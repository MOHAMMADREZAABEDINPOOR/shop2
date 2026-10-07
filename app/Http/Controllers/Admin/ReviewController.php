<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with(['product.primaryImage', 'user']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, Review $review): RedirectResponse
    {
        $request->validate(['status' => 'required|in:approved,rejected,pending']);

        $review->update([
            'status' => $request->input('status'),
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', 'وضعیت دیدگاه تغییر یافت.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('info', 'دیدگاه حذف شد.');
    }
}
