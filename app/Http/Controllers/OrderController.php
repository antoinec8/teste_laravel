<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::with('product')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Products::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Products::find($request->input('product_id'));
        $totalPrice = $product->price * $request->input('quantity');

        Orders::create([
            'product_id' => $product->id,
            'quantity' => $request->input('quantity'),
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully.');
    }

    public function edit(Orders $order)
    {
        $products = Products::all();
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Orders $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Products::find($request->input('product_id'));
        $totalPrice = $product->price * $request->input('quantity');

        $order->update([
            'product_id' => $product->id,
            'quantity' => $request->input('quantity'),
            'total_price' => $totalPrice,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully');
    }

    public function destroy(Orders $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully');
    }
}
