<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ReviewRequest;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function dashboard(): View
    {
        $user = Auth::user();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $ordersCount = $user->orders()->count();
        $wishlistCount = $user->wishlist?->items()->count() ?? 0;
        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        return view('customer.dashboard', compact(
            'user',
            'recentOrders',
            'ordersCount',
            'wishlistCount',
            'defaultAddress'
        ));
    }

    public function profile(): View
    {
        $user = Auth::user()->load('profile');

        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^09[0-9]{9}$/', "unique:users,phone,{$user->id}"],
            'national_code' => ['nullable', 'string', 'size:10'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->save();

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'national_code' => $validated['national_code'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]
        );

        return back()->with('success', 'اطلاعات حساب کاربری با موفقیت بروزرسانی شد.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'current_password.current_password' => 'رمز عبور فعلی نادرست است.',
            'password.confirmed' => 'تکرار رمز عبور جدید مطابقت ندارد.',
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($request->input('password'))]);

        return back()->with('success', 'رمز عبور شما با موفقیت تغییر یافت.');
    }

    public function addresses(): View
    {
        $addresses = Auth::user()->addresses()->latest()->get();

        return view('customer.addresses', compact('addresses'));
    }

    public function storeAddress(AddressRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($request->validated());

        return back()->with('success', 'آدرس جدید با موفقیت ثبت شد.');
    }

    public function updateAddress(AddressRequest $request, Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->validated());

        return back()->with('success', 'آدرس با موفقیت بروزرسانی گردید.');
    }

    public function destroyAddress(Address $address): RedirectResponse
    {
        $this->authorize('delete', $address);
        $address->delete();

        return back()->with('info', 'آدرس مورد نظر حذف شد.');
    }

    public function setDefaultAddress(Address $address): RedirectResponse
    {
        $this->authorize('update', $address);

        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'آدرس به عنوان پیش‌فرض تنظیم شد.');
    }

    public function orders(): View
    {
        $orders = Auth::user()->orders()->with('items.product.primaryImage')->latest()->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function showOrder(Order $order): View
    {
        $this->authorize('view', $order);
        $order->load(['items.product.primaryImage', 'payments']);

        return view('customer.order-detail', compact('order'));
    }

    public function cancelOrder(Order $order): RedirectResponse
    {
        $this->authorize('cancel', $order);

        $order->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Restore stock
        foreach ($order->items as $item) {
            $this->inventoryService->restoreStock(
                productId: $item->product_id,
                variantId: $item->product_variant_id,
                quantity: $item->quantity,
                reason: 'customer_cancelled',
                referenceId: "ORDER-{$order->order_number}",
                userId: Auth::id()
            );
        }

        return back()->with('success', 'سفارش با موفقیت لغو شد و موجودی به انبار بازگردانده شد.');
    }

    public function storeReview(ReviewRequest $request, int $productId): RedirectResponse
    {
        $user = Auth::user();
        $product = Product::published()->findOrFail($productId);

        $hasPurchased = $user->orders()
            ->where('payment_status', 'paid')
            ->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        Review::updateOrCreate(
            [
                'product_id' => $productId,
                'user_id' => $user->id,
            ],
            [
                'rating' => $request->validated('rating'),
                'title' => $request->validated('title'),
                'body' => $request->validated('body'),
                'is_verified_purchase' => $hasPurchased,
                'status' => 'approved', // Published immediately; admin can still moderate/delete in admin panel
            ]
        );

        return back()->with('success', 'دیدگاه شما با موفقیت ثبت و منتشر گردید.');
    }
}
