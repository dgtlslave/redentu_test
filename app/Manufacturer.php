<?php

namespace App;
use App\Product;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = ['name'];

    public function product_man() {
        return $this->hasMany(\Product::class, 'manufacturer_id', 'id');
    }
}
