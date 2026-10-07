<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected AuditService $auditService
    ) {}

    public function index(Request $request): View
    {
        $query = Product::with(['category', 'brand', 'primaryImage']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($hasDiscount = $request->input('has_discount')) {
            if ($hasDiscount === 'yes') {
                $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
            } elseif ($hasDiscount === 'no') {
                $query->where(function ($q) {
                    $q->whereNull('sale_price')->orWhereColumn('sale_price', '>=', 'price');
                });
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']).'-'.Str::random(5);
        }

        DB::transaction(function () use ($request, $data) {
            $product = Product::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // Create default inventory
            $product->inventory()->create([
                'stock' => $product->stock,
                'reserved_stock' => 0,
                'low_stock_threshold' => 5,
            ]);

            $this->auditService->log('product.created', $product, null, $product->toArray());
        });

        return redirect()->route('admin.products.index')->with('success', 'محصول جدید با موفقیت ایجاد گردید.');
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $product->load(['images', 'variants']);

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $oldValues = $product->toArray();

        DB::transaction(function () use ($request, $product, $data, $oldValues) {
            $product->update($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => $product->images()->count() === 0,
                        'sort_order' => $product->images()->count() + $index,
                    ]);
                }
            }

            // Update inventory record stock
            if ($product->inventory) {
                $product->inventory->update(['stock' => $product->stock]);
            }

            $this->auditService->log('product.updated', $product, $oldValues, $product->toArray());
        });

        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت بروزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $oldValues = $product->toArray();
        $product->delete();

        $this->auditService->log('product.deleted', $product, $oldValues, null);

        return back()->with('info', 'محصول با موفقیت حذف (آرشیو) گردید.');
    }

    public function quickDiscount(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:set_percent,set_price,remove',
            'discount_percent' => 'nullable|numeric|min:1|max:99',
            'sale_price' => 'nullable|numeric|min:0',
        ]);

        $oldValues = $product->toArray();

        if ($request->input('action') === 'remove') {
            $product->update(['sale_price' => null]);
            $msg = "تخفیف محصول «{$product->name}» با موفقیت حذف گردید.";
        } elseif ($request->input('action') === 'set_percent') {
            $percent = (float) $request->input('discount_percent');
            $salePrice = round($product->price * (1 - ($percent / 100)), -3);
            $product->update(['sale_price' => $salePrice]);
            $msg = "تخفیف {$percent}٪ با موفقیت بر روی محصول «{$product->name}» اعمال شد (قیمت فروش ویژه: ".format_price($salePrice).' تومان).';
        } else {
            $salePrice = (float) $request->input('sale_price');
            $product->update(['sale_price' => $salePrice]);
            $msg = "قیمت تخفیف‌خورده با موفقیت بر روی «{$product->name}» اعمال شد.";
        }

        $this->auditService->log('product.discount_updated', $product, $oldValues, $product->toArray());

        return back()->with('success', $msg);
    }
}
