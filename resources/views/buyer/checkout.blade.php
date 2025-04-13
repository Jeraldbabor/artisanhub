@extends("layouts.buyertmp.index")

@section("content")

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Checkout</h1>
                </div>
            </div>
            <div class="col-lg-7"></div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="border p-4 rounded" role="alert">
                    Returning customer? <a href="#">Click here</a> to login
                </div>
            </div>
        </div>
        
        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-5 mb-md-0">
                    <h2 class="h3 mb-3 text-black">Billing Details</h2>
                    <div class="p-3 p-lg-5 border bg-white">
                        <div class="form-group">
                            <label for="country" class="text-black">Country <span class="text-danger">*</span></label>
                            <select id="country" name="country" class="form-control" required>
                                <option value="">Select a country</option>    
                                <option value="Philippines">Philippines</option>    
                                <option value="United States">United States</option>    
                                <option value="Canada">Canada</option>    
                                <option value="Japan">Japan</option>    
                                <option value="Singapore">Singapore</option>    
                            </select>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="first_name" class="text-black">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="text-black">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email" class="text-black">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="address" class="text-black">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Street address" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="city" class="text-black">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-6">
                                <label for="postal_code" class="text-black">Postal Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="order_notes" class="text-black">Order Notes</label>
                            <textarea name="order_notes" id="order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border bg-white">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        @foreach($cart as $id => $item)
                                        <tr>
                                            <td>{{ $item['name'] }} <strong class="mx-2">x</strong> {{ $item['quantity'] }}</td>
                                            <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                                            <td class="text-black">₱{{ number_format($subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                            <td class="text-black font-weight-bold"><strong>₱{{ number_format($total, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0">
                                        <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" checked>
                                        <label for="bank_transfer">Direct Bank Transfer</label>
                                    </h3>
                                    <div class="mt-2">
                                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference.</p>
                                    </div>
                                </div>

                                <div class="border p-3 mb-3">
                                    <h3 class="h6 mb-0">
                                        <input type="radio" name="payment_method" value="cod" id="cod">
                                        <label for="cod">Cash on Delivery</label>
                                    </h3>
                                </div>

                                <div class="border p-3 mb-5">
                                    <h3 class="h6 mb-0">
                                        <input type="radio" name="payment_method" value="paypal" id="paypal">
                                        <label for="paypal">PayPal</label>
                                    </h3>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-black btn-lg py-3 btn-block">Place Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection