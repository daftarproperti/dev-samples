<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function store(Request $request)
    {
        $receiptInput = $request->input('receipt');
        $signature = $request->input('signature');

        if (!$receiptInput || !$signature) {
            return response()->json(['success' => false], 400);
        }

        $receipt = new Receipt();
        $receipt->receipt = $receiptInput;
        $receipt->signature = $signature;
        $receipt->save();

        return response()->json(['success' => true]);
    }
}
