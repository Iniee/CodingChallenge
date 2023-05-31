<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';

    protected $primaryKey = "ingredient_id";

    protected $fillable = ['name', 'stock in Kg', 'notification_mail'];
    
}