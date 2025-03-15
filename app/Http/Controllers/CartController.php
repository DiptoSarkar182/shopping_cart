<?php

namespace App\Http\Controllers;

use App\Models\rc;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Get the cart from session (default to empty array if not set)
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = [
            'id' => $request->input('id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'quantity' => 1, // Default quantity
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

        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }
}
