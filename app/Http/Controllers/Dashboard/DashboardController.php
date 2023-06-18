<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;


class DashboardController extends Controller
{
    public function index()
    {

        $categories_count = Category::all()->count();
        $products_count = Product::all()->count();
        $clients_count = Client::all()->count();
        $users_count = User::whereHasRole('admin')->count();

        $sales_data=Order::select(
            DB::raw('year(created_at) as year'),
            DB::raw('month(created_at) as month'),
            DB::raw('sum(total_price) as sum')
        )->groupBy('month')->get();


        return view('dashboard.index',
            compact('categories_count', 'products_count', 'clients_count', 'users_count','sales_data'));
    }

    public function show()
    {
        return view('page_error.404');
    }

    public function error403(){
        return view('page_error.403');
    }

}
