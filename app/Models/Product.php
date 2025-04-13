<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'quantity',
        'image',
        'category_id'
    ];

    /**
     * Get the user that owns the product.
     */

     public function category()
     {
         return $this->belongsTo(Category::class);
     }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Boot method to register model events
    protected static function boot()
    {
        parent::boot();

        // Listen for the 'deleting' event
        static::deleting(function ($product) {
            // Delete the associated image file
            if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
                Storage::disk('public')->delete('images/' . $product->image);
            }
        });
    }
}