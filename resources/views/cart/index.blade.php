<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .quantity-input {
            width: 50px;
            text-align: center;
        }
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
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
                <tr data-id="{{ $item['id'] }}">
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary decrease-quantity" type="button">-</button>
                            <input type="text" class="form-control quantity-input" value="{{ $item['quantity'] }}" readonly>
                            <button class="btn btn-outline-secondary increase-quantity" type="button">+</button>
                        </div>
                    </td>
                    <td class="subtotal" data-price="{{ $item['price'] }}" data-quantity="{{ $item['quantity'] }}">
                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                    </td>
                    <td>
                        <form class="remove-from-cart-form" data-id="{{ $item['id'] }}">
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

<!-- Toast Container for Notifications -->
<div class="toast-container">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Cart updated!
        </div>
    </div>
</div>

<!-- Include Bootstrap JS for Toast -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateCartSummary() {
        const subtotals = document.querySelectorAll('.subtotal');
        let originalTotal = 0;
        let totalItems = 0;

        subtotals.forEach(function (element) {
            const price = parseFloat(element.getAttribute('data-price'));
            const quantity = parseInt(element.getAttribute('data-quantity'));
            const subtotal = price * quantity;
            originalTotal += subtotal;
            totalItems += quantity;

            // Update the subtotal display
            element.textContent = '$' + subtotal.toFixed(2);
        });

        // Calculate discount (10% if 3 or more items)
        let discount = 0;
        if (totalItems >= 3) {
            discount = originalTotal * 0.10;
        }

        // Calculate final total
        const finalTotal = originalTotal - discount;

        // Update the UI
        document.getElementById('total-items').textContent = totalItems;
        document.getElementById('original-total').textContent = '$' + originalTotal.toFixed(2);
        document.getElementById('discount-amount').textContent = '$' + discount.toFixed(2);
        document.getElementById('final-total').textContent = '$' + finalTotal.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Update cart summary on page load
        updateCartSummary();

        // Handle increase quantity
        document.querySelectorAll('.increase-quantity').forEach(function (button) {
            button.addEventListener('click', function () {
                const row = button.closest('tr');
                const productId = row.getAttribute('data-id');
                const quantityInput = row.querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);

                // Send AJAX request to increase quantity
                fetch('{{ route('cart.increase') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id: productId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            quantity++;
                            quantityInput.value = quantity;
                            row.querySelector('.subtotal').setAttribute('data-quantity', quantity);
                            updateCartSummary();

                            // Show toast notification
                            const toastElement = document.getElementById('toast');
                            const toast = new bootstrap.Toast(toastElement);
                            toastElement.querySelector('.toast-body').textContent = 'Quantity increased!';
                            toast.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update quantity.');
                    });
            });
        });

        // Handle decrease quantity
        document.querySelectorAll('.decrease-quantity').forEach(function (button) {
            button.addEventListener('click', function () {
                const row = button.closest('tr');
                const productId = row.getAttribute('data-id');
                const quantityInput = row.querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);

                if (quantity <= 1) {
                    // Optionally remove the item if quantity reaches 0
                    return;
                }

                // Send AJAX request to decrease quantity
                fetch('{{ route('cart.decrease') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id: productId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            quantity--;
                            quantityInput.value = quantity;
                            row.querySelector('.subtotal').setAttribute('data-quantity', quantity);
                            updateCartSummary();

                            // Show toast notification
                            const toastElement = document.getElementById('toast');
                            const toast = new bootstrap.Toast(toastElement);
                            toastElement.querySelector('.toast-body').textContent = 'Quantity decreased!';
                            toast.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update quantity.');
                    });
            });
        });

        // Handle remove from cart
        document.querySelectorAll('.remove-from-cart-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const formData = new FormData(form);
                const productId = form.getAttribute('data-id');

                fetch('{{ route('cart.remove') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from the table
                            form.closest('tr').remove();
                            updateCartSummary();

                            // Show toast notification
                            const toastElement = document.getElementById('toast');
                            const toast = new bootstrap.Toast(toastElement);
                            toastElement.querySelector('.toast-body').textContent = 'Product removed from cart!';
                            toast.show();

                            // If cart is empty, show empty message
                            if (document.querySelectorAll('tbody tr').length === 0) {
                                const table = document.querySelector('table');
                                const summary = document.querySelector('.cart-summary');
                                table.remove();
                                summary.remove();
                                document.querySelector('.container').innerHTML += '<p>Your cart is empty.</p>';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to remove product.');
                    });
            });
        });
    });
</script>
</body>
</html>
