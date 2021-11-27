<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Http\Requests\TransactionRequest;
use App\Services\TransactionAppService;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function createTransaction(TransactionRequest $request)
    {

        $transactionValidate = (new TransactionAppService)($request);

        return response()->json($transactionValidate, 200);

    }

}
