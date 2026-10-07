<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(Request $request): View
    {
        $query = Banner::query();

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $banners = $query->orderBy('position')->orderBy('sort_order')->paginate(20)->withQueryString();

        $counts = [
            'all' => Banner::count(),
            'hero' => Banner::where('position', 'hero')->count(),
            'promo_top' => Banner::where('position', 'promo_top')->count(),
            'promo_mid' => Banner::where('position', 'promo_mid')->count(),
            'promo_bottom' => Banner::where('position', 'promo_bottom')->count(),
            'sidebar' => Banner::where('position', 'sidebar')->count(),
        ];

        return view('admin.banners.index', compact('banners', 'counts'));
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'link_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'position' => 'required|in:hero,promo_top,promo_mid,promo_bottom,sidebar',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image_path'] = trim($validated['image_url']);
        } else {
            return back()->withInput()->withErrors(['image' => 'لطفاً یک فایل تصویر آپلود کنید یا آدرس اینترنتی تصویر را درج کنید.']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        unset($validated['image_url']);

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'پوستر با موفقیت ایجاد و ذخیره شد.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'image_url' => 'nullable|string|max:1000',
            'link_url' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:100',
            'position' => 'required|in:hero,promo_top,promo_mid,promo_bottom,sidebar',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old uploaded file if it was locally stored
            if (! str_starts_with($banner->image_path, 'http') && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('banners', 'public');
        } elseif (! empty($validated['image_url'])) {
            $validated['image_path'] = trim($validated['image_url']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        unset($validated['image_url']);

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'پوستر با موفقیت بروزرسانی شد.');
    }

    public function toggleStatus(Banner $banner): RedirectResponse
    {
        $banner->update([
            'is_active' => ! $banner->is_active,
        ]);

        $statusText = $banner->is_active ? 'فعال' : 'غیرفعال';

        return back()->with('success', "وضعیت پوستر «{$banner->title}» به {$statusText} تغییر یافت.");
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        if (! str_starts_with($banner->image_path, 'http') && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return back()->with('info', 'پوستر با موفقیت حذف گردید.');
    }
}
