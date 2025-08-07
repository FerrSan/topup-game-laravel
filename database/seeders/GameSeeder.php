<?php

// database/seeders/GameSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Support\Str;

class GameSeeder extends Seeder
{
    public function run()
    {
        // Array of games with image URLs (you can use external URLs for testing)
        $games = [
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'description' => 'Top up Diamond Mobile Legends dengan harga termurah, proses cepat dan aman. Dapatkan skin dan hero favoritmu sekarang!',
                'publisher' => 'Moonton',
                'category' => 'mobile',
                'image' => 'games/mobile-legends.jpg', // akan di-generate atau download
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan User ID'],
                    ['name' => 'zone_id', 'label' => 'Zone ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan Zone ID']
                ]
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'description' => 'Top up Diamond Free Fire dengan berbagai pilihan nominal. Proses instan, aman, dan terpercaya. Dapatkan bundle dan skin eksklusif!',
                'publisher' => 'Garena',
                'category' => 'mobile',
                'image' => 'games/free-fire.jpg',
                'form_fields' => [
                    ['name' => 'player_id', 'label' => 'Player ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan Player ID']
                ]
            ],
            [
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'description' => 'Beli UC PUBG Mobile dengan harga terjangkau. Proses otomatis 24/7, dapatkan skin senjata dan outfit keren!',
                'publisher' => 'Tencent',
                'category' => 'mobile',
                'image' => 'games/pubg-mobile.jpg',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan User ID']
                ]
            ],
            [
                'name' => 'Genshin Impact',
                'slug' => 'genshin-impact',
                'description' => 'Top up Genesis Crystal dan Welkin Moon Genshin Impact. Dapatkan karakter dan senjata bintang 5 favoritmu!',
                'publisher' => 'HoYoverse',
                'category' => 'mobile',
                'image' => 'games/genshin-impact.jpg',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'UID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan UID'],
                    ['name' => 'server', 'label' => 'Server', 'type' => 'select', 'required' => true, 
                     'options' => ['Asia', 'America', 'Europe', 'TW, HK, MO']]
                ]
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'description' => 'Top up Valorant Points (VP) untuk PC. Beli skin senjata, battle pass, dan bundle eksklusif!',
                'publisher' => 'Riot Games',
                'category' => 'pc',
                'image' => 'games/valorant.jpg',
                'form_fields' => [
                    ['name' => 'riot_id', 'label' => 'Riot ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: PlayerName'],
                    ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: #1234']
                ]
            ],
            [
                'name' => 'Call of Duty Mobile',
                'slug' => 'call-of-duty-mobile',
                'description' => 'Top up CP Call of Duty Mobile dengan proses cepat. Dapatkan senjata legendaris dan skin operator!',
                'publisher' => 'Activision',
                'category' => 'mobile',
                'image' => 'games/cod-mobile.jpg',
                'form_fields' => [
                    ['name' => 'open_id', 'label' => 'Open ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan Open ID']
                ]
            ],
            [
                'name' => 'Honor of Kings',
                'slug' => 'honor-of-kings',
                'description' => 'Top up Token Honor of Kings Indonesia. Game MOBA terbaru dengan grafis memukau!',
                'publisher' => 'Tencent',
                'category' => 'mobile',
                'image' => 'games/honor-of-kings.jpg',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan User ID']
                ]
            ],
            [
                'name' => 'Honkai Star Rail',
                'slug' => 'honkai-star-rail',
                'description' => 'Top up Oneiric Shard Honkai Star Rail. Gacha karakter bintang 5 dan nikmati petualangan antariksa!',
                'publisher' => 'HoYoverse',
                'category' => 'mobile',
                'image' => 'games/honkai-star-rail.jpg',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'UID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan UID'],
                    ['name' => 'server', 'label' => 'Server', 'type' => 'select', 'required' => true,
                     'options' => ['Asia', 'America', 'Europe', 'TW, HK, MO']]
                ]
            ],
            [
                'name' => 'Arena of Valor',
                'slug' => 'arena-of-valor',
                'description' => 'Top up Voucher Arena of Valor. MOBA mobile dengan hero-hero unik dan gameplay seru!',
                'publisher' => 'Garena',
                'category' => 'mobile',
                'image' => 'games/aov.jpg',
                'form_fields' => [
                    ['name' => 'open_id', 'label' => 'Open ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan Open ID']
                ]
            ],
            [
                'name' => 'League of Legends Wild Rift',
                'slug' => 'lol-wild-rift',
                'description' => 'Top up Wild Core League of Legends Wild Rift. Experience League of Legends di mobile!',
                'publisher' => 'Riot Games',
                'category' => 'mobile',
                'image' => 'games/wild-rift.jpg',
                'form_fields' => [
                    ['name' => 'riot_id', 'label' => 'Riot ID', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: PlayerName'],
                    ['name' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'required' => true, 'placeholder' => 'Contoh: #1234']
                ]
            ],
            [
                'name' => 'Stumble Guys',
                'slug' => 'stumble-guys',
                'description' => 'Top up Gems dan Stumble Pass. Party game seru untuk dimainkan bersama teman!',
                'publisher' => 'Kitka Games',
                'category' => 'mobile',
                'image' => 'games/stumble-guys.jpg',
                'form_fields' => [
                    ['name' => 'user_id', 'label' => 'User ID', 'type' => 'text', 'required' => true, 'placeholder' => 'ID dapat dilihat di profil']
                ]
            ],
            [
                'name' => 'Roblox',
                'slug' => 'roblox',
                'description' => 'Top up Robux untuk Roblox. Beli item, aksesoris, dan game pass favoritmu!',
                'publisher' => 'Roblox Corporation',
                'category' => 'pc',
                'image' => 'games/roblox.jpg',
                'form_fields' => [
                    ['name' => 'username', 'label' => 'Username', 'type' => 'text', 'required' => true, 'placeholder' => 'Masukkan Username Roblox']
                ]
            ]
        ];

        foreach ($games as $index => $gameData) {
            $game = Game::create([
                'name' => $gameData['name'],
                'slug' => $gameData['slug'],
                'description' => $gameData['description'],
                'publisher' => $gameData['publisher'],
                'category' => $gameData['category'],
                'image' => $gameData['image'] ?? null,
                'banner' => $gameData['image'] ?? null, // Use same image for banner
                'form_fields' => $gameData['form_fields'],
                'is_active' => true,
                'sort_order' => $index
            ]);

            // Create products for each game
            $this->createProductsForGame($game);
        }
    }

    private function createProductsForGame($game)
    {
        $productTemplates = [
            'Mobile Legends' => [
                ['amount' => 3, 'bonus' => 0, 'price' => 1000, 'original_price' => 1500, 'currency' => 'Diamond'],
                ['amount' => 5, 'bonus' => 0, 'price' => 1500, 'original_price' => 2000, 'currency' => 'Diamond'],
                ['amount' => 12, 'bonus' => 0, 'price' => 3000, 'original_price' => 3500, 'currency' => 'Diamond'],
                ['amount' => 19, 'bonus' => 0, 'price' => 5000, 'original_price' => 5500, 'currency' => 'Diamond'],
                ['amount' => 28, 'bonus' => 0, 'price' => 7000, 'original_price' => 8000, 'currency' => 'Diamond'],
                ['amount' => 44, 'bonus' => 0, 'price' => 10000, 'original_price' => 11000, 'currency' => 'Diamond'],
                ['amount' => 59, 'bonus' => 0, 'price' => 14000, 'original_price' => 15000, 'currency' => 'Diamond'],
                ['amount' => 86, 'bonus' => 0, 'price' => 20000, 'original_price' => 22000, 'currency' => 'Diamond'],
                ['amount' => 172, 'bonus' => 0, 'price' => 39000, 'original_price' => 44000, 'currency' => 'Diamond'],
                ['amount' => 257, 'bonus' => 0, 'price' => 59000, 'original_price' => 66000, 'currency' => 'Diamond'],
                ['amount' => 344, 'bonus' => 0, 'price' => 78000, 'original_price' => 88000, 'currency' => 'Diamond'],
                ['amount' => 429, 'bonus' => 0, 'price' => 98000, 'original_price' => 110000, 'currency' => 'Diamond'],
                ['amount' => 514, 'bonus' => 0, 'price' => 117000, 'original_price' => 132000, 'currency' => 'Diamond'],
                ['amount' => 706, 'bonus' => 0, 'price' => 156000, 'original_price' => 176000, 'currency' => 'Diamond'],
                ['amount' => 878, 'bonus' => 0, 'price' => 195000, 'original_price' => 220000, 'currency' => 'Diamond'],
            ],
            'Free Fire' => [
                ['amount' => 5, 'bonus' => 0, 'price' => 1000, 'original_price' => 1500, 'currency' => 'Diamond'],
                ['amount' => 12, 'bonus' => 0, 'price' => 2000, 'original_price' => 2500, 'currency' => 'Diamond'],
                ['amount' => 50, 'bonus' => 0, 'price' => 8000, 'original_price' => 9000, 'currency' => 'Diamond'],
                ['amount' => 70, 'bonus' => 0, 'price' => 10000, 'original_price' => 11000, 'currency' => 'Diamond'],
                ['amount' => 140, 'bonus' => 0, 'price' => 19000, 'original_price' => 22000, 'currency' => 'Diamond'],
                ['amount' => 210, 'bonus' => 0, 'price' => 29000, 'original_price' => 33000, 'currency' => 'Diamond'],
                ['amount' => 355, 'bonus' => 0, 'price' => 48000, 'original_price' => 55000, 'currency' => 'Diamond'],
                ['amount' => 720, 'bonus' => 0, 'price' => 95000, 'original_price' => 110000, 'currency' => 'Diamond'],
            ],
            'PUBG Mobile' => [
                ['amount' => 60, 'bonus' => 0, 'price' => 15000, 'original_price' => 16500, 'currency' => 'UC'],
                ['amount' => 325, 'bonus' => 0, 'price' => 75000, 'original_price' => 82500, 'currency' => 'UC'],
                ['amount' => 660, 'bonus' => 0, 'price' => 150000, 'original_price' => 165000, 'currency' => 'UC'],
                ['amount' => 1800, 'bonus' => 0, 'price' => 375000, 'original_price' => 412500, 'currency' => 'UC'],
                ['amount' => 3850, 'bonus' => 0, 'price' => 750000, 'original_price' => 825000, 'currency' => 'UC'],
            ],
            'Genshin Impact' => [
                ['amount' => 60, 'bonus' => 0, 'price' => 16000, 'original_price' => 18000, 'currency' => 'Genesis Crystal'],
                ['amount' => 330, 'bonus' => 0, 'price' => 79000, 'original_price' => 89000, 'currency' => 'Genesis Crystal'],
                ['amount' => 1090, 'bonus' => 0, 'price' => 249000, 'original_price' => 279000, 'currency' => 'Genesis Crystal'],
                ['amount' => 2240, 'bonus' => 0, 'price' => 479000, 'original_price' => 539000, 'currency' => 'Genesis Crystal'],
                ['amount' => 3880, 'bonus' => 0, 'price' => 799000, 'original_price' => 899000, 'currency' => 'Genesis Crystal'],
                ['amount' => 8080, 'bonus' => 0, 'price' => 1599000, 'original_price' => 1799000, 'currency' => 'Genesis Crystal'],
                // Welkin Moon
                ['amount' => 1, 'bonus' => 0, 'price' => 79000, 'original_price' => 89000, 'currency' => 'Welkin Moon'],
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
            ['amount' => 60, 'bonus' => 0, 'price' => 10000, 'original_price' => 12000, 'currency' => 'Currency'],
            ['amount' => 120, 'bonus' => 0, 'price' => 20000, 'original_price' => 22000, 'currency' => 'Currency'],
            ['amount' => 300, 'bonus' => 0, 'price' => 48000, 'original_price' => 52000, 'currency' => 'Currency'],
            ['amount' => 600, 'bonus' => 0, 'price' => 95000, 'original_price' => 105000, 'currency' => 'Currency'],
            ['amount' => 1200, 'bonus' => 0, 'price' => 185000, 'original_price' => 205000, 'currency' => 'Currency'],
            ['amount' => 2400, 'bonus' => 0, 'price' => 365000, 'original_price' => 405000, 'currency' => 'Currency'],
            ['amount' => 6000, 'bonus' => 0, 'price' => 895000, 'original_price' => 995000, 'currency' => 'Currency'],
        ];

        $products = $productTemplates[$game->name] ?? $defaultTemplate;

        foreach ($products as $index => $product) {
            // Set random promo for some products
            $isPromo = rand(0, 100) < 30; // 30% chance of promo
            
            Product::create([
                'game_id' => $game->id,
                'name' => $product['amount'] . ' ' . $product['currency'],
                'description' => $product['bonus'] > 0 ? 'Bonus +' . $product['bonus'] : null,
                'price' => $product['price'],
                'original_price' => $isPromo ? $product['original_price'] : null,
                'bonus' => $product['bonus'],
                'currency_type' => $product['currency'],
                'amount' => $product['amount'],
                'is_promo' => $isPromo,
                'is_active' => true,
                'sort_order' => $index
            ]);
        }
    }
}