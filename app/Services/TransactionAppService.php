<?php

namespace App\Services;

use App\Jobs\TransactionMessage;
use Illuminate\Http\Request;
use App\Transaction;

class TransactionAppService{

    /**
     * Classe para realizar Transação entre usuários.
     *
     * @param Request $request
     * @return string
     */
    public function __invoke(Request $request){

        $authorization = new ApiAuthorizationTransactionService();

        $authorization->tryAuthorize();

        if($authorization->erroAuthorization()){

            return $authorization->getErroMessage();
        }

        if($authorization->isNotAuthorized()) {

            (new Transaction)->createTransactionNotAuthorized($request);

        }else{

            $transactionModel = (new Transaction)->createTransactionAuthorized($request);

//            return $transactionModel;
            TransactionMessage::dispatch($transactionModel);

        }

        return $authorization->getMessage();

    }


}
