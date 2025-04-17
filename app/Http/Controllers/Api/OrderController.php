<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //utk filter tgl order
        //var start_date didapatkan dari nama inputan field start_date
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        //jika tanggal mulai dan end date diisi
        if ($start_date && $end_date) {
            # code... query utk mendapatkan data order based tgl pakai where between
            $orders = Order::whereBetween('created_at', [$start_date, $end_date])->get();
        } else {
            # code... mendapatkan semua data order
            $orders = Order::all();
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function saveOrder(Request $request)
    {
        //validasi request
        $request->validate([
            'payment_amount' => 'required',
            'sub_total' => 'required',
            'tax' => 'required',
            'discount' => 'required',
            'service_charge' => 'required',
            'total' => 'required',
            'payment_method' => 'required',
            'total_item' => 'required',
            'id_kasir' => 'required',
            'nama_kasir' => 'required',
            'transaction_time' => 'required'
        ]);

        // create order (tambah data), ternyata disini belim ditambahin request payment amount
        $order = Order::create([
            'payment_amount' => $request->payment_amount,
            'payment_method' => $request->payment_method,
            'sub_total' => $request->sub_total,
            'tax' => $request->tax,
            'discount' => $request->discount,
            'service_charge' => $request->service_charge,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'total_item' => $request->total_item,
            'id_kasir' => $request->id_kasir,
            'nama_kasir' => $request->nama_kasir,
            'transaction_time' => $request->transaction_time
        ]);

        // tambah data ke tbl order item
        // foreach ($order->order_items as $item) { ini salah
        // ambilnya dari request
        foreach ($request->order_items as $item) {
            # code...
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id_product'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // menampilkan response json
        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function summary(Request $request)
    {
        //
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Order::query(); //utk dilakukan proses query

        if ($startDate && $endDate) {
            # code...
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        //melakukan perhitungan total kesulurhan payment_amount
        $totalRevenue = $query->sum('payment_amount');
        $totalDiscount = $query->sum('discount_amount');
        $totalTax = $query->sum('tax');
        $totalServiceCharge = $query->sum('service_charge');
        $totalSubtotal = $query->sum('sub_total');
        $total = $totalSubtotal - $totalDiscount - $totalTax + $totalServiceCharge;

        //tampilkan json
        return response()->json([
            'status' => 'success',
            //menampilkan data dalam bentuk array
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_discount' => $totalDiscount,
                'total_tax' => $totalTax,
                'total_subtotal' => $totalSubtotal,
                'total_service_charge' => $totalServiceCharge,
                'total' => $total
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
