<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart as CartModel; 
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function dashboard()
    {
        return view('buyer.dashboard');
    }

    public function shop()
    {
        $products = Product::where('quantity', '>', 0)->get();
        return view('buyer.shop', compact('products'));
    }

    public function cart()
    {
        // Get cart from session (for guests)
        $cart = session()->get('cart', []);

        // If user is logged in, sync with database
        if (auth()->check()) {
            $dbCart = CartModel::where('user_id', auth()->id())->get();
            
            foreach ($dbCart as $item) {
                $product = $item->product;
                
                if (!isset($cart[$product->id])) {
                    $cart[$product->id] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item->quantity,
                        'image' => $product->image,
                        'max_quantity' => $product->quantity,
                    ];
                }
            }
            
            session()->put('cart', $cart);
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $total = $subtotal;

        return view('buyer.cart', compact('cart', 'subtotal', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock! Only ' . $product->quantity . ' left.');
        }

        // Get current cart (from session)
        $cart = session()->get('cart', []);

        // If user is logged in, save to database
        if (auth()->check()) {
            CartModel::updateOrCreate(
                ['user_id' => auth()->id(), 'product_id' => $product->id],
                ['quantity' => \DB::raw("quantity + {$request->quantity}")]
            );
        }

        // Update session cart
        if (isset($cart[$product->id])) {
            $newQuantity = $cart[$product->id]['quantity'] + $request->quantity;
            if ($product->quantity < $newQuantity) {
                return back()->with('error', 'Exceeds stock! Only ' . ($product->quantity - $cart[$product->id]['quantity']) . ' more available.');
            }
            $cart[$product->id]['quantity'] = $newQuantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->image,
                'max_quantity' => $product->quantity,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::findOrFail($id);
            
            if ($product->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock! Only ' . $product->quantity . ' left.');
            }

            $cart[$id]['quantity'] = $request->quantity;

            // Sync with database if logged in
            if (auth()->check()) {
                CartModel::updateOrCreate(
                    ['user_id' => auth()->id(), 'product_id' => $id],
                    ['quantity' => $request->quantity]
                );
            }

            session()->put('cart', $cart);
            return back()->with('success', 'Cart updated!');
        }

        return back()->with('error', 'Product not in cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);

            // Remove from database if logged in
            if (auth()->check()) {
                CartModel::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->delete();
            }

            return back()->with('success', 'Product removed!');
        }

        return back()->with('error', 'Product not in cart!');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        $total = 0;

        foreach ($cart as $details) {
            $subtotal += $details['price'] * $details['quantity'];
        }

        $total = $subtotal; // Add shipping, taxes, etc., if needed

        return view('cart', compact('cart', 'subtotal', 'total'));
    }

    public function checkout()
    {
        // Get cart from session
        $cart = session()->get('cart', []);
        
        // Check if cart is empty
        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your cart is empty!');
        }
        
        // Verify stock availability before showing checkout
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->quantity < $item['quantity']) {
                return redirect()->route('cart')->with('error', "Not enough stock available for {$item['name']}. Only {$product->quantity} left.");
            }
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $total = $subtotal; // You can add shipping/taxes here if needed
        
        return view('buyer.checkout', compact('cart', 'subtotal', 'total'));
    }

    public function placeOrder(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        // Get cart from session
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Calculate total and verify stock availability
        $total = 0;
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product || $product->quantity < $item['quantity']) {
                return redirect()->route('cart')->with('error', "Not enough stock available for {$item['name']}. Only {$product->quantity} left.");
            }
            $total += $item['price'] * $item['quantity'];
        }

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'postal_code' => $request->postal_code,
            'payment_method' => $request->payment_method,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Create order items and update product quantities
        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Deduct the ordered quantity from the product's stock
            $product = Product::find($id);
            $product->quantity -= $item['quantity'];
            $product->save();
        }

        // Clear the cart
        session()->forget('cart');

        // Redirect to thank you page
        return redirect()->route('buyer.thankyou')->with('success', 'Order placed successfully!');
    }

    public function thankyou()
    {
        return view('buyer.thankyou');
    }
}