<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = "product_id";

    protected $fillable = ['name'];



    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}