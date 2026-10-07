<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Inventory;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions
        $roles = [
            'super-admin' => ['name' => 'مدیر ارشد سامانه', 'description' => 'دسترسی نامحدود به تمامی بخش‌ها'],
            'admin' => ['name' => 'مدیر فروشگاه', 'description' => 'مدیریت محصولات، سفارش‌ها و تخفیف‌ها'],
            'staff' => ['name' => 'کارشناس انبار و فروش', 'description' => 'مدیریت پردازش سفارش‌ها و انبارداری'],
            'customer' => ['name' => 'خریدار و مشتری', 'description' => 'کاربر عادی فروشگاه با امکان ثبت سفارش'],
        ];

        $roleModels = [];
        foreach ($roles as $slug => $data) {
            $roleModels[$slug] = Role::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
        }

        $permissions = [
            'manage_products' => 'مدیریت محصولات و انبار',
            'manage_orders' => 'مدیریت سفارش‌ها و ارسال',
            'manage_coupons' => 'مدیریت کدهای تخفیف',
            'manage_users' => 'مدیریت کاربران و دسترسی‌ها',
            'manage_settings' => 'تنظیمات کلی فروشگاه',
            'view_audit_logs' => 'مشاهده گزارش لاگ‌های امنیتی',
        ];

        foreach ($permissions as $slug => $name) {
            $perm = Permission::firstOrCreate(['slug' => $slug], [
                'name' => $name,
            ]);
            $roleModels['super-admin']->permissions()->syncWithoutDetaching([$perm->id]);
            $roleModels['admin']->permissions()->syncWithoutDetaching([$perm->id]);
            if (in_array($slug, ['manage_products', 'manage_orders'])) {
                $roleModels['staff']->permissions()->syncWithoutDetaching([$perm->id]);
            }
        }

        // 2. Users & Profiles
        $admin = User::firstOrCreate(
            ['email' => 'admin@digistore.ir'],
            [
                'name' => 'مدیر ارشد سامانه',
                'phone' => '09121111111',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->sync([$roleModels['super-admin']->id]);
        UserProfile::firstOrCreate(['user_id' => $admin->id], [
            'national_code' => '0012345678',
            'bio' => 'مدیریت و پشتیبانی سامانه',
        ]);

        $staff = User::firstOrCreate(
            ['email' => 'staff@digistore.ir'],
            [
                'name' => 'کارشناس انبار و پردازش',
                'phone' => '09124444444',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $staff->roles()->sync([$roleModels['staff']->id]);

        $customer = User::firstOrCreate(
            ['email' => 'customer@digistore.ir'],
            [
                'name' => 'سهراب سپهری',
                'phone' => '09122222222',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $customer->roles()->sync([$roleModels['customer']->id]);
        UserProfile::firstOrCreate(['user_id' => $customer->id], [
            'national_code' => '0023456789',
        ]);

        Address::firstOrCreate(
            ['user_id' => $customer->id, 'title' => 'منزل تهران'],
            [
                'recipient_name' => 'سهراب سپهری',
                'recipient_phone' => '09122222222',
                'province' => 'تهران',
                'city' => 'تهران',
                'address_line' => 'خیابان ولیعصر، بالاتر از پارک ساعی، کوچه ساعی یکم، پلاک ۱۲، واحد ۴',
                'postal_code' => '1511812345',
                'is_default' => true,
            ]
        );

        Address::firstOrCreate(
            ['user_id' => $customer->id, 'title' => 'محل کار'],
            [
                'recipient_name' => 'سهراب سپهری',
                'recipient_phone' => '09122222222',
                'province' => 'تهران',
                'city' => 'تهران',
                'address_line' => 'میدان آزادی، بزرگراه جناح، خیابان شهید گلاب، پلاک ۴۰',
                'postal_code' => '1459876543',
                'is_default' => false,
            ]
        );

        $buyer = User::firstOrCreate(
            ['email' => 'buyer@digistore.ir'],
            [
                'name' => 'مریم میرزاخانی',
                'phone' => '09123333333',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $buyer->roles()->sync([$roleModels['customer']->id]);
        UserProfile::firstOrCreate(['user_id' => $buyer->id], [
            'national_code' => '0034567890',
        ]);

        Address::firstOrCreate(
            ['user_id' => $buyer->id, 'title' => 'منزل اصفهان'],
            [
                'recipient_name' => 'مریم میرزاخانی',
                'recipient_phone' => '09123333333',
                'province' => 'اصفهان',
                'city' => 'اصفهان',
                'address_line' => 'خیابان چهارباغ بالا، مجتمع کوثر، بلوک B، طبقه ۳',
                'postal_code' => '8173612345',
                'is_default' => true,
            ]
        );

        // 3. Categories (Parent & Children)
        $parentDigital = Category::firstOrCreate(['slug' => 'digital-goods'], [
            'name' => 'کالای دیجیتال',
            'icon' => '💻',
            'description' => 'انواع گوشی موبایل، لپ‌تاپ، تبلت و لوازم الکترونیکی هوشمند',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $parentAccessories = Category::firstOrCreate(['slug' => 'accessories'], [
            'name' => 'لوازم جانبی دیجیتال',
            'icon' => '🎧',
            'description' => 'هدفون، پاوربانک، شارژر، کابل و قاب محافظ اورجینال',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $parentAudio = Category::firstOrCreate(['slug' => 'audio-video'], [
            'name' => 'صوتی و تصویری',
            'icon' => '📺',
            'description' => 'تلویزیون‌های هوشمند، سینمای خانگی و اسپیکرهای حرفه‌ای',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catMobile = Category::firstOrCreate(['slug' => 'mobile-phones'], [
            'name' => 'گوشی موبایل',
            'parent_id' => $parentDigital->id,
            'icon' => '📱',
            'description' => 'جدیدترین گوشی‌های هوشمند پرچمدار و میان‌رده',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catLaptop = Category::firstOrCreate(['slug' => 'laptops'], [
            'name' => 'لپ‌تاپ و اولترابوک',
            'parent_id' => $parentDigital->id,
            'icon' => '💻',
            'description' => 'لپ‌تاپ‌های گیمینگ، مهندسی و اداری',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catSmartwatch = Category::firstOrCreate(['slug' => 'smart-watches'], [
            'name' => 'ساعت هوشمند',
            'parent_id' => $parentDigital->id,
            'icon' => '⌚',
            'description' => 'ساعت‌ها و مچ‌بندهای سلامتی هوشمند',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catHeadphones = Category::firstOrCreate(['slug' => 'headphones'], [
            'name' => 'هدفون و هندزفری',
            'parent_id' => $parentAccessories->id,
            'icon' => '🎧',
            'description' => 'هدفون‌های نویزکنسلینگ و هندزفری بلوتوثی',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catPowerbank = Category::firstOrCreate(['slug' => 'powerbanks'], [
            'name' => 'پاوربانک و شارژر',
            'parent_id' => $parentAccessories->id,
            'icon' => '🔋',
            'description' => 'پاوربانک‌های فست شارژ ظرفیت بالا',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catTV = Category::firstOrCreate(['slug' => 'tv'], [
            'name' => 'تلویزیون هوشمند',
            'parent_id' => $parentAudio->id,
            'icon' => '📺',
            'description' => 'تلویزیون‌های 4K و 8K با پنل‌های OLED و QLED',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. Brands
        $brandsData = [
            'samsung' => ['name' => 'سامسونگ (Samsung)', 'website' => 'https://samsung.com', 'description' => 'پیشرو در فناوری نمایشگرها و گوشی‌های هوشمند سری Galaxy'],
            'apple' => ['name' => 'اپل (Apple)', 'website' => 'https://apple.com', 'description' => 'طراح و سازنده آیفون، مک‌بوک و اکوسیستم اختصاصی iOS'],
            'xiaomi' => ['name' => 'شیائومی (Xiaomi)', 'website' => 'https://mi.com', 'description' => 'نوآوری با قیمت مناسب در تولید گجت‌ها و تجهیزات هوشمند خانگی'],
            'asus' => ['name' => 'ایسوس (ASUS)', 'website' => 'https://asus.com', 'description' => 'برترین برند لپ‌تاپ‌های گیمینگ ROG و مادربوردهای حرفه‌ای'],
            'sony' => ['name' => 'سونی (Sony)', 'website' => 'https://sony.com', 'description' => 'کیفیت برتر صدا و تصویر با هدفون‌های بی‌سیم و تلویزیون‌های Bravia'],
        ];

        $brandModels = [];
        foreach ($brandsData as $slug => $data) {
            $brandModels[$slug] = Brand::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'website' => $data['website'],
                'description' => $data['description'],
                'is_active' => true,
            ]);
        }

        // 5. Attributes (Color & Storage)
        $attrColor = ProductAttribute::firstOrCreate(['slug' => 'color'], ['name' => 'رنگ']);
        $valTitaniumBlack = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrColor->id, 'value' => 'مشکی تیتانیوم'], ['color_code' => '#2b2b2c']);
        $valTitaniumGray = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrColor->id, 'value' => 'خاکستری تیتانیوم'], ['color_code' => '#757575']);
        $valTitaniumNatural = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrColor->id, 'value' => 'تیتانیوم طبیعی'], ['color_code' => '#9e978e']);
        $valTitaniumBlue = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrColor->id, 'value' => 'آبی تیتانیوم'], ['color_code' => '#2d3b4e']);

        $attrStorage = ProductAttribute::firstOrCreate(['slug' => 'storage'], ['name' => 'حافظه داخلی']);
        $val256GB = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrStorage->id, 'value' => '256GB']);
        $val512GB = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrStorage->id, 'value' => '512GB']);
        $val1TB = ProductAttributeValue::firstOrCreate(['product_attribute_id' => $attrStorage->id, 'value' => '1TB']);

        // 6. Products with Real Specs, Variants, Images & Inventories
        $productsSeed = [
            [
                'name' => 'گوشی موبایل سامسونگ مدل Galaxy S24 Ultra دو سیم‌کارت',
                'slug' => 'samsung-galaxy-s24-ultra',
                'sku' => 'SAM-S24U-512',
                'category_id' => $catMobile->id,
                'brand_id' => $brandModels['samsung']->id,
                'price' => 74500000,
                'sale_price' => 69900000,
                'cost_price' => 65000000,
                'stock' => 25,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new_arrival' => true,
                'weight' => 232,
                'short_description' => 'پرچمدار ۲۰۲۴ سامسونگ مجهز به هوش مصنوعی Galaxy AI، دوربین ۲۰۰ مگاپیکسلی و پردازنده Snapdragon 8 Gen 3',
                'description' => 'گوشی Galaxy S24 Ultra اوج مهندسی سامسونگ با فریم تیتانیومی مستحکم و قلم هوشمند S Pen داخلی است. قابلیت‌های منحصر به فرد هوش مصنوعی شامل ترجمه زنده تماس، ویرایش جادویی تصاویر و سرچ تصویری با Google Circle to Search این محصول را به گزینه‌ای بی‌رقیب تبدیل کرده است.',
                'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&auto=format&fit=crop&q=80',
                'variants' => [
                    [
                        'sku' => 'SAM-S24U-256-BLK',
                        'price' => 69900000,
                        'sale_price' => 66500000,
                        'stock' => 15,
                        'attributes' => ['color' => 'مشکی تیتانیوم', 'storage' => '256GB'],
                    ],
                    [
                        'sku' => 'SAM-S24U-512-GRY',
                        'price' => 74500000,
                        'sale_price' => 71500000,
                        'stock' => 10,
                        'attributes' => ['color' => 'خاکستری تیتانیوم', 'storage' => '512GB'],
                    ],
                ],
            ],
            [
                'name' => 'گوشی موبایل اپل مدل iPhone 15 Pro Max نات اکتیو پارت‌نامبر ZAA',
                'slug' => 'apple-iphone-15-pro-max',
                'sku' => 'APL-IP15PM-256',
                'category_id' => $catMobile->id,
                'brand_id' => $brandModels['apple']->id,
                'price' => 88000000,
                'sale_price' => 84900000,
                'cost_price' => 80000000,
                'stock' => 18,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new_arrival' => true,
                'weight' => 221,
                'short_description' => 'طراحی نوین تیتانیومی گرید هوافضا با پورت Type-C، تراشه A17 Pro و دوربین زوم اپتیکال ۵ برابری',
                'description' => 'آیفون ۱۵ پرو مکس سبک‌ترین و قدرتمندترین مدل سری پرو تا به امروز است. پردازنده ۳ نانومتری A17 Pro قابلیت اجرای بازی‌های کنسولی را روی موبایل ممکن ساخته و کلید اکشن جدید امکان دسترسی سریع به عملکردهای دلخواه شما را فراهم می‌سازد.',
                'image' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                'variants' => [
                    [
                        'sku' => 'APL-IP15PM-256-NAT',
                        'price' => 84900000,
                        'sale_price' => null,
                        'stock' => 8,
                        'attributes' => ['color' => 'تیتانیوم طبیعی', 'storage' => '256GB'],
                    ],
                    [
                        'sku' => 'APL-IP15PM-512-BLU',
                        'price' => 93000000,
                        'sale_price' => 89500000,
                        'stock' => 10,
                        'attributes' => ['color' => 'آبی تیتانیوم', 'storage' => '512GB'],
                    ],
                ],
            ],
            [
                'name' => 'لپ‌تاپ گیمینگ ایسوس مدل ROG Strix G16 G614JVR',
                'slug' => 'asus-rog-strix-g16-gaming-laptop',
                'sku' => 'ASUS-G16-14900HX',
                'category_id' => $catLaptop->id,
                'brand_id' => $brandModels['asus']->id,
                'price' => 112000000,
                'sale_price' => 106500000,
                'cost_price' => 99000000,
                'stock' => 8,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new_arrival' => true,
                'weight' => 2500,
                'short_description' => 'پردازنده Core i9-14900HX با گرافیک RTX 4080 شانزده گیگابایت، ۳۲ گیگابایت رم DDR5 و صفحه ۲۴۰ هرتز ROG Nebula',
                'description' => 'غول بی‌رقیب پردازش گرافیکی و گیمینگ ایسوس با سیستم خنک‌کننده سه فنه و فلز مایع Thermal Grizzly، آماده اجرای روان‌ترین خروجی در رزولوشن 2K برای حرفه‌ای‌ترین بازی‌سازان و طراحان ۳D.',
                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
            [
                'name' => 'هدفون بی‌سیم نویزکنسلینگ سونی مدل WH-1000XM5',
                'slug' => 'sony-wh-1000xm5-wireless-headphones',
                'sku' => 'SNY-WH1000XM5-BLK',
                'category_id' => $catHeadphones->id,
                'brand_id' => $brandModels['sony']->id,
                'price' => 19800000,
                'sale_price' => 17900000,
                'cost_price' => 16000000,
                'stock' => 30,
                'is_featured' => true,
                'is_best_seller' => true,
                'is_new_arrival' => false,
                'weight' => 250,
                'short_description' => 'استاندارد طلایی حذف نویز فعال صنعتی با پردازشگر اختصاصی V1 سونی و شارژدهی خارق‌العاده ۳۰ ساعته',
                'description' => 'سونی WH-1000XM5 سکوت محض را به همراه باکیفیت‌ترین کدک صوتی LDAC برای عاشقان موسیقی به ارمغان می‌آورد. طراحی ارگونومیک جدید و وزن سبک باعث می‌شود حتی در طول پروازهای طولانی احساس خستگی نکنید.',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
            [
                'name' => 'ساعت هوشمند اپل واچ سری ۹ مدل Aluminum 45mm',
                'slug' => 'apple-watch-series-9-aluminum-45mm',
                'sku' => 'APL-W9-45-MID',
                'category_id' => $catSmartwatch->id,
                'brand_id' => $brandModels['apple']->id,
                'price' => 24500000,
                'sale_price' => 22800000,
                'cost_price' => 20500000,
                'stock' => 14,
                'is_featured' => false,
                'is_best_seller' => true,
                'is_new_arrival' => true,
                'weight' => 39,
                'short_description' => 'مجهز به پردازنده جدید S9 SiP، ژست لمسی شگفت‌انگیز دابل تپ (Double Tap) و نمایشگر روشن ۲۰۰۰ نیتی',
                'description' => 'با ساعت هوشمند Apple Watch Series 9 پایش دقیق نوار قلب ECG، میزان اکسیژن خون و کیفیت خواب را همیشه بر روی مچ دست خود داشته باشید. بدنه آلومینیومی سازگار با محیط زیست و بند اسپرت نرم.',
                'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
            [
                'name' => 'پاوربانک ۲۰۰۰۰ میلی‌آمپر شیائومی مدل Redmi 18W Fast Charge',
                'slug' => 'xiaomi-redmi-20000mah-powerbank',
                'sku' => 'XMI-PB-20000-WHT',
                'category_id' => $catPowerbank->id,
                'brand_id' => $brandModels['xiaomi']->id,
                'price' => 1950000,
                'sale_price' => 1690000,
                'cost_price' => 1400000,
                'stock' => 45,
                'is_featured' => false,
                'is_best_seller' => true,
                'is_new_arrival' => false,
                'weight' => 440,
                'short_description' => 'ظرفیت اسمی واقعی ۲۰۰۰۰ میلی‌آمپر با دو درگاه خروجی USB و فناوری شارژ سریع دوطرفه ۱۸ وات',
                'description' => 'همراه ضروری سفرهای شما با قابلیت شارژ همزمان دو دستگاه، تراشه ایمنی ۹ لایه محافظ در برابر ولتاژ و حرارت بالا و سازگاری کامل با تمامی گوشی‌های هوشمند و هدفون‌ها.',
                'image' => 'https://images.unsplash.com/photo-1609592426508-cc8534882ce9?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
            [
                'name' => 'تلویزیون هوشمند ۶۵ اینچ سامسونگ 4K QLED مدل 65Q70C',
                'slug' => 'samsung-65-inch-4k-qled-smart-tv',
                'sku' => 'SAM-TV-65Q70C',
                'category_id' => $catTV->id,
                'brand_id' => $brandModels['samsung']->id,
                'price' => 64000000,
                'sale_price' => 59900000,
                'cost_price' => 54000000,
                'stock' => 6,
                'is_featured' => true,
                'is_best_seller' => false,
                'is_new_arrival' => false,
                'weight' => 22000,
                'short_description' => 'پنل کوانتوم دات با نرخ ۱۲۰ هرتز واقعی، پردازشگر Quantum 4K و رفرش‌ریت عالی برای پلی‌استیشن ۵',
                'description' => 'رنگ‌های ۱۰۰٪ واقعی با گواهی رسمی Pantone و شفافیت خیره‌کننده در محیط‌های پرنور. مجهز به ۴ پورت HDMI 2.1 و سیستم صوتی هماهنگ Q-Symphony برای اتصال بی‌نقص ساندبار.',
                'image' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
            [
                'name' => 'ساعت هوشمند سامسونگ مدل Galaxy Watch 6 Classic 47mm',
                'slug' => 'samsung-galaxy-watch-6-classic-47mm',
                'sku' => 'SAM-W6C-47-SLV',
                'category_id' => $catSmartwatch->id,
                'brand_id' => $brandModels['samsung']->id,
                'price' => 16800000,
                'sale_price' => 14900000,
                'cost_price' => 13500000,
                'stock' => 20,
                'is_featured' => false,
                'is_best_seller' => false,
                'is_new_arrival' => true,
                'weight' => 59,
                'short_description' => 'بازگشت بزل چرخان فیزیکی نمادین با حاشیه باریک‌تر نمایشگر، بدنه استیل ضدزنگ و شیشه یاقوت کبود',
                'description' => 'کلاسیک‌ترین و لوکس‌ترین ساعت هوشمند سامسونگ با سیستم‌عامل پیشرفته WearOS گوگل و امکان پایش ترکیبات بدنی (BIA)، فشار خون و وضعیت ریتم قلب.',
                'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&auto=format&fit=crop&q=80',
                'variants' => [],
            ],
        ];

        foreach ($productsSeed as $pData) {
            $variants = $pData['variants'] ?? [];
            $imgUrl = $pData['image'];
            unset($pData['variants'], $pData['image']);

            $product = Product::firstOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );

            // Primary Image
            ProductImage::firstOrCreate(
                ['product_id' => $product->id, 'image_path' => $imgUrl],
                [
                    'is_primary' => true,
                    'sort_order' => 0,
                    'alt_text' => $product->name,
                ]
            );

            // Master Product Inventory
            $totalStock = empty($variants) ? $product->stock : array_sum(array_column($variants, 'stock'));
            $product->update(['stock' => $totalStock]);

            Inventory::firstOrCreate(
                ['product_id' => $product->id, 'product_variant_id' => null],
                [
                    'stock' => $totalStock,
                    'reserved_stock' => 0,
                    'low_stock_threshold' => 3,
                ]
            );

            // Variants & Variant Inventories
            foreach ($variants as $vData) {
                $variant = ProductVariant::firstOrCreate(
                    ['sku' => $vData['sku']],
                    [
                        'product_id' => $product->id,
                        'price' => $vData['price'],
                        'sale_price' => $vData['sale_price'],
                        'stock' => $vData['stock'],
                        'attributes_json' => $vData['attributes'],
                        'is_active' => true,
                    ]
                );

                Inventory::firstOrCreate(
                    ['product_id' => $product->id, 'product_variant_id' => $variant->id],
                    [
                        'stock' => $vData['stock'],
                        'reserved_stock' => 0,
                        'low_stock_threshold' => 2,
                    ]
                );
            }
        }

        // 7. Banners
        $banners = [
            [
                'title' => 'جشنواره بزرگ معرفی پرچمداران ۲۰۲۴',
                'subtitle' => 'جدیدترین گوشی‌های تیتانیومی سامسونگ و اپل با ۱۸ ماه گارانتی شرکتی و ارسال رایگان',
                'image_path' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1600&auto=format&fit=crop&q=80',
                'link_url' => '/catalog?category_id='.$catMobile->id,
                'badge_text' => 'فروش ویژه پرچمداران',
                'position' => 'hero',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'لپ‌تاپ‌های گیمینگ و قدرتمندترین ایستگاه‌های کاری',
                'subtitle' => 'خرید به همراه هدایای ویژه، ماوس گیمینگ و ضمانت سلامت کالا',
                'image_path' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=1600&auto=format&fit=crop&q=80',
                'link_url' => '/catalog?category_id='.$catLaptop->id,
                'badge_text' => 'پیشنهاد شگفت‌انگیز',
                'position' => 'hero',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'لوازم جانبی و هدفون‌های نویزکنسلینگ',
                'subtitle' => 'تجربه موسیقی با کیفیتی بی‌مانند در محصولات سونی و اپل',
                'image_path' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&auto=format&fit=crop&q=80',
                'link_url' => '/catalog?category_id='.$catHeadphones->id,
                'badge_text' => 'تخفیف تا ۳۰٪',
                'position' => 'promo_top',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'ساعت‌های هوشمند و مچ‌بندهای سلامتی',
                'subtitle' => 'پایش ورزشی حرفه‌ای با اپل واچ و گلکسی واچ جدید',
                'image_path' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                'link_url' => '/catalog?category_id='.$catSmartwatch->id,
                'badge_text' => 'سلامتی و تناسب اندام',
                'position' => 'promo_top',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'تلویزیون‌های هوشمند 4K با وضوح خیره‌کننده سینمایی',
                'subtitle' => 'فرصتی تکرارنشدنی برای تجهیز سالن پذیرایی با برترین پنل‌های کیولد',
                'image_path' => 'https://images.unsplash.com/photo-1593784991095-a205069470b6?w=1600&auto=format&fit=crop&q=80',
                'link_url' => '/catalog?category_id='.$catTV->id,
                'badge_text' => 'سینمای خانگی',
                'position' => 'promo_bottom',
                'sort_order' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $bData) {
            Banner::firstOrCreate(['title' => $bData['title']], $bData);
        }

        // 8. Coupons
        $coupons = [
            [
                'code' => 'DIGI2026',
                'type' => 'percentage',
                'value' => 10,
                'minimum_order_amount' => 500000,
                'maximum_discount_amount' => 300000,
                'usage_limit' => 100,
                'per_user_limit' => 2,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME',
                'type' => 'fixed',
                'value' => 50000,
                'minimum_order_amount' => 300000,
                'maximum_discount_amount' => null,
                'usage_limit' => 500,
                'per_user_limit' => 1,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'VIPDISCOUNT',
                'type' => 'percentage',
                'value' => 20,
                'minimum_order_amount' => 2000000,
                'maximum_discount_amount' => 800000,
                'usage_limit' => 50,
                'per_user_limit' => 1,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $cData) {
            Coupon::firstOrCreate(['code' => $cData['code']], $cData);
        }

        // 9. Site Settings
        $settings = [
            'site_name' => 'دیجی‌استور',
            'site_tagline' => 'مرجع تخصصی خرید آنلاین کالاهای اورجینال دیجیتال و الکترونیک',
            'site_about' => 'دیجی‌استور با تضمین اصل بودن کالاها، ارسال سریع اکسپرس و هفت روز ضمانت بازگشت، خریدی مطمئن و لذت‌بخش را برای هم‌وطنان عزیز رقم می‌زند.',
            'contact_phone' => '۰۲۱-۸۸۸۸۹۹۹۹',
            'contact_email' => 'support@digistore.ir',
            'contact_address' => 'تهران، خیابان ولیعصر، بالاتر از پارک ساعی، برج تجارت، طبقه ۴',
            'shipping_base_cost' => '49000',
            'free_shipping_threshold' => '1500000',
            'tax_rate_percentage' => '0',
            'social_instagram' => 'https://instagram.com/digistore',
            'social_telegram' => 'https://t.me/digistore',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::set($key, $val);
        }

        // 10. Sample Customer Reviews
        $s24 = Product::where('slug', 'samsung-galaxy-s24-ultra')->first();
        if ($s24) {
            Review::firstOrCreate(
                ['product_id' => $s24->id, 'user_id' => $customer->id],
                [
                    'rating' => 5,
                    'title' => 'بهترین گوشی اندرویدی جهان بدون شک!',
                    'body' => 'کیفیت ساخت بدنه تیتانیومی فوق‌العاده است. قابلیت‌های Galaxy AI به خصوص در ترجمه و خلاصه کردن متن‌ها حیرت‌انگیز کار می‌کنه. ارسال دیجی‌استور هم کمتر از ۲۴ ساعت در تهران انجام شد.',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                ]
            );
        }

        $ip15 = Product::where('slug', 'apple-iphone-15-pro-max')->first();
        if ($ip15) {
            Review::firstOrCreate(
                ['product_id' => $ip15->id, 'user_id' => $buyer->id],
                [
                    'rating' => 5,
                    'title' => 'عالی و بسیار سبک‌تر از نسل قبل',
                    'body' => 'وزن گوشی به خاطر تیتانیوم کاهش محسوسی داشته و در دست گرفتن طولانی مدت دست رو خسته نمی‌کنه. دوربین ۵ برابری هم فوق‌العاده با کیفیته.',
                    'status' => 'approved',
                    'is_verified_purchase' => true,
                ]
            );
        }

        // 11. Amazon-scale full categories & departments
        $this->call(AmazonScaleCategoriesSeeder::class);
    }
}
