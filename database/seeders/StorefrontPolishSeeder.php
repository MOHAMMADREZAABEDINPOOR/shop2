<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StorefrontPolishSeeder extends Seeder
{
    /**
     * Give every category its own cover image (no emoji),
     * refresh hero/promo banners with professional copy and
     * working shop links, and rotate product primary images
     * so neighbouring products no longer look identical.
     */
    public function run(): void
    {
        $this->polishCategories();
        $this->refreshBanners();
        $this->rotateProductPrimaries();
    }

    private function unsplash(string $photoId, int $width = 800): string
    {
        return "https://images.unsplash.com/{$photoId}?w={$width}&auto=format&fit=crop&q=80";
    }

    private function polishCategories(): void
    {
        $covers = [
            // Root categories
            'digital-goods' => ['photo-1498049794561-7780e7231661', 'موبایل، لپ‌تاپ، تبلت و ساعت هوشمند از معتبرترین برندهای جهانی با گارانتی رسمی'],
            'accessories' => ['photo-1583863788434-e58a36330cf0', 'شارژر، کابل، پاوربانک و حافظه جانبی اورجینال با ضمانت اصالت'],
            'audio-video' => ['photo-1593784991095-a205069470b6', 'تلویزیون، اسپیکر و سیستم صوتی حرفه‌ای برای تجربه سینمایی در خانه'],
            'gaming' => ['photo-1542751371-adc38448a05e', 'کنسول، دسته و تجهیزات گیمینگ حرفه‌ای برای بازی بدون توقف'],
            'computer-parts' => ['photo-1547082299-de196ea013d6', 'قطعات کامپیوتر، مانیتور و تجهیزات اداری مخصوص کار و بازی'],
            'smart-home' => ['photo-1558089687-f282ffcbc126', 'گجت هوشمند، دوربین و لوازم خانه مدرن با کنترل از راه دور'],
            // Leaves
            'mobile-phones' => ['photo-1592750475338-74b7b21085ab', 'جدیدترین گوشی‌های پرچمدار و میان‌رده با رجیستری رسمی و ۱۸ ماه گارانتی'],
            'tablets' => ['photo-1544244015-0df4b3ffc6b0', 'تبلت و کتابخوان برای کار، تحصیل و سرگرمی با نمایشگر چشم‌نواز'],
            'laptops' => ['photo-1496181133206-80ce9b88a853', 'لپ‌تاپ اداری، مهندسی و گیمینگ از ایسوس، اپل، لنوو، اچ‌پی و دل'],
            'smart-watches' => ['photo-1546868871-7041f2a55e12', 'ساعت و مچ‌بند هوشمند با پایش سلامتی، GPS و مکالمه مستقیم'],
            'headphones' => ['photo-1505740420928-5e560c06d30e', 'هدفون و هندزفری نویزکنسلینگ سونی، اپل، جی‌بی‌ال و بوز'],
            'speakers-soundbars' => ['photo-1545454675-3531b543be5d', 'اسپیکر بلوتوثی و ساندبار با صدای فراگیر و بیس کوبنده'],
            'powerbanks' => ['photo-1609592426508-cc8534882ce9', 'پاوربانک و شارژر همراه با شارژ سریع مناسب سفر و استفاده روزمره'],
            'chargers-cables' => ['photo-1572569511254-d8f925fe2cbb', 'کابل و شارژر دیواری فست‌شارژ با انواع درگاه Type-C و لایتنینگ'],
            'storage-flash' => ['photo-1597872200969-2b65d56bd16b', 'هارد اکسترنال، SSD و فلش مموری با سرعت بالا و گارانتی طولانی'],
            'consoles' => ['photo-1606813907291-d86efa9b94db', 'پلی‌استیشن، ایکس‌باکس و لوازم جانبی گیمینگ با ضمانت سلامت'],
            'tv' => ['photo-1593359677879-a4bb92f829d1', 'تلویزیون هوشمند 4K با پنل QLED و OLED و رفرش‌ریت مناسب بازی'],
            'monitors' => ['photo-1527443224154-c4a3942d3acf', 'مانیتور اداری، طراحی و گیمینگ با دقت رنگ بالا و نرخ نوسازی سریع'],
            'keyboards-mice' => ['photo-1527864550417-7fd91fc51a46', 'ماوس و کیبورد مکانیکال و ارگونومیک مخصوص بازی و کار طولانی'],
            'pc-components' => ['photo-1591488320449-011701bb6704', 'کارت گرافیک و قطعات اسمبل سیستم گیمینگ و رندرینگ حرفه‌ای'],
            'gadgets-cameras' => ['photo-1516035069371-29a1b244cc32', 'دوربین عکاسی و گجت هوشمند برای ثبت لحظه‌ها با کیفیت حرفه‌ای'],
        ];

        $order = 1;
        foreach ($covers as $slug => [$photoId, $description]) {
            Category::where('slug', $slug)->update([
                'image' => $this->unsplash($photoId),
                'icon' => null,
                'description' => $description,
                'is_active' => true,
                'sort_order' => $order++,
            ]);
        }
    }

    private function refreshBanners(): void
    {
        DB::table('banners')->delete();

        $banners = [
            [
                'title' => 'بزرگ‌ترین فروشگاه تخصصی کالای دیجیتال',
                'subtitle' => 'بیش از ۱۵۰۰ کالای اورجینال موبایل، لپ‌تاپ، صوتی و گیمینگ با ضمانت اصالت، گارانتی رسمی و ارسال سریع به سراسر کشور',
                'image_path' => $this->unsplash('photo-1511707171634-5f897ff02aa9', 1600),
                'link_url' => '/shop?category=mobile-phones',
                'badge_text' => 'ارسال سریع به سراسر کشور',
                'position' => 'hero',
                'sort_order' => 1,
            ],
            [
                'title' => 'لپ‌تاپ و کامپیوتر؛ از کار اداری تا گیم حرفه‌ای',
                'subtitle' => 'مدل‌های روز ایسوس، اپل، لنوو، اچ‌پی و دل با پردازنده‌های نسل جدید و ۲۴ ماه گارانتی شرکتی',
                'image_path' => $this->unsplash('photo-1603302576837-37561b2e2302', 1600),
                'link_url' => '/shop?category=laptops',
                'badge_text' => 'مدل‌های ۲۰۲۴ موجود شد',
                'position' => 'hero',
                'sort_order' => 2,
            ],
            [
                'title' => 'صدای حرفه‌ای؛ از هدفون تا سینمای خانگی',
                'subtitle' => 'هدفون‌های نویزکنسلینگ، اسپیکر بلوتوثی و ساندبار سونی، جی‌بی‌ال، بوز و مارشال با ضمانت تعویض',
                'image_path' => $this->unsplash('photo-1545454675-3531b543be5d', 1600),
                'link_url' => '/shop?category=headphones',
                'badge_text' => 'تخفیف ویژه صوتی',
                'position' => 'hero',
                'sort_order' => 3,
            ],
            [
                'title' => 'ساعت هوشمند و مچ‌بند سلامتی',
                'subtitle' => 'اپل واچ، گلکسی واچ و شیائومی با پایش خواب، ضربان قلب و GPS دقیق',
                'image_path' => $this->unsplash('photo-1579586337278-3befd40fd17a'),
                'link_url' => '/shop?category=smart-watches',
                'badge_text' => 'پایش سلامتی',
                'position' => 'promo_top',
                'sort_order' => 1,
            ],
            [
                'title' => 'دنیای بازی؛ کنسول و تجهیزات گیمینگ',
                'subtitle' => 'پلی‌استیشن ۵، ایکس‌باکس، هدست و ماوس گیمینگ ریزر و لاجیتک',
                'image_path' => $this->unsplash('photo-1606813907291-d86efa9b94db'),
                'link_url' => '/shop?category=consoles',
                'badge_text' => 'پیشنهاد گیمرها',
                'position' => 'promo_top',
                'sort_order' => 2,
            ],
            [
                'title' => 'تلویزیون هوشمند 4K با گارانتی رسمی',
                'subtitle' => 'پنل‌های QLED و OLED سامسونگ با وضوح سینمایی و رفرش‌ریت مناسب پلی‌استیشن ۵',
                'image_path' => $this->unsplash('photo-1593359677879-a4bb92f829d1', 1600),
                'link_url' => '/shop?category=tv',
                'badge_text' => 'سینمای خانگی',
                'position' => 'promo_bottom',
                'sort_order' => 1,
            ],
            [
                'title' => 'پاوربانک و شارژرهای فست‌شارژ',
                'subtitle' => 'انکر، بیسوس، شیائومی و سامسونگ با توان تا ۱۴۰ وات و نمایشگر هوشمند',
                'image_path' => $this->unsplash('photo-1609592426508-cc8534882ce9', 1600),
                'link_url' => '/shop?category=powerbanks',
                'badge_text' => 'همراه همیشگی سفر',
                'position' => 'promo_bottom',
                'sort_order' => 2,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }
    }

    private function rotateProductPrimaries(): void
    {
        $productIds = ProductImage::query()
            ->select('product_id')
            ->groupBy('product_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('product_id');

        foreach ($productIds as $productId) {
            $images = ProductImage::where('product_id', $productId)
                ->orderBy('sort_order')
                ->get(['id']);

            if ($images->count() < 2) {
                continue;
            }

            $picked = $images->get($productId % $images->count());

            ProductImage::where('product_id', $productId)->update(['is_primary' => false]);
            ProductImage::where('id', $picked->id)->update(['is_primary' => true]);
        }
    }
}
