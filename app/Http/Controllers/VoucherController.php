<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers',
            'discount_amount' => 'required|numeric',
            'start_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:start_at',
        ]);

        $voucher = Voucher::create($request->all());

        return response()->json($voucher, 201);
    }

    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        // Cek apakah voucher ada
        if (!$voucher) {
            return response()->json(['message' => 'Voucher is invalid.'], 404);
        }

        // Cek masa berlaku voucher
        $now = Carbon::now();
        if ($voucher->expires_at < $now || $voucher->start_at > $now) {
            return response()->json(['message' => 'Voucher has expired or is not yet active.'], 404);
        }

        return response()->json([
            'message' => 'Voucher applied successfully',
            'discount_amount' => $voucher->discount_amount,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }

    
}
