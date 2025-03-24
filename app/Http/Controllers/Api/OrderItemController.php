<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = OrderItem::query();

        //bikin query db raw ambil data nama jika id produknya sama dengan id produk yg ada di tabel order_items
        $query->select('order_items', DB::raw('(SELECT name FROM products WHERE products.id = order_items.product_id) AS product_name'));

        if ($start_date && $end_date) {
            # code...
            $query->whereBetween('order_items.created_at', [$start_date, $end_date]);
        }

        $orderItems = $query->get(); //dapatin data dari query

        return response()->json([
            'status' => 'success',
            'data' => $orderItems
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function orderSales(Request $request)
    {
        //
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        //pilih order_items.product_id lalu dibandinngkan ke tabel products dan hasil total penjumlahan dari field quantity dijadikan total_quantity, dikelompokan berdasarkan product_id (tbl order_items)
        $query = OrderItem::select('order_items.product_id', DB::raw('(SELECT name FROM products WHERE products.id = order_items.product_id) AS product_name'), DB::raw('SUM(order_items.quantity) as total_quantity'))->groupBy('order_items.product_id');

        // jika startdate dan enddate ada
        if ($startDate && $endDate) {
            # code...
            $query->whereBetween(DB::raw('DATE(order_items.created_at)'), [$startDate, $endDate]);
        }

        $totalProductsSold = $query->orderBy('total_quantity', 'desc')->get();

        //menampilkan response api
        return response()->json([
            'status' => 'success',
            'data' => $totalProductsSold
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
