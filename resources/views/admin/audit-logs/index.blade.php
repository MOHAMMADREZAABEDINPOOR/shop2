@extends('layouts.admin')

@section('title', __('لاگ‌های امنیتی و سیستمی (Audit Logs)'))

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">گزارش تغییرات و لاگ‌های سیستمی</h1>
            <p class="text-xs text-gray-500 mt-1">ردیابی تمامی عملیات حساس، تغییرات محصولات، وضعیت سفارش‌ها و فعالیت ادمین‌ها</p>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="action" value="{{ request('action') }}" placeholder="{{ __('جستجو بر اساس نام عملیات (مثلاً: product.updated یا order)...') }}"
                   class="flex-1 px-4 py-2 bg-gray-50 dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500 font-mono dir-ltr text-left">
            <button type="submit" class="px-5 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 rounded-xl text-xs font-bold hover:opacity-90">
                فیلتر
            </button>
            @if(request('action'))
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 rounded-xl text-xs font-bold hover:bg-gray-200">
                    پاک کردن
                </a>
            @endif
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-gray-50 dark:bg-zinc-800/50 text-gray-500 border-b border-gray-200 dark:border-zinc-800 font-bold">
                    <tr>
                        <th class="p-4">زمان ثبت</th>
                        <th class="p-4">کاربر مجری</th>
                        <th class="p-4">عنوان عملیات</th>
                        <th class="p-4">موجودیت مرتبط</th>
                        <th class="p-4">{{ __('آدرس IP') }}</th>
                        <th class="p-4">جزئیات تغییرات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="p-4 font-mono text-[11px] text-gray-500 dir-ltr text-start">
                                {{ $log->created_at->format('Y/m/d H:i:s') }}
                            </td>
                            <td class="p-4">
                                @if($log->user)
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $log->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-mono">{{ $log->user->email }}</div>
                                @else
                                    <span class="text-gray-400">سیستم خودکار</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 bg-zinc-100 dark:bg-zinc-800 font-mono text-[11px] font-bold text-rose-600 dark:text-rose-400 rounded-lg">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-4 font-mono text-gray-500 text-[11px]">
                                @if($log->auditable_type)
                                    {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 font-mono text-[11px] text-gray-400">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="p-4 max-w-xs truncate" x-data="{ expanded: false }">
                                <button @click="expanded = !expanded" class="text-blue-600 hover:underline text-[11px] font-semibold">
                                    مشاهده Payload
                                </button>
                                <div x-show="expanded" x-cloak class="mt-2 p-2 bg-gray-50 dark:bg-zinc-950 border border-gray-200 dark:border-zinc-800 rounded-xl font-mono text-[10px] dir-ltr text-left overflow-x-auto max-h-40">
                                    @if($log->old_values)
                                        <p class="text-rose-500 font-bold mb-1">Old: {{ json_encode($log->old_values, JSON_UNESCAPED_UNICODE) }}</p>
                                    @endif
                                    @if($log->new_values)
                                        <p class="text-emerald-500 font-bold">New: {{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE) }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 text-xs">لاگی در سیستم ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-zinc-800">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
