<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['pemesan', 'pesan'])->get();

        // Group orders by creation date
        $orderCounts = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartData = [
            'labels' => $orderCounts->pluck('date'),
            'counts' => $orderCounts->pluck('count'),
        ];

        $user = User::all();
        return view('admin.ordercontrol', compact('orders', 'chartData','user'));
    }


    public function create()
    {
        return view('admin.ordercontrol');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pemesan_id' => 'required|exists:users,id',
            'pesan_id' => 'required|exists:users,id|different:pemesan_id', // Harus berbeda dari pemesan_id
            'waktu' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'approve' => 'required|in:y,n',
            'batas' => 'required|date',
            'location' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);
    
        Order::create($validatedData);
    
        return redirect()->route('orderdash')->with('success', 'Order created successfully.');
    }
    
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'pemesan_id' => 'required|exists:users,id',
            'pesan_id' => 'required|exists:users,id|different:pemesan_id', // Harus berbeda dari pemesan_id
            'waktu' => 'required|date',
            'cost' => 'required|numeric|min:0',
            'approve' => 'required|in:y,n',
            'batas' => 'required|date',
            'location' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);
    
        $order->update($validatedData);
    
        return redirect()->route('orderdash')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orderdash')->with('success', 'Order deleted successfully.');
    }
}
