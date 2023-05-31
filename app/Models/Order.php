<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = "order_id";

    protected $fillable = ['customer_name', 'product_id', 'ingredients', 'quantity' ];
     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}