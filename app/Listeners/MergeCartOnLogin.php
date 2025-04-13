<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeCartOnLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $cart = Session::get('cart', []);

        // Load user's cart from database
        $dbCart = \App\Models\Cart::where('user_id', $user->id)->get();

        foreach ($dbCart as $item) {
            if (!isset($cart[$item->product_id])) {
                $cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->image,
                    'max_quantity' => $item->product->quantity,
                ];
            }
        }

        Session::put('cart', $cart);
    }

    /**
     * Handle the event.
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\MergeCartOnLogin',
        ],
    ];
}
