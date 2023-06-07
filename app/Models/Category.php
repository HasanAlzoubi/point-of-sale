<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['name'];
    protected $guarded=[];


    public function products(){
        return $this->hasMany(Product::class);
    }
}
