<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(){
        return view('guest.index');
    }

    public function shop(){
        $products = Product::all();
        return view('guest.shop', compact('products'));
    }

    public function cart(){
        return view('guest.cart');
    }
}
