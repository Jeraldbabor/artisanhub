<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'user_id'];

    // Automatically generate unique slugs when creating a category
    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = $category->generateUniqueSlug();
        });
    }

    // Generates a unique slug for the artisan
    public function generateUniqueSlug()
    {
        $slug = Str::slug($this->name); // Convert name to slug (e.g., "Handicraft" → "handicraft")
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists for this artisan
        while (Category::where('user_id', $this->user_id)
                      ->where('slug', $slug)
                      ->exists()) {
            $slug = "{$originalSlug}-{$count}"; // Append a number if needed (e.g., "handicraft-1")
            $count++;
        }

        return $slug;
    }

    public function products(){
    return $this->hasMany(Product::class);
    }
}