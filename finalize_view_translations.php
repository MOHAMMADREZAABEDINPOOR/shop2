<?php

$pairs = [
    'کاتالوگ و لیست جامع محصولات (:count کالا)' => 'Comprehensive Product Catalog & List (:count items)',
    'نمایش :from تا :to از :total کالا' => 'Showing :from to :to of :total products',
    'پاکسازی فیلترها' => 'Clear Filters',
    'تخفیف‌گذاری سریع' => 'Quick Discount',
    'ویرایش کامل' => 'Full Edit',
    'جزئیات سفارش: :number' => 'Order Details: :number',
    'سفارش #:number' => 'Order #:number',
    'ثبت شده در: :date' => 'Registered on: :date',
    'تنوع: :variant' => 'Variant: :variant',
    'کد: :sku' => 'Code: :sku',
    'مجموع اقلام:' => 'Subtotal:',
    'تخفیف (کوپن :code):' => 'Discount (Coupon :code):',
    'هزینه حمل و نقل:' => 'Shipping Cost:',
    'رایگان' => 'Free',
    'مالیات بر ارزش افزوده:' => 'VAT / Tax:',
    'مبلغ نهایی پرداختی:' => 'Final Payable Amount:',
    'آدرس و مشخصات تحویل‌گیرنده' => 'Recipient Address & Details',
    'نشانی پستی:' => 'Postal Address:',
    'پست پیشتاز اکسپرس' => 'Express Post',
    'یادداشت مشتری:' => 'Customer Note:',
    'مدیریت وضعیت سفارش' => 'Manage Order Status',
    'وضعیت فعلی سفارش' => 'Current Order Status',
    'در حال پردازش و بسته‌بندی' => 'Processing & Packaging',
    'تحویل به پست (ارسال شد)' => 'Shipped to Post',
    'تحویل مشتری داده شد' => 'Delivered to Customer',
    'لغو سفارش (برگشت موجودی)' => 'Cancel Order (Restock)',
    'مرجوع و استرداد وجه' => 'Returned & Refunded',
    'کد رهگیری پستی (Tracking Code)' => 'Postal Tracking Code',
    'بروزرسانی وضعیت سفارش' => 'Update Order Status',
    'اطلاعات درگاه پرداخت' => 'Payment Gateway Info',
    'درگاه:' => 'Gateway:',
    'تراکنش #:id' => 'Transaction #:id',
    'کد پیگیری: :code' => 'Tracking Code: :code',
    'ندارد' => 'None',
    'شناسه تراکنش درگاه: :id' => 'Gateway Transaction ID: :id',
    'سفارش' => 'Order',
    'جزئیات سفارش:' => 'Order Details:',
    'تنوع:' => 'Variant:',
    'کد:' => 'Code:',
    'تراکنش' => 'Transaction',
    'کد پیگیری:' => 'Tracking Code:',
    'شناسه تراکنش درگاه:' => 'Gateway Transaction ID:',
    'تخفیف (کوپن' => 'Discount (Coupon',
    'ثبت شده در:' => 'Registered at:',
];

$enPath = __DIR__ . '/lang/en.json';
$faPath = __DIR__ . '/lang/fa.json';

$en = json_decode(file_get_contents($enPath), true) ?: [];
$fa = json_decode(file_get_contents($faPath), true) ?: [];

foreach ($pairs as $faStr => $enStr) {
    $en[$faStr] = $enStr;
    $en[$enStr] = $enStr;
    $fa[$faStr] = $faStr;
    $fa[$enStr] = $faStr;
}

ksort($en);
ksort($fa);

file_put_contents($enPath, json_encode($en, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
file_put_contents($faPath, json_encode($fa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Updated lang files with final pairs (" . count($en) . " keys).\n";
