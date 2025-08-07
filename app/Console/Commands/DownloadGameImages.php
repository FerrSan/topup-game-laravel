<?php

// app/Console/Commands/DownloadGameImages.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadGameImages extends Command
{
    protected $signature = 'images:download';
    protected $description = 'Download default game images';

    public function handle()
    {
        $this->info('Downloading game images...');
        
        // Game images URLs (using placeholder service for demo)
        $images = [
            'mobile-legends' => 'https://via.placeholder.com/400x400/5B21B6/ffffff?text=Mobile+Legends',
            'free-fire' => 'https://via.placeholder.com/400x400/DC2626/ffffff?text=Free+Fire',
            'pubg-mobile' => 'https://via.placeholder.com/400x400/EA580C/ffffff?text=PUBG+Mobile',
            'genshin-impact' => 'https://via.placeholder.com/400x400/7C3AED/ffffff?text=Genshin+Impact',
            'valorant' => 'https://via.placeholder.com/400x400/DC2626/ffffff?text=Valorant',
            'cod-mobile' => 'https://via.placeholder.com/400x400/059669/ffffff?text=COD+Mobile',
            'honor-of-kings' => 'https://via.placeholder.com/400x400/B91C1C/ffffff?text=Honor+of+Kings',
            'honkai-star-rail' => 'https://via.placeholder.com/400x400/1E40AF/ffffff?text=Honkai+Star+Rail',
            'aov' => 'https://via.placeholder.com/400x400/B45309/ffffff?text=AOV',
            'wild-rift' => 'https://via.placeholder.com/400x400/0369A1/ffffff?text=Wild+Rift',
            'stumble-guys' => 'https://via.placeholder.com/400x400/EC4899/ffffff?text=Stumble+Guys',
            'roblox' => 'https://via.placeholder.com/400x400/DC2626/ffffff?text=Roblox',
        ];

        // Create games directory if not exists
        Storage::disk('public')->makeDirectory('games');

        foreach ($images as $slug => $url) {
            try {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    $filename = "games/{$slug}.jpg";
                    Storage::disk('public')->put($filename, $response->body());
                    $this->info("Downloaded: {$filename}");
                } else {
                    $this->error("Failed to download: {$slug}");
                }
            } catch (\Exception $e) {
                $this->error("Error downloading {$slug}: " . $e->getMessage());
            }
        }

        // Download banner placeholders
        $this->info('Downloading banner images...');
        
        $banners = [
            'banner-1' => 'https://via.placeholder.com/1200x400/5B21B6/ffffff?text=Promo+Mobile+Legends',
            'banner-2' => 'https://via.placeholder.com/1200x400/DC2626/ffffff?text=Event+Free+Fire',
            'banner-3' => 'https://via.placeholder.com/1200x400/7C3AED/ffffff?text=Genshin+Impact+Update',
        ];

        Storage::disk('public')->makeDirectory('banners');

        foreach ($banners as $name => $url) {
            try {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    $filename = "banners/{$name}.jpg";
                    Storage::disk('public')->put($filename, $response->body());
                    $this->info("Downloaded: {$filename}");
                }
            } catch (\Exception $e) {
                $this->error("Error downloading banner: " . $e->getMessage());
            }
        }

        // Download payment method logos
        $this->info('Downloading payment method logos...');
        
        $paymentLogos = [
            'gopay' => 'https://via.placeholder.com/150x50/00AA13/ffffff?text=GoPay',
            'ovo' => 'https://via.placeholder.com/150x50/4C3494/ffffff?text=OVO',
            'dana' => 'https://via.placeholder.com/150x50/118EEA/ffffff?text=DANA',
            'shopeepay' => 'https://via.placeholder.com/150x50/EE4D2D/ffffff?text=ShopeePay',
            'linkaja' => 'https://via.placeholder.com/150x50/E82128/ffffff?text=LinkAja',
            'bca_va' => 'https://via.placeholder.com/150x50/005BAC/ffffff?text=BCA',
            'bni_va' => 'https://via.placeholder.com/150x50/F05921/ffffff?text=BNI',
            'bri_va' => 'https://via.placeholder.com/150x50/00529C/ffffff?text=BRI',
            'mandiri_va' => 'https://via.placeholder.com/150x50/003D79/ffffff?text=Mandiri',
            'permata_va' => 'https://via.placeholder.com/150x50/D4001F/ffffff?text=Permata',
            'indomaret' => 'https://via.placeholder.com/150x50/0061A0/ffffff?text=Indomaret',
            'alfamart' => 'https://via.placeholder.com/150x50/ED1C24/ffffff?text=Alfamart',
            'qris' => 'https://via.placeholder.com/150x50/1BA0E2/ffffff?text=QRIS',
            'credit_card' => 'https://via.placeholder.com/150x50/4B5563/ffffff?text=Credit+Card',
        ];

        Storage::disk('public')->makeDirectory('payment');

        foreach ($paymentLogos as $code => $url) {
            try {
                $response = Http::get($url);
                
                if ($response->successful()) {
                    $filename = "payment/{$code}.png";
                    Storage::disk('public')->put($filename, $response->body());
                    $this->info("Downloaded: {$filename}");
                }
            } catch (\Exception $e) {
                $this->error("Error downloading payment logo: " . $e->getMessage());
            }
        }

        $this->info('All images downloaded successfully!');
        $this->info('You can replace these placeholder images with real images later.');
        
        return Command::SUCCESS;
    }
}