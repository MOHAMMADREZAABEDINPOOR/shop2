@extends('layouts.admin')

@section('title', __('مدیریت و تأیید دیدگاه‌ها'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">نظرات و دیدگاه‌های خریداران</h1>
            <p class="text-xs text-gray-500 mt-1">بررسی، انتشار یا رد نظرات ثبت‌شده کاربران برای محصولات</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 rounded-2xl text-emerald-700 dark:text-emerald-400 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/50 rounded-2xl text-blue-700 dark:text-blue-400 text-xs font-bold">
            {{ session('info') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="flex gap-2">
        <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ !request('status') ? 'bg-rose-600 text-white' : 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-gray-300' }}">
            همه دیدگاه‌ها
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'pending' ? 'bg-rose-600 text-white' : 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-gray-300' }}">
            در انتظار بررسی
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'approved' ? 'bg-rose-600 text-white' : 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-gray-300' }}">
            تأیید شده
        </a>
        <a href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors {{ request('status') === 'rejected' ? 'bg-rose-600 text-white' : 'bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 text-gray-700 dark:text-gray-300' }}">
            رد شده
        </a>
    </div>

    <!-- Reviews List -->
    <div class="space-y-4">
        @forelse($reviews as $review)
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 dark:border-zinc-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-zinc-800 flex items-center justify-center font-bold text-gray-600 dark:text-gray-300 text-xs">
                            {{ mb_substr($review->user?->name ?? 'کاربر', 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $review->user?->name ?? 'کاربر مهمان' }}</span>
                                @if($review->is_verified_purchase)
                                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded-md text-[10px] font-bold">خریدار کالا</span>
                                @endif
                            </div>
                            <span class="text-[10px] text-gray-400">{{ $review->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                            <span class="mr-1 text-gray-700 dark:text-gray-300 font-bold">({{ $review->rating }}/5)</span>
                        </div>

                        <div>
                            @if($review->status === 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded-lg text-[10px] font-bold">تأیید شده</span>
                            @elseif($review->status === 'pending')
                                <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950 text-amber-600 rounded-lg text-[10px] font-bold">در انتظار بررسی</span>
                            @else
                                <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950 text-rose-600 rounded-lg text-[10px] font-bold">رد شده</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Product Subject -->
                @if($review->product)
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>محصول مرتبط:</span>
                        <a href="{{ route('product.show', $review->product->slug) }}" target="_blank" class="font-bold text-gray-800 dark:text-gray-200 hover:text-rose-600 transition-colors">
                            {{ $review->product->name }}
                        </a>
                    </div>
                @endif

                <!-- Review Content -->
                <div class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-zinc-800/40 p-4 rounded-xl">
                    @if($review->title)
                        <h4 class="font-bold mb-1 text-gray-900 dark:text-white">{{ $review->title }}</h4>
                    @endif
                    <p>{{ $review->body }}</p>
                </div>

                <!-- Action Controls -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-2">
                    <form action="{{ route('admin.reviews.status', $review) }}" method="POST" class="flex flex-wrap items-center gap-2">
                        @csrf
                        <input type="text" name="admin_notes" value="{{ $review->admin_notes }}" placeholder="{{ __('یادداشت مدیر (اختیاری)') }}"
                               class="px-3 py-1.5 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500">

                        <button type="submit" name="status" value="approved" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-colors">
                            ✓ تأیید و انتشار
                        </button>
                        <button type="submit" name="status" value="rejected" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition-colors">
                            ✕ رد دیدگاه
                        </button>
                    </form>

                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('{{ __('آیا از حذف این نظر اطمینان دارید؟') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-700 text-xs font-bold p-1">
                            حذف دیدگاه
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-12 text-center text-gray-400 text-xs">
                دیدگاهی برای نمایش وجود ندارد.
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="pt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
