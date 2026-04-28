<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('orders.index', ['orders' => $orders]);
    }

    public function create()
    {
        $products = Product::orderBy('name', 'asc')->get(); 
        $users = User::all();

        return view('orders.create', [
            'products' => $products,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $products = json_decode($request->products, true);

        if (!$products || count($products) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Select at least one product'
            ]);
        }

        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'status' => 'pending'
            ]);

            foreach ($products as $item) {

                $product = Product::find($item['id']);

                if ($product && $product->stock >= $item['qty']) {

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['qty'],
                        'price' => $product->price
                    ]);

    
                    $product->decrement('stock', $item['qty']);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        return view('orders.show', ['order' => $order]);
    }



    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $status = $request->input('status', 'completed');

        $order->status = $status;
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated',
            'new_status' => $order->status
        ]);
    }


    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }

        $order->delete();

        return response()->json([
            'status' => true
        ]);
    }
}