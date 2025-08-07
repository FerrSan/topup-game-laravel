<?php

// app/Models/Game.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'banner',
        'description',
        'publisher',
        'category',
        'is_active',
        'sort_order',
        'form_fields'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'form_fields' => 'array'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrlAttribute()
    {
        // If image exists and file exists
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        // Try default game images based on slug
        $defaultImages = [
            'mobile-legends' => 'images/games/mobile-legends.jpg',
            'free-fire' => 'images/games/free-fire.jpg',
            'pubg-mobile' => 'images/games/pubg-mobile.jpg',
            'genshin-impact' => 'images/games/genshin-impact.jpg',
            'valorant' => 'images/games/valorant.jpg',
            'call-of-duty-mobile' => 'images/games/cod-mobile.jpg',
            'honor-of-kings' => 'images/games/honor-of-kings.jpg',
            'honkai-star-rail' => 'images/games/honkai-star-rail.jpg',
        ];
        
        if (isset($defaultImages[$this->slug]) && file_exists(public_path($defaultImages[$this->slug]))) {
            return asset($defaultImages[$this->slug]);
        }
        
        // Generate placeholder
        return $this->generatePlaceholder();
    }

    /**
     * Get banner URL with fallback
     */
    public function getBannerUrlAttribute()
    {
        if ($this->banner && Storage::disk('public')->exists($this->banner)) {
            return asset('storage/' . $this->banner);
        }
        
        // Use image as banner if available
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        
        // Default banner
        if (file_exists(public_path('images/default-banner.png'))) {
            return asset('images/default-banner.png');
        }
        
        return $this->generatePlaceholder('banner');
    }

    /**
     * Generate placeholder image URL
     */
    protected function generatePlaceholder($type = 'game')
    {
        $name = urlencode($this->name);
        $bgColor = substr(md5($this->name), 0, 6);
        
        if ($type === 'banner') {
            return "https://via.placeholder.com/1200x400/{$bgColor}/ffffff?text={$name}";
        }
        
        return "https://via.placeholder.com/400x400/{$bgColor}/ffffff?text={$name}";
    }
}