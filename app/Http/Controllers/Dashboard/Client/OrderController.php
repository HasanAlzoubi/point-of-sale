<?php

namespace App\Http\Controllers\Dashboard\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function create(Client $client)
    {
        $categories = Category::with('products')->get();
        $orders=$client->orders()->paginate(5);

        return view('dashboard.clients.orders.create', compact('client', 'categories','orders'));
    }

    public function store(Request $request, Client $client)
    {
        $data = $request->all();

        foreach ($data['products'] as $product_id => $product) {
            $Product = Product::FindOrFail($product_id);
            $Product->update([
                'stock' => $Product->stock - $product['quantity'],
            ]);
        }

        $order = $client->orders()->create([
            'total_price' => $data['total_price'],
        ]);
        $order->products()->attach($data['products']);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.clients.index');
    }

    public function edit(Client $client, Order $order)
    {
        $categories = Category::with('products')->get();
        return view('dashboard.clients.orders.edit',
            compact('client', 'order', 'categories'));
    }

    public function update(Request $request, Client $client, Order $order)
    {
        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity,
            ]);
        }
        $order->delete();

        $data = $request->all();
        foreach ($data['products'] as $product_id => $product) {
            $Product = Product::FindOrFail($product_id);
            $Product->update([
                'stock' => $Product->stock - $product['quantity'],
            ]);
        }

        $order = $client->orders()->create([
            'total_price' => $data['total_price'],
        ]);
        $order->products()->attach($data['products']);

        session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.orders.index');
    }

}
