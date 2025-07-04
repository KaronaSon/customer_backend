<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class OrderApiController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,cid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,pid',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $order = Order::create([
            'customer_id' => $request->customer_id,
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        $total = 0;

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal = $product->price * $item['quantity'];

            if ($product->stock < $item['quantity']) {
                return response()->json(['message' => 'Not enough stock for product: ' . $product->name], 400);
            }
            $product->stock -= $item['quantity'];
            $product->save();

            OrderItem::create([
                'order_id' => $order->oid,
                'product_id' => $product->pid,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
            $total += $subtotal;
        }
        $order->total_amount = $total;
        $order->save();

        return response()->json(['message' => 'Order placed', 'order_id' => $order->oid]);
    }

    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])->orderBy('created_at', 'desc')->get();
        return response()->json($orders, 200);
    }

    public function listByCustomer($customer_id)
    {
        $orders = Order::where('customer_id', $customer_id)->with('items.product')->get();
        return response()->json($orders, 200);
    }

    public function show($oid)
    {
        $order =
            Order::with('items.product')->find($oid);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order, 200);
    }
}