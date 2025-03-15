<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .btn-added {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Product Listing</h1>
    <div class="row mb-3">
        <div class="col-md-6">
            <!-- Search Form -->
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('cart.index') }}" class="btn btn-primary">
                View Cart <span id="cart-count" class="badge bg-light text-dark">{{ session('cart', []) ? array_sum(array_column(session('cart', []), 'quantity')) : 0 }}</span>
            </a>
        </div>
    </div>

    @if(empty($products))
        <p>No products found.</p>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text">${{ number_format($product['price'], 2) }} USD</p>
                            <!-- Check if the product is in the cart -->
                            @php
                                $inCart = false;
                                $cart = session('cart', []);
                                foreach ($cart as $item) {
                                    if ($item['id'] == $product['id']) {
                                        $inCart = true;
                                        break;
                                    }
                                }
                            @endphp
                            <form class="cart-form" data-id="{{ $product['id'] }}" data-name="{{ $product['name'] }}" data-price="{{ $product['price'] }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product['id'] }}">
                                <input type="hidden" name="name" value="{{ $product['name'] }}">
                                <input type="hidden" name="price" value="{{ $product['price'] }}">
                                <button type="submit" class="btn {{ $inCart ? 'btn-danger remove-from-cart' : 'btn-success add-to-cart' }}" data-action="{{ $inCart ? 'remove' : 'add' }}">
                                    {{ $inCart ? 'Remove from Cart' : 'Add to Cart' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
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
            Product added to cart!
        </div>
    </div>
</div>

<!-- Include Bootstrap JS for Toast -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all cart forms
        const forms = document.querySelectorAll('.cart-form');

        forms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent form submission from reloading the page

                // Get product data and action (add or remove)
                const formData = new FormData(form);
                const productId = form.getAttribute('data-id');
                const productName = form.getAttribute('data-name');
                const productPrice = form.getAttribute('data-price');
                const button = form.querySelector('button');
                const action = button.getAttribute('data-action');
                const route = action === 'add' ? '{{ route('cart.add') }}' : '{{ route('cart.remove') }}';

                // Send AJAX request
                fetch(route, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        // Update the cart count
                        const cartCountElement = document.getElementById('cart-count');
                        cartCountElement.textContent = data.cartCount;

                        // Update the button state
                        if (action === 'add') {
                            button.classList.remove('btn-success', 'add-to-cart');
                            button.classList.add('btn-danger', 'remove-from-cart');
                            button.setAttribute('data-action', 'remove');
                            button.textContent = 'Remove from Cart';
                        } else {
                            button.classList.remove('btn-danger', 'remove-from-cart');
                            button.classList.add('btn-success', 'add-to-cart');
                            button.setAttribute('data-action', 'add');
                            button.textContent = 'Add to Cart';
                        }

                        // Show toast notification
                        const toastElement = document.getElementById('toast');
                        const toast = new bootstrap.Toast(toastElement);
                        toastElement.querySelector('.toast-body').textContent = action === 'add'
                            ? `${productName} added to cart!`
                            : `${productName} removed from cart!`;
                        toast.show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to update cart.');
                    });
            });
        });
    });
</script>
</body>
</html>
