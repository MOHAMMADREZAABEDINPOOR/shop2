@extends('layouts.admin')

@section('title', __('مدیریت برندها'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('برندهای تجاری') }}</h1>
            <p class="text-xs text-gray-500 mt-1">{{ __('تولیدکنندگان و برندهای کالاها در فروشگاه') }}</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-rose-600/20 transition-all">{{ __('+ افزودن برند جدید') }}</a>
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

    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 text-gray-500 border-b border-gray-200 dark:border-zinc-800 font-bold">
                    <tr>
                        <th class="p-4">{{ __('لوگو') }}</th>
                        <th class="p-4">{{ __('نام برند') }}</th>
                        <th class="p-4">{{ __('اسلاگ') }}</th>
                        <th class="p-4">{{ __('تعداد کالا') }}</th>
                        <th class="p-4">{{ __('وب‌سایت') }}</th>
                        <th class="p-4">{{ __('وضعیت') }}</th>
                        <th class="p-4 text-left">{{ __('عملیات') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="p-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 flex items-center justify-center overflow-hidden">
                                    @if($brand->logo)
                                        <img src="{{ Str::startsWith($brand->logo, 'http') ? $brand->logo : Storage::url($brand->logo) }}" class="w-full h-full object-contain p-1">
                                    @else
                                        <span class="text-xs font-bold text-gray-400">{{ mb_substr($brand->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 font-bold text-gray-900 dark:text-white">
                                {{ $brand->name }}
                            </td>
                            <td class="p-4 font-mono text-gray-400 text-[11px]">
                                {{ $brand->slug }}
                            </td>
                            <td class="p-4 font-semibold text-gray-700 dark:text-gray-300">
                                {{ number_format($brand->products_count) }} کالا
                            </td>
                            <td class="p-4 text-gray-500">
                                @if($brand->website)
                                    <a href="{{ $brand->website }}" target="_blank" class="text-blue-600 hover:underline font-mono text-[11px]">{{ parse_url($brand->website, PHP_URL_HOST) ?? $brand->website }}</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($brand->is_active)
                                    <span class="px-2 py-0.5 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 rounded-full font-bold text-[10px]">{{ __('فعال') }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-zinc-800 text-gray-500 rounded-full font-bold text-[10px]">{{ __('غیرفعال') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-zinc-800 rounded-lg transition-colors" title="ویرایش">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" onsubmit="return confirm('{{ __('آیا از حذف این برند اطمینان دارید؟') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-zinc-800 rounded-lg transition-colors" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500 text-xs">{{ __('هیچ برندی ثبت نشده است.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
