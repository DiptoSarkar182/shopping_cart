<?php

namespace App\Http\Controllers;

use App\Models\rc;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mock product data (in a real app, this might come from a database)
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 10.99],
            ['id' => 2, 'name' => 'Product 2', 'price' => 20.49],
            ['id' => 3, 'name' => 'Product 3', 'price' => 15.99],
            ['id' => 4, 'name' => 'Laptop', 'price' => 799.99],
            ['id' => 5, 'name' => 'Smartphone', 'price' => 499.50],
            ['id' => 6, 'name' => 'Headphones', 'price' => 29.99],
            ['id' => 7, 'name' => 'Wireless Mouse', 'price' => 19.75],
            ['id' => 8, 'name' => 'Keyboard', 'price' => 39.95],
            ['id' => 9, 'name' => 'Monitor', 'price' => 149.99],
            ['id' => 10, 'name' => 'Gaming Chair', 'price' => 199.00],
        ];

        $search = $request->input('search');

        // Filter products by name if a search query exists
        if ($search) {
            $products = array_filter($products, function ($product) use ($search) {
                return stripos($product['name'], $search) !== false;
            });
        }

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(rc $rc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(rc $rc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, rc $rc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(rc $rc)
    {
        //
    }
}
