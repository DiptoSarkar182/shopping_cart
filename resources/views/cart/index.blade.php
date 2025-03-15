<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Shopping Cart</h1>
    <a href="{{ route('products.index') }}" class="btn btn-primary mb-3">Back to Products</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(empty($cart))
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Price (USD)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td class="subtotal" data-price="{{ $item['price'] }}" data-quantity="{{ $item['quantity'] }}">
                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                    </td>
                    <td>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Display Cart Summary -->
        <div class="cart-summary">
            <h3>Total Items: <span id="total-items">0</span></h3>
            <h3>Original Total: <span id="original-total">$0.00</span></h3>
            <h3>Discount (10% if 3 or more items): <span id="discount-amount">$0.00</span></h3>
            <h3>Final Total: <span id="final-total">$0.00</span></h3>
        </div>
    @endif
</div>

<script>
    function updateCartSummary() {
        // Get all subtotal elements
        const subtotals = document.querySelectorAll('.subtotal');
        let originalTotal = 0;
        let totalItems = 0;

        // Calculate total items and original total
        subtotals.forEach(function (element) {
            const price = parseFloat(element.getAttribute('data-price'));
            const quantity = parseInt(element.getAttribute('data-quantity'));
            const subtotal = price * quantity;
            originalTotal += subtotal;
            totalItems += quantity;
        });

        // Calculate discount (10% if 3 or more items)
        let discount = 0;
        if (totalItems >= 3) {
            discount = originalTotal * 0.10; // 10% discount
        }

        // Calculate final total
        const finalTotal = originalTotal - discount;

        // Update the UI
        document.getElementById('total-items').textContent = totalItems;
        document.getElementById('original-total').textContent = '$' + originalTotal.toFixed(2);
        document.getElementById('discount-amount').textContent = '$' + discount.toFixed(2);
        document.getElementById('final-total').textContent = '$' + finalTotal.toFixed(2);
    }

    // Run the function on page load
    window.onload = updateCartSummary;
</script>
</body>
</html>
