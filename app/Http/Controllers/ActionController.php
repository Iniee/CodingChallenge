<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\StockMail;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ActionController extends Controller
{
    public function makeOrder(Request $request)
    {
        $orderDetails = $request->all();

        $quantity = $orderDetails['quantity'];

        $beef = (150 / 1000) * $quantity;
        $cheese = (30 / 1000) * $quantity;
        $onion = (20 / 1000) * $quantity;

        $ingredients = [
            1 => $beef,
            2 => $cheese,
            3 => $onion
        ];

        $ingredientsJson = json_encode($ingredients);

        $order = Order::create([
            'customer_name' => $orderDetails['customer_name'],
            'product_id' => $orderDetails['product_id'],
            'quantity' => $quantity,
            'ingredients' => $ingredientsJson,
        ]);

        foreach ($ingredients as $ingredientId => $stock) {
            $ingredient = Ingredient::find($ingredientId);

            if ($ingredient) {
                $ingredient->stock_Kg -= $stock;
                $ingredient->save();

                if ($ingredient->stock_Kg < (0.5 * $ingredient->stock_Kg) && !$ingredient->restock) {
                    $email = 'Inioluwa.eng@gmail.com';
                    $ingredientName = $ingredient->name;
                    $mail = "The stock level for the ingredient '$ingredientName' is below 50%. Please restock.";

                    Mail::to($email)->send(new StockMail($ingredientName, $mail));
                    $ingredient->notification_mail = true;
                    $ingredient->save();
                }
            }
        }
        
        return response()->json(['message' => 'Order placed successfully', 'product' => [
            'product_id' => $order->product_id,
            'quantity' => $order->quantity
        ]]);
    }
}