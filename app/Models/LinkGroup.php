<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LinkGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'slug',
        'background_color',
        'thumbnail',
        'is_active',
        'views_count',
        'password',
        'instagram_url',
        'facebook_url',
        'x_url',
        'threads_url',
        'website_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($linkGroup) {
            if (empty($linkGroup->slug)) {
                $linkGroup->slug = Str::slug($linkGroup->title);
                
                // Ensure slug is unique
                $count = 1;
                $originalSlug = $linkGroup->slug;
                while (static::where('slug', $linkGroup->slug)->exists()) {
                    $linkGroup->slug = $originalSlug . '-' . $count++;
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function components()
    {
        return $this->hasMany(LinkComponent::class)->orderBy('order');
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
