<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AmazonScaleCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. New Premier Brands for Amazon Scale
        $newBrands = [
            'bosch' => ['name' => 'بوش (Bosch)', 'website' => 'https://bosch-home.com'],
            'philips' => ['name' => 'فیلیپس (Philips)', 'website' => 'https://philips.com'],
            'tefal' => ['name' => 'تفال (Tefal)', 'website' => 'https://tefal.com'],
            'delonghi' => ['name' => 'دلونگی (DeLonghi)', 'website' => 'https://delonghi.com'],
            'dyson' => ['name' => 'دایسون (Dyson)', 'website' => 'https://dyson.com'],
            'nespresso' => ['name' => 'نسپرسو (Nespresso)', 'website' => 'https://nespresso.com'],
            'nike' => ['name' => 'نایک (Nike)', 'website' => 'https://nike.com'],
            'adidas' => ['name' => 'آدیداس (Adidas)', 'website' => 'https://adidas.com'],
            'zara' => ['name' => 'زارا (Zara)', 'website' => 'https://zara.com'],
            'casio' => ['name' => 'کاسیو (Casio)', 'website' => 'https://casio.com'],
            'ray-ban' => ['name' => 'ری‌بن (Ray-Ban)', 'website' => 'https://ray-ban.com'],
            'loreal' => ['name' => 'لورآل (L\'Oréal)', 'website' => 'https://loreal.com'],
            'oral-b' => ['name' => 'اورال-بی (Oral-B)', 'website' => 'https://oralb.com'],
            'braun' => ['name' => 'براون (Braun)', 'website' => 'https://braun.com'],
            'ronix' => ['name' => 'رونیکس (Ronix)', 'website' => 'https://ronixtools.com'],
            'makita' => ['name' => 'ماکیتا (Makita)', 'website' => 'https://makita.com'],
            'lego' => ['name' => 'لگو (LEGO)', 'website' => 'https://lego.com'],
            'faber-castell' => ['name' => 'فابر کاستل (Faber-Castell)', 'website' => 'https://faber-castell.com'],
            'lavazza' => ['name' => 'لاواتزا (Lavazza)', 'website' => 'https://lavazza.com'],
            'lindt' => ['name' => 'لینت (Lindt)', 'website' => 'https://lindt.com'],
        ];

        foreach ($newBrands as $slug => $data) {
            Brand::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'website' => $data['website'],
                'is_active' => true,
            ]);
        }

        // 2. Comprehensive 12 Root Departments (Amazon Scale)
        $departments = [
            'digital-goods' => [
                'name' => 'کالای دیجیتال و گجت‌ها',
                'icon' => '📱',
                'description' => 'انواع گوشی‌های هوشمند، تبلت، ساعت هوشمند، هدفون و لوازم جانبی دیجیتال',
                'sort_order' => 1,
            ],
            'computer-parts' => [
                'name' => 'کامپیوتر، لپ‌تاپ و ماشین‌های اداری',
                'icon' => '💻',
                'description' => 'لپ‌تاپ، سیستم‌های رومیزی، قطعات سخت‌افزار، مانیتور و تجهیزات شبکه',
                'sort_order' => 2,
            ],
            'gaming' => [
                'name' => 'بازی، کنسول و گیمینگ',
                'icon' => '🎮',
                'description' => 'کنسول‌های بازی پلی‌استیشن و ایکس‌باکس، بازی‌ها و تجهیزات گیمینگ حرفه‌ای',
                'sort_order' => 3,
            ],
            'home-appliances' => [
                'name' => 'لوازم خانگی و آشپزخانه',
                'icon' => '🏠',
                'description' => 'لوازم برقی خانگی، پخت و پز، قهوه‌ساز، جاروبرقی، یخچال و شستشو',
                'sort_order' => 4,
            ],
            'fashion-clothing' => [
                'name' => 'مد، پوشاک و استایل',
                'icon' => '👗',
                'description' => 'پوشاک مردانه، زنانه و بچگانه، کیف، کفش، ساعت مچی و اکسسوری مد',
                'sort_order' => 5,
            ],
            'beauty-health' => [
                'name' => 'زیبایی، سلامت و مراقبت شخصی',
                'icon' => '💄',
                'description' => 'محصولات آرایشی، مراقبت پوست و مو، عطر و ادکلن، تجهیزات اصلاح و سلامت',
                'sort_order' => 6,
            ],
            'smart-home' => [
                'name' => 'خانه هوشمند، روشنایی و دکوراسیون',
                'icon' => '💡',
                'description' => 'گجت‌های هوشمند خانگی، روشنایی مدرن، امنیت و دکوراسیون منزل',
                'sort_order' => 7,
            ],
            'sports-outdoors' => [
                'name' => 'ورزش، تناسب اندام و کمپینگ',
                'icon' => '⛺',
                'description' => 'لوازم ورزشی و بدنسازی، دوچرخه، اسکوتر برقی، تجهیزات سفر و کوهنوردی',
                'sort_order' => 8,
            ],
            'tools-automotive' => [
                'name' => 'ابزارآلات، تجهیزات صنعتی و خودرو',
                'icon' => '🔧',
                'description' => 'ابزار برقی و دستی، تجهیزات فنی و لوازم جانبی مصرفی خودرو',
                'sort_order' => 9,
            ],
            'toys-baby' => [
                'name' => 'کودک، اسباب‌بازی و نوزاد',
                'icon' => '🧸',
                'description' => 'انواع اسباب‌بازی هوشمند، لگو، پازل، وسایل خواب، بهداشت و گردش کودک',
                'sort_order' => 10,
            ],
            'books-stationery' => [
                'name' => 'کتاب، نوشت‌افزار و هنر',
                'icon' => '📚',
                'description' => 'کتاب‌های ادبیات، علمی و توسعه فردی، نوشت‌افزار لوکس و ادوات هنری',
                'sort_order' => 11,
            ],
            'supermarket-grocery' => [
                'name' => 'سوپرمارکت، قهوه و خوراکی‌های ممتاز',
                'icon' => '🛒',
                'description' => 'قهوه‌های تخصصی، چای اعلا، شکلات‌های خارجی و خشکبار دست‌چین',
                'sort_order' => 12,
            ],
        ];

        $rootModels = [];
        foreach ($departments as $slug => $data) {
            $rootModels[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'icon' => $data['icon'],
                    'description' => $data['description'],
                    'sort_order' => $data['sort_order'],
                    'parent_id' => null,
                    'is_active' => true,
                ]
            );
        }

        // 3. 80+ Deep Subcategories (Full Amazon Hierarchy)
        $subcategories = [
            // Digital Goods
            'mobile-phones' => ['parent' => 'digital-goods', 'name' => 'گوشی موبایل', 'icon' => '📱', 'order' => 1],
            'tablets' => ['parent' => 'digital-goods', 'name' => 'تبلت و کتابخوان دیجیتال', 'icon' => '📟', 'order' => 2],
            'smart-watches' => ['parent' => 'digital-goods', 'name' => 'ساعت و مچ‌بند هوشمند', 'icon' => '⌚', 'order' => 3],
            'headphones' => ['parent' => 'digital-goods', 'name' => 'هدفون، هدست و هندزفری', 'icon' => '🎧', 'order' => 4],
            'powerbanks' => ['parent' => 'digital-goods', 'name' => 'پاوربانک و باتری همراه', 'icon' => '🔋', 'order' => 5],
            'chargers-cables' => ['parent' => 'digital-goods', 'name' => 'شارژر دیواری، کابل و مبدل', 'icon' => '🔌', 'order' => 6],
            'gadgets-cameras' => ['parent' => 'digital-goods', 'name' => 'دوربین عکاسی و هلی‌شات', 'icon' => '📷', 'order' => 7],
            'mobile-accessories' => ['parent' => 'digital-goods', 'name' => 'لوازم جانبی موبایل و تبلت', 'icon' => '🛡️', 'order' => 8],

            // Computers & Office
            'laptops' => ['parent' => 'computer-parts', 'name' => 'لپ‌تاپ و اولترابوک', 'icon' => '💻', 'order' => 1],
            'desktop-pcs' => ['parent' => 'computer-parts', 'name' => 'کامپیوترهای رومیزی و All-in-One', 'icon' => '🖥️', 'order' => 2],
            'monitors' => ['parent' => 'computer-parts', 'name' => 'مانیتور و نمایشگر حرفه‌ای', 'icon' => '🖥️', 'order' => 3],
            'pc-components' => ['parent' => 'computer-parts', 'name' => 'قطعات اصلی سخت‌افزار (CPU, GPU, RAM)', 'icon' => '⚙️', 'order' => 4],
            'storage-flash' => ['parent' => 'computer-parts', 'name' => 'حافظه SSD، هارد اکسترنال و فلش', 'icon' => '💾', 'order' => 5],
            'keyboards-mice' => ['parent' => 'computer-parts', 'name' => 'ماوس، کیبورد مکانیکی و پد', 'icon' => '⌨️', 'order' => 6],
            'network-modems' => ['parent' => 'computer-parts', 'name' => 'تجهیزات شبکه، مودم و روتر Wi-Fi', 'icon' => '🌐', 'order' => 7],
            'printers-scanners' => ['parent' => 'computer-parts', 'name' => 'پرینتر، اسکنر و لوازم اداری', 'icon' => '🖨️', 'order' => 8],

            // Gaming & Consoles
            'consoles' => ['parent' => 'gaming', 'name' => 'کنسول‌های بازی (PlayStation, Xbox, Switch)', 'icon' => '🎮', 'order' => 1],
            'video-games' => ['parent' => 'gaming', 'name' => 'بازی‌های کنسول و کامپیوتر', 'icon' => '💿', 'order' => 2],
            'gamepads-wheels' => ['parent' => 'gaming', 'name' => 'دسته بازی، فرمان و جوی‌استیک', 'icon' => '🕹️', 'order' => 3],
            'gaming-headsets' => ['parent' => 'gaming', 'name' => 'هدست و میکروفون گیمینگ و استریم', 'icon' => '🎙️', 'order' => 4],
            'gaming-furniture' => ['parent' => 'gaming', 'name' => 'صندلی و میز گیمینگ ارگونومیک', 'icon' => '🪑', 'order' => 5],
            'vr-headsets' => ['parent' => 'gaming', 'name' => 'عینک و هدست واقعیت مجازی (VR)', 'icon' => '🥽', 'order' => 6],

            // Home & Kitchen Appliances
            'coffee-espresso-makers' => ['parent' => 'home-appliances', 'name' => 'اسپرسوساز، قهوه‌ساز و چای‌ساز', 'icon' => '☕', 'order' => 1],
            'air-fryers-ovens' => ['parent' => 'home-appliances', 'name' => 'سرخ‌کن بدون روغن، فر و مایکروویو', 'icon' => '🍳', 'order' => 2],
            'food-processors' => ['parent' => 'home-appliances', 'name' => 'غذاساز، آسیاب و مخلوط‌کن', 'icon' => '🥣', 'order' => 3],
            'vacuums-cleaners' => ['parent' => 'home-appliances', 'name' => 'جاروبرقی، جاروشارژی و ربات نظافت', 'icon' => '🧹', 'order' => 4],
            'refrigerators' => ['parent' => 'home-appliances', 'name' => 'یخچال، فریزر و ساید بای ساید', 'icon' => '❄️', 'order' => 5],
            'washing-dishwashers' => ['parent' => 'home-appliances', 'name' => 'ماشین لباسشویی و ظرفشویی', 'icon' => '🧺', 'order' => 6],
            'cooling-air-purifiers' => ['parent' => 'home-appliances', 'name' => 'کولر گازی و دستگاه تصفیه هوا', 'icon' => '🌬️', 'order' => 7],
            'irons-steamers' => ['parent' => 'home-appliances', 'name' => 'اتو بخار دستی و اتو مخزن‌دار', 'icon' => '👔', 'order' => 8],

            // Fashion & Clothing
            'mens-clothing' => ['parent' => 'fashion-clothing', 'name' => 'پوشاک مردانه (پیراهن، شلوار، کاپشن)', 'icon' => '👕', 'order' => 1],
            'womens-clothing' => ['parent' => 'fashion-clothing', 'name' => 'پوشاک زنانه (مانتو، پالتو، شومیز)', 'icon' => '👗', 'order' => 2],
            'shoes-sneakers' => ['parent' => 'fashion-clothing', 'name' => 'کتانی اورجینال، کفش رسمی و صندل', 'icon' => '👟', 'order' => 3],
            'bags-luggage' => ['parent' => 'fashion-clothing', 'name' => 'کوله پشتی، کیف دستی و چمدان', 'icon' => '🎒', 'order' => 4],
            'watches-jewelry' => ['parent' => 'fashion-clothing', 'name' => 'ساعت مچی عقربه‌ای و اکسسوری لوکس', 'icon' => '⌚', 'order' => 5],
            'sunglasses' => ['parent' => 'fashion-clothing', 'name' => 'عینک آفتابی استاندارد UV400', 'icon' => '🕶️', 'order' => 6],

            // Beauty & Personal Care
            'skincare-creams' => ['parent' => 'beauty-health', 'name' => 'مراقبت از پوست، سرم و ضدآفتاب', 'icon' => '🧴', 'order' => 1],
            'perfumes-colognes' => ['parent' => 'beauty-health', 'name' => 'عطر، ادکلن اورجینال و بادی‌اسپلش', 'icon' => '✨', 'order' => 2],
            'shavers-grooming' => ['parent' => 'beauty-health', 'name' => 'ماشین اصلاح سر و صورت، ریش‌تراش', 'icon' => '🪒', 'order' => 3],
            'hair-dryers' => ['parent' => 'beauty-health', 'name' => 'سشوار حرفه‌ای، اتو و حالت‌دهنده مو', 'icon' => '💨', 'order' => 4],
            'oral-dental' => ['parent' => 'beauty-health', 'name' => 'مسواک برقی و بهداشت دهان و دندان', 'icon' => '🪥', 'order' => 5],
            'health-monitors' => ['parent' => 'beauty-health', 'name' => 'فشارسنج، تب‌سنج و ماساژور خانگی', 'icon' => '🩺', 'order' => 6],

            // Smart Home & Lighting
            'smart-lighting' => ['parent' => 'smart-home', 'name' => 'روشنایی هوشمند، لامپ و ریسه RGB', 'icon' => '💡', 'order' => 1],
            'security-cameras' => ['parent' => 'smart-home', 'name' => 'دوربین مداربسته و امنیت هوشمند', 'icon' => '📹', 'order' => 2],
            'smart-plugs' => ['parent' => 'smart-home', 'name' => 'پریز و کلید لمسی هوشمند', 'icon' => '🔌', 'order' => 3],
            'dinnerware' => ['parent' => 'smart-home', 'name' => 'ظروف پذیرایی و سرو غذا', 'icon' => '🍽️', 'order' => 4],
            'bedding-linens' => ['parent' => 'smart-home', 'name' => 'سرویس خواب، بالش و روتختی', 'icon' => '🛏️', 'order' => 5],
            'wall-art' => ['parent' => 'smart-home', 'name' => 'ساعت دیواری مدرن و تابلوهای دکوراتیو', 'icon' => '🖼️', 'order' => 6],

            // Sports & Outdoors
            'fitness-equipment' => ['parent' => 'sports-outdoors', 'name' => 'لوازم بدنسازی، دمبل و کش فیتنس', 'icon' => '🏋️', 'order' => 1],
            'bicycles-scooters' => ['parent' => 'sports-outdoors', 'name' => 'دوچرخه، اسکوتر برقی و کلاه ایمنی', 'icon' => '🚲', 'order' => 2],
            'camping-gear' => ['parent' => 'sports-outdoors', 'name' => 'چادر مسافرتی، کیسه خواب و فلاسک', 'icon' => '🏕️', 'order' => 3],
            'sportswear' => ['parent' => 'sports-outdoors', 'name' => 'پوشاک ورزشی تخصصی و لگینگ', 'icon' => '🏃', 'order' => 4],
            'water-sports' => ['parent' => 'sports-outdoors', 'name' => 'عینک و وسایل شنا و غواصی', 'icon' => '🏊', 'order' => 5],

            // Tools & Automotive
            'power-tools' => ['parent' => 'tools-automotive', 'name' => 'ابزار برقی، دریل و فرز شارژی', 'icon' => '🪚', 'order' => 1],
            'hand-tools' => ['parent' => 'tools-automotive', 'name' => 'ابزار دستی، آچار و جعبه ابزار کامل', 'icon' => '🔨', 'order' => 2],
            'car-accessories' => ['parent' => 'tools-automotive', 'name' => 'لوازم جانبی و تجهیزات داخل خودرو', 'icon' => '🚗', 'order' => 3],
            'car-electronics' => ['parent' => 'tools-automotive', 'name' => 'سیستم صوتی، مانیتور و ردیاب خودرو', 'icon' => '📻', 'order' => 4],
            'car-care' => ['parent' => 'tools-automotive', 'name' => 'واکس، پولیش و محصولات نانو خودرو', 'icon' => '🧽', 'order' => 5],

            // Toys & Kids
            'board-games-puzzles' => ['parent' => 'toys-baby', 'name' => 'بازی فکری، پازل و بردگیم', 'icon' => '🧩', 'order' => 1],
            'rc-drones-cars' => ['parent' => 'toys-baby', 'name' => 'ماشین کنترلی، پهپاد و ربات هوشمند', 'icon' => '🏎️', 'order' => 2],
            'lego-building' => ['parent' => 'toys-baby', 'name' => 'بلوک ساختنی و ست‌های اصل لگو', 'icon' => '🧱', 'order' => 3],
            'dolls-figures' => ['parent' => 'toys-baby', 'name' => 'اکشن‌فیگور شخصیت‌ها و عروسک', 'icon' => '🪆', 'order' => 4],
            'baby-strollers' => ['parent' => 'toys-baby', 'name' => 'کالسکه، صندلی ماشین و وسایل نوزاد', 'icon' => '🚼', 'order' => 5],

            // Books & Stationery
            'novels-literature' => ['parent' => 'books-stationery', 'name' => 'رمان، ادبیات جهان و داستان', 'icon' => '📖', 'order' => 1],
            'business-psychology' => ['parent' => 'books-stationery', 'name' => 'کتاب‌های موفقیت، مدیریت و روانشناسی', 'icon' => '📕', 'order' => 2],
            'fine-pens-stationery' => ['parent' => 'books-stationery', 'name' => 'روان‌نویس لوکس، خودنویس و سررسید', 'icon' => '✒️', 'order' => 3],
            'art-supplies' => ['parent' => 'books-stationery', 'name' => 'ابزار نقاشی، رنگ روغن و ماژیک حرفه‌ای', 'icon' => '🎨', 'order' => 4],
            'musical-instruments' => ['parent' => 'books-stationery', 'name' => 'گیتار، کالیمبا و سازهای موسیقی', 'icon' => '🎸', 'order' => 5],

            // Supermarket & Gourmet
            'specialty-coffee' => ['parent' => 'supermarket-grocery', 'name' => 'دانه قهوه تخصصی و کپسول نسپرسو', 'icon' => '☕', 'order' => 1],
            'artisan-tea' => ['parent' => 'supermarket-grocery', 'name' => 'چای سرگل لاهیجان و دمنوش طبیعی', 'icon' => '🍵', 'order' => 2],
            'luxury-chocolates' => ['parent' => 'supermarket-grocery', 'name' => 'شکلات تخته‌ای و پذیرایی خارجی', 'icon' => '🍫', 'order' => 3],
            'premium-nuts' => ['parent' => 'supermarket-grocery', 'name' => 'پسته، فندق و خشکبار ارگانیک', 'icon' => '🥜', 'order' => 4],
            'olive-oils' => ['parent' => 'supermarket-grocery', 'name' => 'روغن زیتون فرابکر و چاشنی خاص', 'icon' => '🫒', 'order' => 5],
        ];

        $subModels = [];
        foreach ($subcategories as $slug => $data) {
            $parentId = $rootModels[$data['parent']]->id ?? null;
            $subModels[$slug] = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'icon' => $data['icon'],
                    'parent_id' => $parentId,
                    'sort_order' => $data['order'],
                    'is_active' => true,
                ]
            );
        }

        // 4. Populate realistic catalog items for new categories that currently have 0 products
        $catalogTemplates = [
            'coffee-espresso-makers' => [
                'brand' => 'delonghi',
                'items' => [
                    ['name' => 'اسپرسوساز دلونگی مدل Dedica EC685', 'price' => 11800000, 'sale' => 10900000, 'img' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'دستگاه قهوه‌ساز نسپرسو مدل Vertuo Pop', 'price' => 9500000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'اسپرسوسaz اتوماتیک فیلیپس مدل EP2220', 'price' => 28500000, 'sale' => 26900000, 'img' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'air-fryers-ovens' => [
                'brand' => 'philips',
                'items' => [
                    ['name' => 'سرخ‌کن بدون روغن فیلیپس مدل HD9270 ظرفیت ۶.۲ لیتر', 'price' => 7900000, 'sale' => 7350000, 'img' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'هواپز چندکاره تفال مدل Easy Fry Dual Zone', 'price' => 12400000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'vacuums-cleaners' => [
                'brand' => 'dyson',
                'items' => [
                    ['name' => 'جارو شارژی دایسون مدل V15 Detect Absolute', 'price' => 54000000, 'sale' => 49900000, 'img' => 'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'جاروبرقی بوش مدل BGL8PRO5 توان ۱۸۰۰ وات', 'price' => 19500000, 'sale' => 18200000, 'img' => 'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'shoes-sneakers' => [
                'brand' => 'nike',
                'items' => [
                    ['name' => 'کفش رانینگ نایک Air Zoom Pegasus 40 اورجینال', 'price' => 8900000, 'sale' => 7990000, 'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'کتانی آدیداس مدل Ultraboost Light سایز ۴۲ تا ۴۵', 'price' => 9800000, 'sale' => 8900000, 'img' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'watches-jewelry' => [
                'brand' => 'casio',
                'items' => [
                    ['name' => 'ساعت مچی مردانه کاسیو جی‌شاک مدل GA-2100 ضد ضربه', 'price' => 6400000, 'sale' => 5850000, 'img' => 'https://images.unsplash.com/photo-1524805444758-089113d48a6d?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'ساعت کلاسیک کاسیو نوستالژی طلایی مدل A168WG', 'price' => 3200000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'skincare-creams' => [
                'brand' => 'loreal',
                'items' => [
                    ['name' => 'سرم ضد چروک لورآل حاوی هیالورونیک اسید خالص Revitalift', 'price' => 1450000, 'sale' => 1290000, 'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'کرم آبرسان عمیق پوست صورت نوتروژینا مدل Hydro Boost', 'price' => 890000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'shavers-grooming' => [
                'brand' => 'braun',
                'items' => [
                    ['name' => 'ماشین اصلاح صورت براون سری ۹ مدل 9465cc ساخت آلمان', 'price' => 24500000, 'sale' => 22900000, 'img' => 'https://images.unsplash.com/photo-1621607512214-68297480165e?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'ریش‌تراش فیلیپس سه‌تیغ هوشمند مدل S7788 با سنسور هوش مصنوعی', 'price' => 16800000, 'sale' => 15400000, 'img' => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'fitness-equipment' => [
                'brand' => 'xiaomi',
                'items' => [
                    ['name' => 'تردمیل تاشو هوشمند شیائومی WalkingPad R2 Pro', 'price' => 38000000, 'sale' => 34900000, 'img' => 'https://images.unsplash.com/photo-1576678927484-cc907957088c?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'مجموعه دمبل متغیر بدنسازی ۲۰ کیلوگرمی با جعبه قابل حمل', 'price' => 4500000, 'sale' => 3900000, 'img' => 'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'power-tools' => [
                'brand' => 'ronix',
                'items' => [
                    ['name' => 'دریل پیچ‌گوشتی چکشی شارژی ۲۰ ولت رونیکس مدل 8900K', 'price' => 5900000, 'sale' => 5300000, 'img' => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'مینی فرز دیمردار ۱۲۰۰ وات ماکیتا اصل ژاپن مدل 9565CVR', 'price' => 14200000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'lego-building' => [
                'brand' => 'lego',
                'items' => [
                    ['name' => 'لگو تکنیک مدل پورشه 911 GT3 RS کد 42056', 'price' => 32000000, 'sale' => 29500000, 'img' => 'https://images.unsplash.com/photo-1585366119957-e9730b6d0f60?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'ست لگو سری استار وارز مدل Millennium Falcon', 'price' => 48000000, 'sale' => null, 'img' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'specialty-coffee' => [
                'brand' => 'lavazza',
                'items' => [
                    ['name' => 'دانه قهوه ۱۰۰٪ عربیکا لاواتزا مدل Qualita Oro وزن ۱ کیلوگرم', 'price' => 1950000, 'sale' => 1790000, 'img' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'کپسول قهوه نسپرسو اصل سوئیس پک ۱۰ عددی مدل Ispirazione Napoli', 'price' => 590000, 'sale' => 540000, 'img' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
            'fine-pens-stationery' => [
                'brand' => 'faber-castell',
                'items' => [
                    ['name' => 'ست روان‌نویس و خودنویس فابر کاستل مدل Ambition بدنه چوب گلابی', 'price' => 6800000, 'sale' => 6200000, 'img' => 'https://images.unsplash.com/photo-1585336261026-41ff36de06d5?w=800&auto=format&fit=crop&q=80'],
                    ['name' => 'مدادرنگی ۱۲۰ رنگ پلی‌کروموس فابر کاستل جعبه فلزی اصل آلمان', 'price' => 18900000, 'sale' => 17500000, 'img' => 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=800&auto=format&fit=crop&q=80'],
                ],
            ],
        ];

        foreach ($catalogTemplates as $catSlug => $template) {
            $cat = $subModels[$catSlug] ?? null;
            if (! $cat) {
                continue;
            }

            $brandModel = Brand::where('slug', $template['brand'])->first() ?? Brand::first();

            foreach ($template['items'] as $item) {
                $productSlug = Str::slug($item['name'], '-', 'en');
                if (empty($productSlug) || Product::where('slug', $productSlug)->exists()) {
                    $productSlug = 'prod-'.Str::random(8);
                }

                $product = Product::firstOrCreate(
                    ['name' => $item['name']],
                    [
                        'category_id' => $cat->id,
                        'brand_id' => $brandModel?->id,
                        'slug' => $productSlug,
                        'sku' => strtoupper(Str::random(3)).'-'.rand(1000, 9999),
                        'price' => $item['price'],
                        'sale_price' => $item['sale'],
                        'stock' => rand(15, 60),
                        'status' => 'published',
                        'is_featured' => rand(0, 1) === 1,
                        'is_best_seller' => rand(0, 1) === 1,
                        'description' => $item['name'].' با بالاترین کیفیت ساخت، گارانتی شرکتی و اصالت فیزیکی کالا ارائه می‌شود.',
                        'short_description' => 'گارانتی ۱۸ ماهه معتبر | ارسال سریع اکسپرس | تضمین بهترین قیمت بازار',
                    ]
                );

                ProductImage::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'is_primary' => true,
                    ],
                    [
                        'image_path' => $item['img'],
                        'sort_order' => 1,
                        'alt_text' => $item['name'],
                    ]
                );

                Inventory::firstOrCreate(
                    ['product_id' => $product->id, 'product_variant_id' => null],
                    [
                        'stock' => $product->stock,
                        'reserved_stock' => 0,
                        'low_stock_threshold' => 5,
                    ]
                );
            }
        }
    }
}
