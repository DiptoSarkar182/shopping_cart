<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => 1,
        ];

        $cart = session('cart', []);

        // Check if product already exists in the cart
        $exists = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity']++;
                $exists = true;
                break;
            }
        }

        // If product doesn't exist, add it to the cart
        if (!$exists) {
            $cart[] = $product;
        }

        // Save the updated cart to session
        session(['cart' => $cart]);

        // Calculate the total cart count (sum of quantities)
        $cartCount = array_sum(array_column($cart, 'quantity'));

        // If the request is AJAX, return a JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart!',
                'cartCount' => $cartCount,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product added to cart!');
    }

    public function remove(Request $request)
    {
        $id = $request->input('id');
        $cart = session('cart', []);

        // Remove the product by ID
        $cart = array_filter($cart, function ($item) use ($id) {
            return $item['id'] != $id;
        });

        // Reindex the array and save to session
        session(['cart' => array_values($cart)]);

        // Calculate the total cart count (sum of quantities)
        $cartCount = array_sum(array_column($cart, 'quantity'));

        // If the request is AJAX, return a JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!',
                'cartCount' => $cartCount,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }

    public function increase(Request $request)
    {
        $id = $request->input('id');
        $cart = session('cart', []);

        // Find the product and increase its quantity
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                $item['quantity']++;
                break;
            }
        }

        // Save the updated cart to session
        session(['cart' => $cart]);

        // Calculate the total cart count (sum of quantities)
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Quantity increased!',
            'cartCount' => $cartCount,
        ]);
    }

    public function decrease(Request $request)
    {
        $id = $request->input('id');
        $cart = session('cart', []);

        // Find the product and decrease its quantity
        foreach ($cart as &$item) {
            if ($item['id'] == $id) {
                if ($item['quantity'] > 1) {
                    $item['quantity']--;
                } else {
                    // Optionally remove the item if quantity reaches 0
                    $cart = array_filter($cart, function ($cartItem) use ($id) {
                        return $cartItem['id'] != $id;
                    });
                    $cart = array_values($cart); // Reindex the array
                }
                break;
            }
        }

        // Save the updated cart to session
        session(['cart' => $cart]);

        // Calculate the total cart count (sum of quantities)
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Quantity decreased!',
            'cartCount' => $cartCount,
        ]);
    }
}
