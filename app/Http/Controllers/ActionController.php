<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Mail\StockMail;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ActionController extends Controller
{
    public function makeOrder(Request $request)
    {
        try {
            $request->validate([
                'products' => 'required|array',
                'products.*.product_id' => 'required|exists:products,product_id',
                'products.*.quantity' => 'required|integer',
            ]);

            $orderDetails = $request->all();

            $products = $orderDetails['products'];

            foreach ($products as $product) {
                $productId = $product['product_id'];
                $quantity = $product['quantity'];

                $product = Product::find($productId);

                if (!$product) {
                    return response()->json(['message' => 'Product not found'], 404);
                }

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
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'ingredients' => $ingredientsJson,
                ]);

                foreach ($ingredients as $ingredientId => $stock) {
                    $ingredient = Ingredient::find($ingredientId);

                    if ($ingredient) {
                        $ingredient->stock_Kg -= $stock;
                        $ingredient->save();

                        if ($ingredient->stock_Kg < (0.5 * $ingredient->initial_stock)) {
                            $email = 'Inioluwa.eng@gmail.com';
                            $ingredientName = $ingredient->name;
                            $mail = "The stock level for the ingredient '$ingredientName' is below 50%. Please restock.";

                            Mail::to($email)->send(new StockMail($ingredientName, $mail));
                            $ingredient->notification_mail = true;
                            $ingredient->save();
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'Order placed successfully',
                'product' => $product->name,
                'quantity' => $quantity
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}