<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemApiController extends Controller
{
    public function index($order_id)
    {
        $items =
            OrderItem::where('order_id', $order_id)->with('product')->get();
        return response()->json($items, 200);
    }

    public function store(Request $request, $order_id)
{
    $request->validate([
        'product_id' => 'required|exists:products,pid',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::find($request->product_id);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Check stock before adding
    if ($product->stock < $request->quantity) {
        return response()->json(['message' => 'Not enough stock for product: ' . $product->name], 400);
    }

    // Decrease stock
    $product->stock -= $request->quantity;
    $product->save();

    $orderItem = OrderItem::create([
        'order_id' => $order_id,
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'price' => $product->price,
    ]);

    return response()->json(['message' => 'Order item added', 'data' => $orderItem], 201);
}

    public function update(Request $request, $order_id, $item_id)
    {
        $orderItem = OrderItem::where('order_id', $order_id)->where('id', $item_id)->first();
        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }

        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
        ]);

        if ($request->has('quantity')) {
            $orderItem->quantity = $request->quantity;
        }
        $orderItem->save();

        return response()->json(['message' => 'Order item updated', 'data' => $orderItem], 200);
    }

    public function destroy($order_id, $item_id)
    {
        $orderItem = OrderItem::where('order_id', $order_id)->where('id', $item_id)->first();
        if (!$orderItem) {
            return response()->json(['message' => 'Order item not found'], 404);
        }
        $orderItem->delete();
        return response()->json(['message' => 'Order item deleted'], 200);
    }
}