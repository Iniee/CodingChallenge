<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\StockMail;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ActionController extends Controller
{
    public function makeOrder(Request $request)
    {

        $orderDetails = $request->all();

        // Create an order
        $order = Order::create([
            $order->customer_name = $orderDetails['customer_name']
        ]);

        // Calculate the amount consumed for each ingredient
        // Assuming the order = burger
        $cheese = 30;
        $beef = 150;
        $onion = 20;

        // Update the stock of each ingredient
        DB::transaction(function () use ($beef, $cheese, $onion) {
            $beefIngredient = Ingredient::where('name', 'Beef')->firstOrFail();
            $cheeseIngredient = Ingredient::where('name', 'Cheese')->firstOrFail();
            $onionIngredient = Ingredient::where('name', 'Onion')->firstOrFail();

            // Update the stock levels
            $beefIngredient->stock -= $beef;
            $cheeseIngredient->stock -= $cheese;
            $onionIngredient->stock -= $onion;

            $beefIngredient->save();
            $cheeseIngredient->save();
            $onionIngredient->save();

            // Check if any ingredient's stock level is below 50%
            $this->checkIngredientStock($beefIngredient);
            $this->checkIngredientStock($cheeseIngredient);
            $this->checkIngredientStock($onionIngredient);
        });

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully'
        ], 200);
    }

    private function checkIngredientStock(Ingredient $ingredient)
    {
        $threshold = 0.5; // 50%
        $currentStockLevel = $ingredient->stock;
        $maxStockLevel = $ingredient->initial_stock;

        if ($currentStockLevel <= $threshold * $maxStockLevel) {

            $email = 'Inioluwa.eng@gmail.com';
            $ingredientName = $ingredient->name;
            $message = "The stock level for the ingredient '$ingredientName' is below 50%. Please restock.";

            Mail::to($email)->send(new StockMail($ingredientName, $message));
            // Set the flag to indicate that an alert email has been sent for this ingredient
            $ingredient->needs_restock = true;
            $ingredient->save();
        }
    }
}