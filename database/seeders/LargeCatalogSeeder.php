<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeCatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Increase time limit for batch seeding
        set_time_limit(600);

        // 1. Ensure Categories
        $categoriesData = [
            'mobile-phones' => ['name' => 'گوشی موبایل', 'icon' => '📱', 'parent' => 'digital-goods'],
            'tablets' => ['name' => 'تبلت و کتابخوان', 'icon' => '📟', 'parent' => 'digital-goods'],
            'laptops' => ['name' => 'لپ‌تاپ و اولترابوک', 'icon' => '💻', 'parent' => 'digital-goods'],
            'smart-watches' => ['name' => 'ساعت و مچ‌بند هوشمند', 'icon' => '⌚', 'parent' => 'digital-goods'],
            'headphones' => ['name' => 'هدفون و هندزفری', 'icon' => '🎧', 'parent' => 'accessories'],
            'speakers-soundbars' => ['name' => 'اسپیکر و ساندبار', 'icon' => '🔊', 'parent' => 'audio-video'],
            'powerbanks' => ['name' => 'پاوربانک و شارژر همراه', 'icon' => '🔋', 'parent' => 'accessories'],
            'chargers-cables' => ['name' => 'کابل و شارژر دیواری', 'icon' => '🔌', 'parent' => 'accessories'],
            'storage-flash' => ['name' => 'هارد اکسترنال و فلش مموری', 'icon' => '💾', 'parent' => 'accessories'],
            'consoles' => ['name' => 'کنسول بازی و لوازم گیمینگ', 'icon' => '🎮', 'parent' => 'gaming'],
            'tv' => ['name' => 'تلویزیون هوشمند', 'icon' => '📺', 'parent' => 'audio-video'],
            'monitors' => ['name' => 'مانیتور و نمایشگر', 'icon' => '🖥️', 'parent' => 'computer-parts'],
            'keyboards-mice' => ['name' => 'ماوس و کیبورد', 'icon' => '⌨️', 'parent' => 'computer-parts'],
            'pc-components' => ['name' => 'قطعات اصلی کامپیوتر', 'icon' => '⚙️', 'parent' => 'computer-parts'],
            'gadgets-cameras' => ['name' => 'دوربین و گجت‌های هوشمند', 'icon' => '📷', 'parent' => 'smart-home'],
        ];

        $parents = [
            'digital-goods' => Category::firstOrCreate(['slug' => 'digital-goods'], ['name' => 'کالای دیجیتال', 'icon' => '💻', 'sort_order' => 1]),
            'accessories' => Category::firstOrCreate(['slug' => 'accessories'], ['name' => 'لوازم جانبی دیجیتال', 'icon' => '🎧', 'sort_order' => 2]),
            'audio-video' => Category::firstOrCreate(['slug' => 'audio-video'], ['name' => 'صوتی و تصویری', 'icon' => '📺', 'sort_order' => 3]),
            'gaming' => Category::firstOrCreate(['slug' => 'gaming'], ['name' => 'بازی و سرگرمی', 'icon' => '🎮', 'sort_order' => 4]),
            'computer-parts' => Category::firstOrCreate(['slug' => 'computer-parts'], ['name' => 'کامپیوتر و اداری', 'icon' => '🖥️', 'sort_order' => 5]),
            'smart-home' => Category::firstOrCreate(['slug' => 'smart-home'], ['name' => 'خانه هوشمند و گجت', 'icon' => '🏠', 'sort_order' => 6]),
        ];

        $categories = [];
        foreach ($categoriesData as $slug => $data) {
            $categories[$slug] = Category::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'icon' => $data['icon'],
                'parent_id' => $parents[$data['parent']]->id,
                'is_active' => true,
            ]);
        }

        // 2. Ensure Brands
        $brandsData = [
            'samsung' => ['name' => 'سامسونگ (Samsung)', 'website' => 'https://samsung.com'],
            'apple' => ['name' => 'اپل (Apple)', 'website' => 'https://apple.com'],
            'xiaomi' => ['name' => 'شیائومی (Xiaomi)', 'website' => 'https://mi.com'],
            'asus' => ['name' => 'ایسوس (ASUS)', 'website' => 'https://asus.com'],
            'sony' => ['name' => 'سونی (Sony)', 'website' => 'https://sony.com'],
            'lenovo' => ['name' => 'لنوو (Lenovo)', 'website' => 'https://lenovo.com'],
            'hp' => ['name' => 'اچ‌پی (HP)', 'website' => 'https://hp.com'],
            'dell' => ['name' => 'دل (Dell)', 'website' => 'https://dell.com'],
            'anker' => ['name' => 'انکر (Anker)', 'website' => 'https://anker.com'],
            'jbl' => ['name' => 'جی‌بی‌ال (JBL)', 'website' => 'https://jbl.com'],
            'logitech' => ['name' => 'لاجیتک (Logitech)', 'website' => 'https://logitech.com'],
            'razer' => ['name' => 'ریزر (Razer)', 'website' => 'https://razer.com'],
            'sandisk' => ['name' => 'سندیسک (SanDisk)', 'website' => 'https://sandisk.com'],
            'western-digital' => ['name' => 'وسترن دیجیتال (WD)', 'website' => 'https://westerndigital.com'],
            'baseus' => ['name' => 'بیسوس (Baseus)', 'website' => 'https://baseus.com'],
            'microsoft' => ['name' => 'مایکروسافت (Microsoft)', 'website' => 'https://microsoft.com'],
            'dji' => ['name' => 'دی‌جی‌آی (DJI)', 'website' => 'https://dji.com'],
            'canon' => ['name' => 'کانن (Canon)', 'website' => 'https://canon.com'],
            'marshall' => ['name' => 'مارشال (Marshall)', 'website' => 'https://marshallheadphones.com'],
            'bose' => ['name' => 'بوز (Bose)', 'website' => 'https://bose.com'],
            'sennheiser' => ['name' => 'سنهایزر (Sennheiser)', 'website' => 'https://sennheiser.com'],
            'honor' => ['name' => 'آنر (Honor)', 'website' => 'https://hihonor.com'],
            'huawei' => ['name' => 'هواوی (Huawei)', 'website' => 'https://huawei.com'],
            'nothing' => ['name' => 'ناتینگ (Nothing)', 'website' => 'https://nothing.tech'],
        ];

        $brands = [];
        foreach ($brandsData as $slug => $data) {
            $brands[$slug] = Brand::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'website' => $data['website'],
                'is_active' => true,
            ]);
        }

        // 3. Multi-Angle High-Resolution Image Library by Type
        $imageSets = [
            'phone' => [
                'front' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1565849904461-04a58ad377e0?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80',
            ],
            'laptop' => [
                'front' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1541807084-5c52b6b3adef?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
            ],
            'tablet' => [
                'front' => 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1589739900243-4b52cd9b104e?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1527698266440-12104e498b76?w=800&auto=format&fit=crop&q=80',
            ],
            'watch' => [
                'front' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800&auto=format&fit=crop&q=80',
            ],
            'headphone' => [
                'front' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800&auto=format&fit=crop&q=80',
            ],
            'speaker' => [
                'front' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1543512214-318c7553f230?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1518444065439-e933c06ce9cd?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1558089687-f282ffcbc126?w=800&auto=format&fit=crop&q=80',
            ],
            'powerbank' => [
                'front' => 'https://images.unsplash.com/photo-1609592426508-cc8534882ce9?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1622445262464-84b24e406223?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1568843479605-fad270929fa7?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=800&auto=format&fit=crop&q=80',
            ],
            'console' => [
                'front' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1600080972464-8e5f35f63d08?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1592840496694-26d035b52b48?w=800&auto=format&fit=crop&q=80',
            ],
            'accessory' => [
                'front' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1512499617640-c74ae3a79d37?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1629654297299-c8506221ca97?w=800&auto=format&fit=crop&q=80',
            ],
            'gadget' => [
                'front' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&auto=format&fit=crop&q=80',
                'back' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&auto=format&fit=crop&q=80',
                'side' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&auto=format&fit=crop&q=80',
                'lifestyle' => 'https://images.unsplash.com/photo-1507646227500-4d389b0012be?w=800&auto=format&fit=crop&q=80',
                'angle' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        // 4. Catalog Archetypes for Generating 1000+ Unique Products
        $archetypes = [
            // SMARTPHONES (200 items)
            [
                'category' => 'mobile-phones',
                'imgType' => 'phone',
                'count' => 200,
                'brands' => ['samsung', 'apple', 'xiaomi', 'honor', 'nothing'],
                'series' => [
                    'samsung' => ['Galaxy S24 Ultra', 'Galaxy S24+', 'Galaxy S24', 'Galaxy S23 FE', 'Galaxy A55 5G', 'Galaxy A35 5G', 'Galaxy A15', 'Galaxy Z Fold 5', 'Galaxy Z Flip 5', 'Galaxy A25 5G'],
                    'apple' => ['iPhone 15 Pro Max', 'iPhone 15 Pro', 'iPhone 15 Plus', 'iPhone 15', 'iPhone 14 Pro Max', 'iPhone 14 Plus', 'iPhone 14', 'iPhone 13', 'iPhone 13 mini', 'iPhone SE 2022'],
                    'xiaomi' => ['Xiaomi 14 Ultra', 'Xiaomi 14 Pro', 'Xiaomi 13T Pro', 'Redmi Note 13 Pro+ 5G', 'Redmi Note 13 Pro', 'Redmi Note 13 4G', 'Poco X6 Pro 5G', 'Poco F6 Pro', 'Poco M6 Pro', 'Redmi 13C'],
                    'honor' => ['Magic 6 Pro', 'Honor 90 5G', 'Honor X9b 5G', 'Honor X8b', 'Honor X7b'],
                    'nothing' => ['Phone (2)', 'Phone (2a)', 'Phone (1)'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'نمایشگر' => '6.7 اینچ AMOLED با نرخ نوسازی 120Hz و HDR10+',
                    'پردازنده' => 'پردازنده 8 هسته‌ای نسل جدید 4 نانومتری',
                    'حافظه رم' => $ram,
                    'حافظه داخلی' => $storage,
                    'دوربین اصلی' => 'چندگانه مجهز به لنز واید تا 200 مگاپیکسل و OIS',
                    'دوربین سلفی' => '12 تا 32 مگاپیکسل با قابلیت فیلمبرداری 4K',
                    'باتری' => '5000 میلی‌آمپر با فناوری شارژ فوق سریع',
                    'سیستم عامل' => 'جدیدترین نسخه با تضمین آپدیت نرم‌افزاری 4 ساله',
                    'گارانتی' => '۱۸ ماهه شرکتی رجیستر شده با کد فعالسازی همتا',
                ],
                'price_range' => [8500000, 95000000],
            ],

            // LAPTOPS & ULTRABOOKS (150 items)
            [
                'category' => 'laptops',
                'imgType' => 'laptop',
                'count' => 150,
                'brands' => ['asus', 'apple', 'lenovo', 'hp', 'dell'],
                'series' => [
                    'asus' => ['ROG Strix G16', 'TUF Gaming A15', 'ZenBook 14 OLED', 'Vivobook Pro 15', 'ROG Zephyrus G14', 'ExpertBook B5', 'TUF Dash F15'],
                    'apple' => ['MacBook Pro 16 M3 Max', 'MacBook Pro 14 M3 Pro', 'MacBook Air 15 M3', 'MacBook Air 13 M3', 'MacBook Pro 14 M3', 'MacBook Air 13 M2'],
                    'lenovo' => ['Legion Pro 5', 'LOQ 15 Gaming', 'ThinkPad X1 Carbon Gen 11', 'IdeaPad Gaming 3', 'Yoga Slim 7 Pro', 'ThinkBook 16 G6'],
                    'hp' => ['Omen 16 Gaming', 'Victus 16', 'Victus 15', 'Spectre x360 14', 'Envy 16', 'Pavilion Gaming 15'],
                    'dell' => ['XPS 15 9530', 'Alienware m16 R2', 'G15 5530', 'Inspiron 16 Plus', 'Latitude 5540'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'پردازنده مرکزی (CPU)' => 'اینتل نسل 13 و 14 / اپل سیلیکون قدرتمند سری M',
                    'پردازنده گرافیکی (GPU)' => 'NVIDIA GeForce RTX 40 سری 4060 تا 4080 اختصاصی',
                    'حافظه رم (RAM)' => $ram,
                    'حافظه ذخیره‌سازی' => $storage.' از نوع NVMe M.2 SSD با سرعت 7000MB/s',
                    'صفحه نمایش' => '15.6 الی 16 اینچ با رزولوشن WQXGA و پوشش 100% sRGB',
                    'کیبورد و تاچ‌پد' => 'کیبورد ارگونومیک دارای نور پس‌زمینه RGB چند ناحیه‌ای',
                    'سیستم خنک‌کننده' => 'سیستم خنک‌کننده هوشمند مجهز به فن‌های دوگانه Arc Flow',
                    'گارانتی' => '۲۴ ماهه رسمی یکپارچه به همراه هدایای ویژه شرکتی',
                ],
                'price_range' => [32000000, 160000000],
            ],

            // TABLETS (80 items)
            [
                'category' => 'tablets',
                'imgType' => 'tablet',
                'count' => 80,
                'brands' => ['apple', 'samsung', 'xiaomi', 'lenovo'],
                'series' => [
                    'apple' => ['iPad Pro 13 M4', 'iPad Pro 11 M4', 'iPad Air 13 M2', 'iPad Air 11 M2', 'iPad 10th Gen', 'iPad mini 6'],
                    'samsung' => ['Galaxy Tab S9 Ultra', 'Galaxy Tab S9+', 'Galaxy Tab S9', 'Galaxy Tab S9 FE+', 'Galaxy Tab S9 FE', 'Galaxy Tab A9+'],
                    'xiaomi' => ['Xiaomi Pad 6 Pro', 'Xiaomi Pad 6', 'Redmi Pad Pro 12.1', 'Redmi Pad SE 11'],
                    'lenovo' => ['Tab P12 Pro', 'Tab M11', 'Legion Y700 Gaming Tablet'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'اندازه صفحه نمایش' => '10.9 الی 14.6 اینچ با روشنایی فوق‌العاده 1000 نیت',
                    'پشتیبانی از قلم هوشمند' => 'پشتیبانی کامل از قلم فعال با حساسیت به فشار 4096 سطح',
                    'حافظه رم' => $ram,
                    'حافظه داخلی' => $storage,
                    'اسپیکرها' => 'چهار اسپیکر استریو کالیبره شده با فناوری Dolby Atmos',
                    'ظرفیت باتری' => '8000 الی 11200 میلی‌آمپر با دوام کاری تا 14 ساعت مداوم',
                    'گارانتی' => '۱۸ ماهه معتبر رسمی شرکتی با ضمانت سلامت باتری و ال‌سی‌دی',
                ],
                'price_range' => [9500000, 85000000],
            ],

            // HEADPHONES & EARPHONES (150 items)
            [
                'category' => 'headphones',
                'imgType' => 'headphone',
                'count' => 150,
                'brands' => ['sony', 'apple', 'jbl', 'anker', 'bose', 'sennheiser', 'marshall'],
                'series' => [
                    'sony' => ['WH-1000XM5', 'WH-1000XM4', 'WF-1000XM5', 'LinkBuds S', 'WH-CH720N', 'WF-C700N'],
                    'apple' => ['AirPods Pro 2 USB-C', 'AirPods Max', 'AirPods 3 با کیس MagSafe', 'AirPods 2'],
                    'jbl' => ['Live 660NC', 'Tune 770NC', 'Tune 720BT', 'Wave Beam TWS', 'Live Pro 2 TWS', 'Tour One M2'],
                    'anker' => ['Soundcore Space Q45', 'Soundcore Liberty 4 NC', 'Soundcore Life Q30', 'Soundcore P20i', 'Soundcore A20i'],
                    'bose' => ['QuietComfort Ultra Headphones', 'QuietComfort 45', 'QuietComfort Ultra Earbuds'],
                    'sennheiser' => ['Momentum 4 Wireless', 'Accentum Wireless', 'Momentum True Wireless 4'],
                    'marshall' => ['Major IV Wireless', 'Monitor II A.N.C.', 'Minor III TWS', 'Motif II A.N.C.'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'نوع اتصال' => 'بلوتوث نسخه 5.3 با برد موثر 15 متر و پشتیبانی کابل AUX',
                    'فناوری حذف نویز (ANC)' => 'نویزکنسلینگ فعال هیبریدی تطبیق‌پذیر با هوش مصنوعی',
                    'درایور صوتی' => 'درایورهای داینامیک تیتانیومی با بیس عمیق و تفکیک صدای کریستالی',
                    'پشتیبانی از کدک‌ها' => 'LDAC, AAC, SBC, aptX Adaptive برای خروجی Hi-Res Audio',
                    'میزان شارژدهی' => '30 الی 60 ساعت پخش مداوم موسیقی با قابلیت شارژ سریع',
                    'مقاومت در برابر آب' => 'دارای گواهی استاندارد IPX4 / IPX5 مقاوم در برابر تعریق و باران',
                    'گارانتی' => '۱۲ ماهه تعویض معتبر بدون قید و شرط',
                ],
                'price_range' => [850000, 38000000],
            ],

            // SMARTWATCHES (100 items)
            [
                'category' => 'smart-watches',
                'imgType' => 'watch',
                'count' => 100,
                'brands' => ['apple', 'samsung', 'xiaomi', 'huawei'],
                'series' => [
                    'apple' => ['Apple Watch Ultra 2 Titanium 49mm', 'Apple Watch Series 9 Aluminum 45mm', 'Apple Watch Series 9 41mm', 'Apple Watch SE 2023 44mm', 'Apple Watch SE 2023 40mm'],
                    'samsung' => ['Galaxy Watch 6 Classic 47mm', 'Galaxy Watch 6 Classic 43mm', 'Galaxy Watch 6 44mm', 'Galaxy Watch 6 40mm', 'Galaxy Watch 5 Pro 45mm', 'Galaxy Fit 3'],
                    'xiaomi' => ['Xiaomi Watch 2 Pro LTE', 'Xiaomi Watch S3', 'Xiaomi Smart Band 8 Pro', 'Xiaomi Smart Band 8 Active', 'Redmi Watch 4'],
                    'huawei' => ['Huawei Watch GT 4 46mm', 'Huawei Watch GT 4 41mm', 'Huawei Watch 4 Pro', 'Huawei Band 9'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'نوع صفحه نمایش' => 'AMOLED شفاف با فناوری نمایشگر همیشه روشن (Always-on Display)',
                    'سنسورهای سلامتی' => 'سنسور پایش اکسیژن خون (SpO2)، نوار قلب (ECG) و آنالیز خواب',
                    'قابلیت مکالمه' => 'اسپیکر و میکروفون داخلی HD جهت پاسخگویی مستقیم به تماس‌های تلفنی',
                    'موقعیت‌یابی' => 'GPS دو فرکانسه مستقل (Dual-frequency GPS) با دقت فوق‌العاده',
                    'مقاومت در برابر آب' => 'ضدآب تا عمق 50 متری استاندارد 5ATM مناسب شنا و ورزش‌های آبی',
                    'گارانتی' => '۱۸ ماهه معتبر شرکتی به همراه مهلت تست تعویض ۷ روزه',
                ],
                'price_range' => [1800000, 48000000],
            ],

            // SPEAKERS & SOUNDBARS (80 items)
            [
                'category' => 'speakers-soundbars',
                'imgType' => 'speaker',
                'count' => 80,
                'brands' => ['jbl', 'sony', 'marshall', 'bose', 'anker'],
                'series' => [
                    'jbl' => ['Charge 5', 'Boombox 3', 'Flip 6', 'PartyBox 310', 'PartyBox 110', 'Go 3', 'Clip 4', 'Bar 500 Soundbar'],
                    'sony' => ['SRS-XG500', 'SRS-XE300', 'SRS-XB100', 'HT-S40R 5.1ch Soundbar', 'MHC-V73D High Power Audio'],
                    'marshall' => ['Stanmore III', 'Acton III', 'Woburn III', 'Emberton II', 'Willen'],
                    'bose' => ['SoundLink Revolve+ II', 'SoundLink Flex', 'Smart Ultra Soundbar'],
                    'anker' => ['Soundcore Motion Boom Plus', 'Soundcore Motion+', 'Soundcore Flare 2', 'Soundcore Select Pro'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'توان خروجی واقعی (RMS)' => 'از 30 وات تا 800 وات توان صوتی پرقدرت با بیس کوبنده',
                    'نوع اتصال' => 'بلوتوث نسخه 5.3، ورودی اپتیکال، جک 3.5 میلی‌متری و پورت USB',
                    'عمر باتری قابل حمل' => 'تا 24 ساعت پخش ممتد موسیقی با قابلیت پاوربانک خروجی',
                    'مقاومت در برابر شرایط جوی' => 'گواهی ضدآب و ضدگردوغبار IP67 مناسب مهمانی و فضای باز',
                    'قابلیت جفت‌سازی' => 'پشتیبانی از فناوری PartyBoost / TWS جهت اتصال همزمان چند اسپیکر',
                    'گارانتی' => '۱۸ ماهه رسمی و ضمانت اصالت فیزیکی مادام‌العمر',
                ],
                'price_range' => [1400000, 65000000],
            ],

            // POWERBANKS & CHARGERS (80 items)
            [
                'category' => 'powerbanks',
                'imgType' => 'powerbank',
                'count' => 80,
                'brands' => ['anker', 'xiaomi', 'baseus', 'samsung'],
                'series' => [
                    'anker' => ['737 Power Bank 24000mAh 140W', '537 Power Bank 24000mAh 65W', 'PowerCore 20000mAh PD', 'MagGo 10000mAh Qi2', 'Nano Power Bank 5000mAh'],
                    'xiaomi' => ['20000mAh 50W Flash Charge', '20000mAh 22.5W Redmi Fast Charge', '10000mAh 33W Pocket Edition Pro', '10000mAh Wireless 10W'],
                    'baseus' => ['Blade 100W 20000mAh Ultra Thin', 'Adaman 20000mAh 65W Metal', 'Amblight 30000mAh 65W Multi Port', 'Magnetic 10000mAh 20W'],
                    'samsung' => ['20000mAh 25W Super Fast', '10000mAh 25W Wireless Fast Charge', '10000mAh 25W Type-C'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'ظرفیت اسمی واقعی' => '10000 تا 30000 میلی‌آمپر ساعت با باتری‌های لیتیوم پلیمری گرید A',
                    'حداکثر توان خروجی' => 'توان شارژ سریع از 22.5 وات تا 140 وات PD مناسب لپ‌تاپ و گوشی',
                    'تعداد درگاه‌های خروجی' => 'دارای 3 الی 5 پورت همزمان (Type-C با Power Delivery و USB-A QC)',
                    'سیستم‌های ایمنی' => 'تراشه هوشمند ضداتصال کوتاه، کنترل ولتاژ، جریان بیش از حد و حرارت بالا',
                    'نمایشگر هوشمند' => 'نمایشگر دیجیتالی LED هوشمند برای نشان دادن درصد شارژ و توان خروجی',
                    'گارانتی' => '۱۲ ماهه تعویض سریع گارانتی معتبر شرکتی',
                ],
                'price_range' => [750000, 11500000],
            ],

            // GAMING CONSOLES & ACCESSORIES (80 items)
            [
                'category' => 'consoles',
                'imgType' => 'console',
                'count' => 80,
                'brands' => ['sony', 'microsoft', 'razer', 'logitech'],
                'series' => [
                    'sony' => ['PlayStation 5 Slim Standard 1TB', 'PlayStation 5 Slim Digital 1TB', 'دسته بازی DualSense Edge حرفه‌ای', 'دسته بازی DualSense بی‌سیم', 'کنسول پرتابل PlayStation Portal', 'هدست گیمینگ Pulse Elite بی‌سیم'],
                    'microsoft' => ['Xbox Series X 1TB Console', 'Xbox Series S 1TB Carbon Black', 'Xbox Series S 512GB', 'دسته بازی Xbox Wireless Controller', 'دسته حرفه‌ای Xbox Elite Wireless Series 2'],
                    'razer' => ['هدست گیمینگ BlackShark V2 Pro', 'ماوس گیمینگ DeathAdder V3 Pro', 'کیبورد مکانیکال Huntsman V3 Pro', 'دسته بازی Wolverine V2 Pro'],
                    'logitech' => ['ماوس بی‌سیم G Pro X Superlight 2', 'فرمان بازی G923 TrueForce با پدال', 'هدست گیمینگ G733 Lightspeed Wireless', 'کیبورد مکانیکال G915 TKL'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'پلتفرم هدف' => 'سازگار با کنسول‌های نسل نهم (PS5 / Xbox Series X) و سیستم‌های گیمینگ PC',
                    'فناوری کلیدها و سنسورها' => 'سنسورهای نوری هپتیک فیدبک با تریگرهای تطبیق‌پذیر و تاخیر ورودی نزدیک به صفر',
                    'نورپردازی' => 'سیستم نورپردازی سفارشی RGB با بیش از 16.8 میلیون رنگ قابل همگام‌سازی',
                    'اتصال و شارژدهی' => 'فناوری اتصال بی‌سیم فوق سریع با فرکانس 2.4GHz بدون کوچک‌ترین تاخیر',
                    'گارانتی' => 'ضمانت اصالت و سلامت فیزیکی به همراه ۱۲ ماه خدمات پس از فروش معتبر',
                ],
                'price_range' => [3200000, 48000000],
            ],

            // STORAGE & FLASH (80 items)
            [
                'category' => 'storage-flash',
                'imgType' => 'accessory',
                'count' => 80,
                'brands' => ['sandisk', 'western-digital', 'samsung'],
                'series' => [
                    'sandisk' => ['Extreme Pro Portable SSD 1TB', 'Extreme Portable SSD 2TB', 'Ultra Dual Drive Luxe Type-C 256GB', 'Extreme PRO MicroSDXC 128GB UHS-I', 'Ultra Flair USB 3.0 128GB', 'Extreme PRO CFexpress Type B 256GB'],
                    'western-digital' => ['هارد اکسترنال My Passport 2TB', 'هارد اکسترنال Elements 4TB', 'حافظه SSD اینترنال Black SN850X 1TB', 'هارد اکسترنال My Book 8TB Desktop', 'اس‌اس‌دی اکسترنال Black P40 Game Drive 1TB'],
                    'samsung' => ['حافظه SSD اکسترنال T7 Shield 1TB مقاوم', 'حافظه SSD اکسترنال T7 Touch 2TB', 'اس‌اس‌دی اینترنال 990 Pro 2TB با هیت‌سینک', 'فلش مموری Type-C 128GB Duo Plus'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'نوع رابط کاربری' => 'USB 3.2 Gen 2x2 Type-C و PCIe Gen 4.0 x4 با پهنای باند فوق‌العاده',
                    'سرعت خواندن متوالی' => 'سرعت انتقال شگفت‌انگیز تا 2000 الی 7450 مگابایت بر ثانیه',
                    'مقاومت فیزیکی' => 'مقاوم در برابر سقوط از ارتفاع ۲ متری، استاندارد ضدآب و گردوغبار IP65',
                    'امنیت داده‌ها' => 'رمزگذاری سخت‌افزاری 256 بیتی AES با قابلیت تعریف کلمه عبور اختصاصی',
                    'گارانتی' => '۳۶ الی ۶۰ ماه گارانتی رسمی اصلی تعویض قطعه',
                ],
                'price_range' => [450000, 18500000],
            ],

            // MONITORS & DISPLAYS (80 items)
            [
                'category' => 'monitors',
                'imgType' => 'gadget',
                'count' => 80,
                'brands' => ['asus', 'samsung', 'dell', 'xiaomi'],
                'series' => [
                    'asus' => ['ROG Swift OLED PG27AQDM 27 اینچ 240Hz', 'TUF Gaming VG279Q3A 27 اینچ', 'ProArt Display PA278CV تخصصی طراحی', 'ROG Strix XG32UQ 32 اینچ 4K 160Hz'],
                    'samsung' => ['Odyssey OLED G9 49 اینچ فوق عریض 240Hz', 'Odyssey G7 32 اینچ خمیده 240Hz', 'Smart Monitor M8 32 اینچ 4K', 'Odyssey G5 27 اینچ 165Hz'],
                    'dell' => ['UltraSharp U2723QE 27 اینچ 4K IPS Black', 'Alienware AW3423DWF QD-OLED 34 اینچ', 'Gaming G2724D 27 اینچ QHD 165Hz'],
                    'xiaomi' => ['مانیتور گیمینگ خمیده 34 اینچ WQHD 144Hz', 'مانیتور 27 اینچ 4K UHD با رنگ 99% DCI-P3', 'مانیتور گیمینگ G27i 165Hz Fast IPS'],
                ],
                'specs_template' => fn ($model, $storage, $ram) => [
                    'اندازه و نوع پنل' => '27 الی 49 اینچ خمیده و تخت با پنل‌های OLED و Fast IPS رنگ‌های طبیعی',
                    'نرخ نوسازی (Refresh Rate)' => '144Hz الی 240Hz با زمان پاسخ‌گویی باورنکردنی 0.03 میلی‌ثانیه',
                    'درگاه‌های اتصالی' => 'DisplayPort 1.4، پورت‌های HDMI 2.1 و هاب USB Type-C با توان 90 وات',
                    'فناوری‌های گیمینگ' => 'پشتیبانی کامل از NVIDIA G-Sync Compatible و AMD FreeSync Premium Pro',
                    'ارگونومی پایه' => 'پایه فوق‌حرفه‌ای با قابلیت تنظیم ارتفاع، چرخش افقی، عمودی و شیب دلخواه',
                    'گارانتی' => '۲۴ الی ۳۶ ماه گارانتی رسمی شرکتی با ضمانت پیکسل سوخته',
                ],
                'price_range' => [8500000, 95000000],
            ],
        ];

        // 5. Persian Reviews Bank for Real Engagement
        $reviewTitles = [
            'بهترین خرید امسالم بود، واقعاً راضیم',
            'کیفیت ساخت فوق‌العاده بالا و ارگونومی عالی',
            'از هر نظر عالی، به خصوص سرعت و روانی عملکرد',
            'با این قیمت رقیبی در بازار نداره',
            'بسته‌بندی پلمپ و ارسال سریع دیجی‌استور عالی بود',
            'طراحی بدنه بسیار لوکس و چشم‌نوازه',
            'شارژدهی باتری شگفت‌انگیزه، ۲ روز کامل دوام میاره',
            'صفحه نمایش به شدت شفاف و با کیفیته',
            'امکانات هوش مصنوعی این محصول بی‌نظیره',
            'صدای شفاف و بیس بسیار کوبنده‌ای داره',
        ];

        $reviewBodies = [
            'بعد از چند هفته استفاده مداوم می‌تونم بگم یکی از بهترین خریدهام بوده. هم از نظر کیفیت و هم از نظر عملکرد فراتر از انتظارم ظاهر شد. سرعت کارکرد عالیه و هیچ لگی مشاهده نکردم.',
            'واقعاً ارزش خرید بسیار بالایی داره. قبل از خرید نگران عملکردش بودم ولی وقتی به دستم رسید متوجه کیفیت متریال ساختش شدم. گارانتی شرکتی هم کاملاً رجیستر و معتبر بود.',
            'پیشنهاد می‌کنم حتماً اگر دنبال محصول باکیفیت و با دوام هستید انتخابش کنید. سرعت ارسال دیجی‌استور هم مثل همیشه دقیق و منظم بود. ممنون از پشتیبانی خوبتون.',
            'توی رده قیمتی خودش بدون تردید بهترین انتخاب بازاره. من قبل از خرید بررسی‌های زیادی رو خونده بودم و در عمل هم کاملاً ادعاهای سازنده تایید شد.',
            'بسیار خوش‌دست و سبک، باتری هم با مصرف سنگین بیش از یک روز کامل جواب میده. پردازش تصویر و کیفیت صدای اسپیکرها فوق‌العاده شفاف هست.',
        ];

        // Get users to associate reviews with
        $users = User::take(10)->get();
        if ($users->isEmpty()) {
            $users = collect([User::factory()->create(['name' => 'کاربر خریدار', 'email' => 'reviewer@example.com'])]);
        }

        // Color variants
        $colors = [
            ['name' => 'مشکی تیتانیوم', 'hex' => '#1a1a1a'],
            ['name' => 'خاکستری تیتانیوم', 'hex' => '#6b7280'],
            ['name' => 'نقره‌ای مهتابی', 'hex' => '#e5e7eb'],
            ['name' => 'آبی اقیانوسی', 'hex' => '#1e3a8a'],
            ['name' => 'طلایی کلاسیک', 'hex' => '#d97706'],
            ['name' => 'سفید صدفی', 'hex' => '#f9fafb'],
        ];

        $storages = ['128GB', '256GB', '512GB', '1TB', '2TB'];
        $rams = ['8GB', '12GB', '16GB', '32GB', '64GB'];

        // 6. Generate Products in Database Transactions
        $skuCounter = 1000;
        $totalCreated = 0;

        foreach ($archetypes as $arch) {
            $cat = $categories[$arch['category']];
            $imgSet = $imageSets[$arch['imgType']];
            $targetCount = $arch['count'];

            DB::beginTransaction();

            for ($i = 1; $i <= $targetCount; $i++) {
                $skuCounter++;
                $brandKey = $arch['brands'][array_rand($arch['brands'])];
                $brand = $brands[$brandKey];
                $seriesList = $arch['series'][$brandKey] ?? ['مدل حرفه‌ای'];
                $modelBase = $seriesList[array_rand($seriesList)];

                $storage = $storages[array_rand($storages)];
                $ram = $rams[array_rand($rams)];
                $editionModifier = ($i % 3 === 0) ? 'نسخه گلوبال سفارش اروپا' : (($i % 5 === 0) ? 'نسخه ویژه پلاس گارانتی طلایی' : 'پک اصلی شرکتی');

                $productName = $brand->name.' مدل '.$modelBase.' '.$editionModifier.' ('.$storage.')';
                $slug = Str::slug($brandKey.'-'.$modelBase.'-'.$storage.'-'.$skuCounter.'-'.Str::random(4));

                // Price calculation
                $minPrice = $arch['price_range'][0];
                $maxPrice = $arch['price_range'][1];
                $rawPrice = rand((int) ($minPrice / 10000), (int) ($maxPrice / 10000)) * 10000;

                // Discount on ~35% of products
                $hasDiscount = ($i % 3 === 0 || $i % 7 === 0);
                $discountPercent = $hasDiscount ? rand(5, 30) : 0;
                $salePrice = $hasDiscount ? round($rawPrice * (1 - ($discountPercent / 100)), -4) : null;
                $costPrice = round($rawPrice * 0.85, -4);

                $stock = rand(4, 50);
                $isFeatured = ($i % 6 === 0);
                $isBestSeller = ($i % 4 === 0);
                $isNewArrival = ($i % 5 === 0);

                $specs = ($arch['specs_template'])($modelBase, $storage, $ram);

                $description = "محصول {$productName} یکی از برجسته‌ترین و پرفروش‌ترین تولیدات برند معتبر {$brand->name} است که با بکارگیری نوآورانه‌ترین استانداردهای صنعتی و کیفی تولید گردیده است. این محصول با بهره‌گیری از مهندسی پیشرفته، طول عمر بالا و عملکردی بی‌نقص، گزینه‌ای ایده‌آل برای کاربرانی است که به دنبال اصالت، کیفیت ساخت عالی و ضمانت رسمی هستند. فروشگاه آنلاین دیجی‌استور این کالا را با ضمانت رجیستری، تضمین اصالت و مهلت تست هفت‌روزه ارائه می‌نماید.";
                $shortDesc = "کالای اورجینال {$brand->name} با ضمانت تعویض و اصالت فیزیکی | قابلیت‌های ارگونومیک و بالاترین بازدهی در رده کاری خود | تحویل اکسپرس با پست پیشتاز";

                // Create Product
                $productSku = strtoupper($brandKey).'-'.$skuCounter.'-'.strtoupper(Str::random(4));
                $product = Product::create([
                    'category_id' => $cat->id,
                    'brand_id' => $brand->id,
                    'name' => $productName,
                    'slug' => $slug,
                    'sku' => $productSku,
                    'barcode' => '626'.rand(100000000, 999999999),
                    'price' => $rawPrice,
                    'sale_price' => $salePrice,
                    'cost_price' => $costPrice,
                    'stock' => $stock,
                    'status' => 'published',
                    'is_featured' => $isFeatured,
                    'is_best_seller' => $isBestSeller,
                    'is_new_arrival' => $isNewArrival,
                    'weight' => rand(150, 2500),
                    'dimensions' => $specs,
                    'short_description' => $shortDesc,
                    'description' => $description,
                    'seo_title' => "خرید و قیمت {$productName} | دیجی‌استور",
                    'seo_description' => "بررسی مشخصات فنی، آخرین قیمت و خرید آنلاین {$productName} با گارانتی رسمی، ارسال فوری و تضمین اصالت کالا در دیجی‌استور.",
                ]);

                // Create Product Images (Multi-Angles: front, back, side, lifestyle, angle)
                $angles = ['front', 'back', 'side', 'lifestyle', 'angle'];
                foreach ($angles as $sortIdx => $angleKey) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imgSet[$angleKey],
                        'is_primary' => ($sortIdx === 0),
                        'sort_order' => $sortIdx,
                        'alt_text' => $product->name.' - نمای '.($sortIdx === 0 ? 'روبرو' : ($sortIdx === 1 ? 'پشت' : ($sortIdx === 2 ? 'کناری' : 'کاربردی'))),
                    ]);
                }

                // Create Master Inventory
                Inventory::create([
                    'product_id' => $product->id,
                    'product_variant_id' => null,
                    'stock' => $stock,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 3,
                ]);

                // Create Variants for 50% of items (Colors & Storage)
                if ($i % 2 === 0) {
                    $chosenColors = array_slice($colors, 0, rand(2, 4));
                    foreach ($chosenColors as $vIdx => $c) {
                        $variantSku = $product->sku.'-V'.($vIdx + 1);
                        $variantPrice = $product->effective_price + ($vIdx * 500000);
                        $variantSale = $product->sale_price ? $product->sale_price + ($vIdx * 450000) : null;
                        $variantStock = rand(2, 15);

                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $variantSku,
                            'price' => $variantPrice,
                            'sale_price' => $variantSale,
                            'stock' => $variantStock,
                            'image' => $imgSet['angle'],
                            'attributes_json' => [
                                'color' => $c['name'],
                                'color_code' => $c['hex'],
                                'storage' => $storage,
                            ],
                            'is_active' => true,
                        ]);

                        Inventory::create([
                            'product_id' => $product->id,
                            'product_variant_id' => $variant->id,
                            'stock' => $variantStock,
                            'reserved_stock' => 0,
                            'low_stock_threshold' => 2,
                        ]);
                    }
                }

                // Create Reviews for ~70% of products
                if ($i % 3 !== 0) {
                    $reviewCount = rand(1, 4);
                    for ($r = 0; $r < $reviewCount; $r++) {
                        $reviewer = $users->random();
                        Review::create([
                            'product_id' => $product->id,
                            'user_id' => $reviewer->id,
                            'rating' => rand(4, 5),
                            'title' => $reviewTitles[array_rand($reviewTitles)],
                            'body' => $reviewBodies[array_rand($reviewBodies)],
                            'is_verified_purchase' => true,
                            'status' => 'approved',
                            'created_at' => now()->subDays(rand(1, 90)),
                        ]);
                    }
                }

                $totalCreated++;
            }

            DB::commit();
        }

        echo "Successfully seeded {$totalCreated} rich e-commerce products with images, variants, specs, and reviews!\n";
    }
}
