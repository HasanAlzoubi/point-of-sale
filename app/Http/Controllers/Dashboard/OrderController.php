<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:orders_read')->only('index');
        $this->middleware('permission:orders_delete')->only('destroy');
    }


    public function index(Request $request)
    {

        $orders = Order::whereHas('client', function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('created_at', 'like', '%' . $request->search . '%')
                ->orWhere('total_price', 'like', '%' . $request->search . '%');
        })->paginate(5);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function products(Order $order)
    {
        $products = $order->products;
        return view('dashboard.orders._products', compact('order', 'products'));
    }

    public function destroy(Order $order)
    {

        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity,
            ]);
        }

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    }


}
