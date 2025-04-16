<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ArtisanController extends Controller
{
    public function dashboard(){
    $productCount = Product::where('user_id', auth()->id())->count();
    
    $orderCount = Order::whereHas('items.product', function($query) {
        $query->where('user_id', auth()->id());
    })->count();
    
    $pendingOrderCount = Order::whereHas('items.product', function($query) {
        $query->where('user_id', auth()->id());
    })->where('status', 'pending')->count();
    
    $confirmedOrderCount = Order::whereHas('items.product', function($query) {
        $query->where('user_id', auth()->id());
    })->where('status', 'confirmed')->count();
    
    $cancelledOrderCount = Order::whereHas('items.product', function($query) {
        $query->where('user_id', auth()->id());
    })->where('status', 'cancelled')->count();
    
    $totalRevenue = Order::whereHas('items.product', function($query) {
        $query->where('user_id', auth()->id());
    })->where('status', '!=', 'cancelled')->sum('total');
    
    return view('artisan.dashboard', compact(
        'productCount',
        'orderCount',
        'pendingOrderCount',
        'confirmedOrderCount',
        'cancelledOrderCount',
        'totalRevenue'
    ));
    }

    public function productlist(){
        $products = Product::where('user_id', auth()->id())
            ->oldest()
            ->paginate(10);
        $categories = auth()->user()->categories()->get();
        return view('artisan.productlist', compact('products', 'categories'));
    }

    public function show(Product $product){
        return view('artisan.product_view', compact('product'));
    }

    public function create(){
    $categories = Category::all();
    return view('artisan.create', compact('categories'));
    }
    

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $imagePath = $request->file('image')->store('images', 'public');
            $imageName = basename($imagePath);

            Product::create([
                'user_id' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image' => $imageName,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('artisan.productlist')
                ->with('success', 'Product added successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product){
        if ($product->user_id !== auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthorized action.'], 403);
            }
            abort(403, 'Unauthorized action.');
        }
    
        if (request()->ajax()) {
            return response()->json([
                'product' => $product,
                'image_url' => asset('storage/images/' . $product->image)
            ]);
        }
        $categories = auth()->user()->categories()->get();
        return view('artisan.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            $imageName = $product->image;
            
            if ($request->hasFile('image')) {
                if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
                    Storage::disk('public')->delete('images/' . $product->image);
                }
                
                $imagePath = $request->file('image')->store('images', 'public');
                $imageName = basename($imagePath);
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'image' => $imageName,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('artisan.productlist')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            if ($product->image && Storage::disk('public')->exists('images/' . $product->image)) {
                Storage::disk('public')->delete('images/' . $product->image);
            }
            
            $product->delete();
            
            return redirect()->route('artisan.productlist')
                ->with('success', 'Product deleted successfully.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function customers(){
    // Get unique customers who have ordered the artisan's products
    $customers = User::whereHas('orders.items.product', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->withCount(['orders' => function($query) {
            $query->whereHas('items.product', function($q) {
                $q->where('user_id', auth()->id());
            });
        }])
        ->with(['orders' => function($query) {
            $query->whereHas('items.product', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['items' => function($query) {
                $query->whereHas('product', function($q) {
                    $q->where('user_id', auth()->id());
                });
            }])
            ->orderBy('created_at', 'desc');
        }])
        ->paginate(10);

    return view('artisan.customers.index', compact('customers'));
    }

    public function index(Request $request){
        // Get only orders that contain products belonging to the authenticated artisan
        $orders = Order::whereHas('items.product', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['items.product' => function($query) {
                $query->where('user_id', auth()->id());
            }])
            ->paginate($request->input('per_page', 10)); 
            
        return view('artisan.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order){
    $isValidOrder = $order->items()
        ->whereHas('product', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->exists();

    if (!$isValidOrder) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'status' => 'required|in:pending,confirmed,completed,cancelled',
    ]);

    try {
        $order->update(['status' => $request->status]);
        $order->items()->update(['status' => $request->status]);

        $buyer = $order->user;
        
        // Get product names for the notification message
        $productNames = $order->items()
            ->with('product')
            ->get()
            ->pluck('product.name')
            ->implode(', ');

        // Create appropriate notification based on status
        if ($request->status === 'confirmed') {
            $buyer->notifications()->create([
                'type' => 'order_confirmed',
                'notifiable_type' => Order::class,
                'notifiable_id' => $order->id,
                'data' => json_encode([
                    'message' => 'Your order containing "'.$productNames.' has been confirmed.',
                    'order_id' => $order->id,
                    'products' => $productNames,
                ]),
            ]);
        } elseif ($request->status === 'cancelled') {
            $buyer->notifications()->create([
                'type' => 'order_cancelled',
                'notifiable_type' => Order::class,
                'notifiable_id' => $order->id,
                'data' => json_encode([
                    'message' => 'Your order containing "'.$productNames.' has been cancelled.',
                    'order_id' => $order->id,
                    'products' => $productNames,
                    'reason' => $request->reason ?? 'No reason provided',
                ]),
            ]);

            // Restore product quantities if cancelled
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->quantity += $item->quantity;
                $product->save();
            }
        }

        return redirect()->route('artisan.orders.index')
            ->with('success', 'Order status updated successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error updating order status: ' . $e->getMessage());
    }
    }

    public function destroyOrder(Order $order)
    {
    // Verify the order contains the artisan's products
    $isValidOrder = $order->items()
        ->whereHas('product', function($query) {
            $query->where('user_id', auth()->id());
        })
        ->exists();

    if (!$isValidOrder) {
        abort(403, 'Unauthorized action.');
    }

    try {
        // Delete only the items that belong to this artisan
        $order->items()
            ->whereHas('product', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->delete();

        // If no items remain, delete the entire order
        if ($order->items()->count() === 0) {
            $order->delete();
        }

        return redirect()->route('artisan.orders.index')
            ->with('success', 'Order deleted successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Error deleting order: ' . $e->getMessage());
    }
    }
}