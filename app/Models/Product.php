<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['name', 'description'];
    protected $guarded = [];
    protected $appends = ['profit_percent'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class,'product_order');
    }

    public function getProfitPercentAttribute()
    {
        $profit = ($this->sell_price - $this->purchase_price);
        return number_format($profit * 100 / $this->purchase_price,2) ;
    }

}
