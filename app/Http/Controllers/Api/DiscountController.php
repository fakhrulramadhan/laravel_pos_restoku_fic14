<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //quey utk mendapatkan semua data diskon
        $discounts = Discount::all();

        return response()->json([
            'status' => 'success',
            'data' => $discounts
        ], 200);
       
    }

    /**
     * Store a newly created resource in storage.
     * utk simpan data diskon
     */
    public function store(Request $request)
    {
        //validasi request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'value' => 'required'
        ]);

        // tambah data diskon, request all sudah mengambil data semua request (di form)
        $discount = Discount::create($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $discount
        ], 201); //201 status code setelah sukses add data
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
