<?php

// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Game;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Banner;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
       // Admin
User::firstOrCreate(
    ['email' => 'admin@topupgame.com'],
    [
        'name' => 'Admin',
        'password' => Hash::make('password123'),
        'is_admin' => true,
        'email_verified_at' => now(),
    ]
);

// Regular User
User::firstOrCreate(
    ['email' => 'user@example.com'],
    [
        'name' => 'John Doe',
        'password' => Hash::make('password123'),
        'is_admin' => false,
        'phone' => '081234567890',
        'email_verified_at' => now(),
    ]
);

        // Seed Games
        $games = [
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'description' => 'Top up Diamond Mobile Legends dengan harga termurah, proses cepat dan aman.',
                'publisher' => 'Moonton',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true],
                    ['name' => 'zone_id', 'label' => 'Zone ID', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'description' => 'Top up Diamond Free Fire dengan berbagai pilihan nominal, proses instan.',
                'publisher' => 'Garena',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'player_id', 'label' => 'Player ID', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'description' => 'Beli UC PUBG Mobile dengan harga terjangkau dan proses otomatis.',
                'publisher' => 'Tencent',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'description' => 'Top up Genesis Crystal dan Welkin Moon Genshin Impact.',
                'publisher' => 'HoYoverse',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'UID', 'type' => 'text', 'required' => true],
                    ['name' => 'server', 'label' => 'Server', 'type' => 'select', 'required' => true, 
                     'options' => ['Asia', 'America', 'Europe', 'TW, HK, MO']]
                ]
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'description' => 'Top up Valorant Points (VP) untuk PC dengan berbagai nominal.',
                'publisher' => 'Riot Games',
                'category' => 'pc',
                'form_fields' => [
                    ['name' => 'riot_id', 'label' => 'Riot ID', 'type' => 'text', 'required' => true],
                    ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Call of Duty Mobile',
                'slug' => 'call-of-duty-mobile',
                'description' => 'Top up CP Call of Duty Mobile dengan proses cepat.',
                'publisher' => 'Activision',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'open_id', 'label' => 'Open ID', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Honor of Kings',
                'slug' => 'honor-of-kings',
                'description' => 'Top up Token Honor of Kings Indonesia.',
                'publisher' => 'Tencent',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true]
                ]
            ],
            [
                'name' => 'Honkai Star Rail',
                'slug' => 'honkai-star-rail',
                'description' => 'Top up Oneiric Shard Honkai Star Rail.',
                'publisher' => 'HoYoverse',
                'category' => 'mobile',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'UID', 'type' => 'text', 'required' => true],
                    ['name' => 'server', 'label' => 'Server', 'type' => 'select', 'required' => true,
                     'options' => ['Asia', 'America', 'Europe', 'TW, HK, MO']]
                ]
            ]
        ];

        foreach ($games as $index => $gameData) {
            $game = Game::firstOrCreate(
                ['slug' => $gameData['slug']],
                [
                'name' => $gameData['name'],
                'description' => $gameData['description'],
                'publisher' => $gameData['publisher'],
                'category' => $gameData['category'],
                'form_fields' => $gameData['form_fields'],
                'is_active' => true,
                'sort_order' => $index
            ]);

            // Create products for each game
            $this->createProductsForGame($game);
        }

        // Seed Payment Methods
        $paymentMethods = [
            // E-Wallets
            ['name' => 'GoPay', 'code' => 'gopay', 'type' => 'e-wallet', 'fee_flat' => 1000, 'fee_percent' => 0],
            ['name' => 'OVO', 'code' => 'ovo', 'type' => 'e-wallet', 'fee_flat' => 1000, 'fee_percent' => 0],
            ['name' => 'DANA', 'code' => 'dana', 'type' => 'e-wallet', 'fee_flat' => 1000, 'fee_percent' => 0],
            ['name' => 'ShopeePay', 'code' => 'shopeepay', 'type' => 'e-wallet', 'fee_flat' => 1000, 'fee_percent' => 0],
            ['name' => 'LinkAja', 'code' => 'linkaja', 'type' => 'e-wallet', 'fee_flat' => 1500, 'fee_percent' => 0],
            
            // Virtual Accounts
            ['name' => 'BCA Virtual Account', 'code' => 'bca_va', 'type' => 'virtual_account', 'fee_flat' => 4000, 'fee_percent' => 0],
            ['name' => 'BNI Virtual Account', 'code' => 'bni_va', 'type' => 'virtual_account', 'fee_flat' => 4000, 'fee_percent' => 0],
            ['name' => 'BRI Virtual Account', 'code' => 'bri_va', 'type' => 'virtual_account', 'fee_flat' => 4000, 'fee_percent' => 0],
            ['name' => 'Mandiri Virtual Account', 'code' => 'mandiri_va', 'type' => 'virtual_account', 'fee_flat' => 4000, 'fee_percent' => 0],
            ['name' => 'Permata Virtual Account', 'code' => 'permata_va', 'type' => 'virtual_account', 'fee_flat' => 4000, 'fee_percent' => 0],
            
            // Convenience Store
            ['name' => 'Indomaret', 'code' => 'indomaret', 'type' => 'convenience_store', 'fee_flat' => 5000, 'fee_percent' => 0],
            ['name' => 'Alfamart', 'code' => 'alfamart', 'type' => 'convenience_store', 'fee_flat' => 5000, 'fee_percent' => 0],
            
            // QRIS
            ['name' => 'QRIS', 'code' => 'qris', 'type' => 'qris', 'fee_flat' => 750, 'fee_percent' => 0.7],
            
            // Credit Card
            ['name' => 'Credit Card', 'code' => 'credit_card', 'type' => 'credit_card', 'fee_flat' => 0, 'fee_percent' => 3],
        ];

        foreach ($paymentMethods as $index => $method) {
            PaymentMethod::firstOrCreate(
                ['code' => $method['code']],
                [
                'name' => $method['name'],
                'type' => $method['type'],
                'fee_flat' => $method['fee_flat'],
                'fee_percent' => $method['fee_percent'],
                'is_active' => true,
                'sort_order' => $index
            ]);
        }

        // Seed Banners
        $banners = [
            [
                'title' => 'Promo Mobile Legends Diamond',
                'link' => '/game/mobile-legends',
                'image' => 'banners/mobilelegend.jpg',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Event Free Fire Special',
                'link' => '/game/free-fire',
                'image' => 'banners/freefire.jpg',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Genshin Impact Update',
                'link' => '/game/genshin-impact',
                'image' => 'banners/genshinimpact.jpg',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }

        // Seed Testimonials
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'game' => 'Mobile Legends',
                'content' => 'Proses top up sangat cepat, tidak sampai 1 menit diamond sudah masuk. Recommended!',
                'rating' => 5
            ],
            [
                'name' => 'Siti Nurhaliza',
                'game' => 'Free Fire',
                'content' => 'Harga paling murah yang pernah saya temukan, pelayanan juga ramah dan responsif.',
                'rating' => 5
            ],
            [
                'name' => 'Ahmad Fauzi',
                'game' => 'PUBG Mobile',
                'content' => 'Sudah langganan di sini, selalu aman dan terpercaya. UC langsung masuk!',
                'rating' => 5
            ],
            [
                'name' => 'Dewi Lestari',
                'game' => 'Genshin Impact',
                'content' => 'Top up Genesis Crystal di sini lebih murah daripada in-game. Proses juga cepat.',
                'rating' => 4
            ],
            [
                'name' => 'Rizky Pratama',
                'game' => 'Valorant',
                'content' => 'Website mudah digunakan, pembayaran banyak pilihan. VP langsung masuk ke akun.',
                'rating' => 5
            ]
        ];

        foreach ($testimonials as $index => $testimonial) {
            Testimonial::firstOrCreate(
                [
                'content' => $testimonial['content'],
                'name' => $testimonial['name'],
                'game' => $testimonial['game'],
                'rating' => $testimonial['rating'],
                'is_active' => true,
                'sort_order' => $index
            ]);
        }

        // Seed Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'TopUp Game Store', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Platform top up game terpercaya dengan harga termurah', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_keywords', 'value' => 'top up game, diamond ml, uc pubg, genesis crystal', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@topupgame.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '081234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '6281234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Jl. Game Center No. 123, Jakarta', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/topupgame', 'type' => 'text', 'group' => 'social'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/topupgame', 'type' => 'text', 'group' => 'social'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com/topupgame', 'type' => 'text', 'group' => 'social'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/topupgame', 'type' => 'text', 'group' => 'social'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'system'],
            ['key' => 'allow_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'system'],
            ['key' => 'email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'system'],
        ];


        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }


    private function createProductsForGame($game)
    {
        $productTemplates = [
            'Mobile Legends' => [
                ['amount' => 86, 'bonus' => 0, 'price' => 20000, 'original_price' => 22000, 'currency' => 'Diamond'],
                ['amount' => 172, 'bonus' => 0, 'price' => 39000, 'original_price' => 44000, 'currency' => 'Diamond'],
                ['amount' => 257, 'bonus' => 0, 'price' => 59000, 'original_price' => 66000, 'currency' => 'Diamond'],
                ['amount' => 344, 'bonus' => 0, 'price' => 78000, 'original_price' => 88000, 'currency' => 'Diamond'],
                ['amount' => 429, 'bonus' => 0, 'price' => 98000, 'original_price' => 110000, 'currency' => 'Diamond'],
                ['amount' => 514, 'bonus' => 0, 'price' => 117000, 'original_price' => 132000, 'currency' => 'Diamond'],
                ['amount' => 706, 'bonus' => 0, 'price' => 156000, 'original_price' => 176000, 'currency' => 'Diamond'],
                ['amount' => 878, 'bonus' => 0, 'price' => 195000, 'original_price' => 220000, 'currency' => 'Diamond'],
                ['amount' => 1163, 'bonus' => 0, 'price' => 254000, 'original_price' => 286000, 'currency' => 'Diamond'],
                ['amount' => 2195, 'bonus' => 0, 'price' => 468000, 'original_price' => 528000, 'currency' => 'Diamond'],
                ['amount' => 3688, 'bonus' => 0, 'price' => 780000, 'original_price' => 880000, 'currency' => 'Diamond'],
                ['amount' => 5532, 'bonus' => 0, 'price' => 1170000, 'original_price' => 1320000, 'currency' => 'Diamond'],
            ],
            'Free Fire' => [
                ['amount' => 50, 'bonus' => 0, 'price' => 8000, 'original_price' => 9000, 'currency' => 'Diamond'],
                ['amount' => 70, 'bonus' => 0, 'price' => 10000, 'original_price' => 11000, 'currency' => 'Diamond'],
                ['amount' => 140, 'bonus' => 0, 'price' => 19000, 'original_price' => 22000, 'currency' => 'Diamond'],
                ['amount' => 210, 'bonus' => 0, 'price' => 29000, 'original_price' => 33000, 'currency' => 'Diamond'],
                ['amount' => 355, 'bonus' => 0, 'price' => 48000, 'original_price' => 55000, 'currency' => 'Diamond'],
                ['amount' => 720, 'bonus' => 0, 'price' => 95000, 'original_price' => 110000, 'currency' => 'Diamond'],
                ['amount' => 1450, 'bonus' => 0, 'price' => 190000, 'original_price' => 220000, 'currency' => 'Diamond'],
                ['amount' => 2180, 'bonus' => 0, 'price' => 285000, 'original_price' => 330000, 'currency' => 'Diamond'],
                ['amount' => 3640, 'bonus' => 0, 'price' => 475000, 'original_price' => 550000, 'currency' => 'Diamond'],
            ],
            'PUBG Mobile' => [
                ['amount' => 60, 'bonus' => 0, 'price' => 15000, 'original_price' => 16500, 'currency' => 'UC'],
                ['amount' => 325, 'bonus' => 0, 'price' => 75000, 'original_price' => 82500, 'currency' => 'UC'],
                ['amount' => 660, 'bonus' => 0, 'price' => 150000, 'original_price' => 165000, 'currency' => 'UC'],
                ['amount' => 1800, 'bonus' => 0, 'price' => 375000, 'original_price' => 412500, 'currency' => 'UC'],
                ['amount' => 3850, 'bonus' => 0, 'price' => 750000, 'original_price' => 825000, 'currency' => 'UC'],
                ['amount' => 8100, 'bonus' => 0, 'price' => 1500000, 'original_price' => 1650000, 'currency' => 'UC'],
            ],
            'Genshin Impact' => [
                ['amount' => 60, 'bonus' => 0, 'price' => 16000, 'original_price' => 18000, 'currency' => 'Genesis Crystal'],
                ['amount' => 330, 'bonus' => 0, 'price' => 79000, 'original_price' => 89000, 'currency' => 'Genesis Crystal'],
                ['amount' => 1090, 'bonus' => 0, 'price' => 249000, 'original_price' => 279000, 'currency' => 'Genesis Crystal'],
                ['amount' => 2240, 'bonus' => 0, 'price' => 479000, 'original_price' => 539000, 'currency' => 'Genesis Crystal'],
                ['amount' => 3880, 'bonus' => 0, 'price' => 799000, 'original_price' => 899000, 'currency' => 'Genesis Crystal'],
                ['amount' => 8080, 'bonus' => 0, 'price' => 1599000, 'original_price' => 1799000, 'currency' => 'Genesis Crystal'],
            ],
            'Valorant' => [
                ['amount' => 420, 'bonus' => 0, 'price' => 50000, 'original_price' => 55000, 'currency' => 'VP'],
                ['amount' => 700, 'bonus' => 0, 'price' => 80000, 'original_price' => 88000, 'currency' => 'VP'],
                ['amount' => 1375, 'bonus' => 0, 'price' => 150000, 'original_price' => 165000, 'currency' => 'VP'],
                ['amount' => 2400, 'bonus' => 0, 'price' => 250000, 'original_price' => 275000, 'currency' => 'VP'],
                ['amount' => 4000, 'bonus' => 0, 'price' => 400000, 'original_price' => 440000, 'currency' => 'VP'],
                ['amount' => 8150, 'bonus' => 0, 'price' => 800000, 'original_price' => 880000, 'currency' => 'VP'],
            ]
        ];

        // Use default template if game doesn't have specific products
        $defaultTemplate = [
            ['amount' => 100, 'bonus' => 0, 'price' => 20000, 'original_price' => 22000, 'currency' => 'Currency'],
            ['amount' => 500, 'bonus' => 0, 'price' => 95000, 'original_price' => 105000, 'currency' => 'Currency'],
            ['amount' => 1000, 'bonus' => 0, 'price' => 185000, 'original_price' => 205000, 'currency' => 'Currency'],
            ['amount' => 2000, 'bonus' => 0, 'price' => 365000, 'original_price' => 405000, 'currency' => 'Currency'],
            ['amount' => 5000, 'bonus' => 0, 'price' => 895000, 'original_price' => 995000, 'currency' => 'Currency'],
        ];

        $products = $productTemplates[$game->name] ?? $defaultTemplate;

        foreach ($products as $index => $product) {
            Product::create([
                'game_id' => $game->id,
                'name' => $product['amount'] . ' ' . $product['currency'],
                'description' => $product['bonus'] > 0 ? 'Bonus +' . $product['bonus'] : null,
                'price' => $product['price'],
                'original_price' => $product['original_price'],
                'bonus' => $product['bonus'],
                'currency_type' => $product['currency'],
                'amount' => $product['amount'],
                'is_promo' => $product['original_price'] > $product['price'],
                'is_active' => true,
                'sort_order' => $index
            ]);
        }
    }
}