<?php

if (! function_exists('format_price')) {
    /**
     * Format a monetary amount with thousands separators (3 digits by 3).
     *
     * Example: 74500000 => "74,500,000"
     */
    function format_price(int|float|string|null $amount): string
    {
        return number_format((int) round((float) $amount));
    }
}

if (! function_exists('fa_num')) {
    /**
     * Convert English digits to Persian digits if current locale is Persian ('fa').
     *
     * Example: 1518 => "۱۵۱۸" (in fa) or "1518" (in en)
     */
    function fa_num(int|float|string|null $number): string
    {
        if ($number === null || $number === '') {
            return '';
        }

        if (function_exists('app') && app()->getLocale() !== 'fa') {
            return (string) $number;
        }

        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ','];
        $fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٬'];

        return str_replace($en, $fa, (string) $number);
    }
}
