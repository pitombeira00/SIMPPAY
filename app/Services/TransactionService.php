<?php

namespace App\Services;

use App\Jobs\TransactionAuthorization;
use App\Jobs\TransactionMessage;
use App\Transaction;
use Illuminate\Support\Facades\Http;

class TransactionService {

    const url_validation = 'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6';
    const url_message = 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Create in Queue case Validate is off
     * @return array
     */
    public function sendValidate(){

        $authorizationExtern = Http::get(self::url_validation);

        if($authorizationExtern->failed()){

            TransactionAuthorization::dispatch($this->transaction);

            return [
                'status' => 'Success',
                'data' => ['status' => $this->transaction->status],
                'message' => 'Transacao pendente, aguardando autorizador.'
            ];

        } elseif ($authorizationExtern->json()['message'] == "Autorizado"){

            $this->transaction->finishedTransaction();

            $sendMessage = Http::get(self::url_message);

            if($sendMessage->failed()){

                TransactionMessage::dispatch($this->transaction);

                return [
                    'status' => 'Success',
                    'data' => ['status' => $this->transaction->status],
                    'message' => 'Transacao finalizada, pendente envio de mensagem'
                ];

            } elseif ($sendMessage->json()['message'] == "Enviado"){

                return [
                    'status' => 'Success',
                    'data' => ['status' => $this->transaction->status],
                    'message' => 'Transacao finalizada com sucesso'
                ];

            }


        } else {

            $this->transaction->cancelTransaction();
            return [
                'status' => 'Error',
                'data' => ['status' => $this->transaction->status],
                'message' => 'Transacao nao autorizada.'
            ];
        }

    }

    /**
     * Try send Validate in Job for Queue
     * @return array
     */
    public function reSendValidate(){

        $authorizationExtern = Http::get(self::url_validation);

        if($authorizationExtern->failed()){

            TransactionAuthorization::dispatch($this->transaction);

        } elseif ($authorizationExtern->json()['message'] == "Autorizado"){

            $this->transaction->finishedTransaction();

            $sendMessage = Http::get(self::url_message);

            if($sendMessage->failed()){

                TransactionMessage::dispatch($this->transaction);


            } elseif ($sendMessage->json()['message'] == "Enviado"){


            }


        } else {

            $this->transaction->cancelTransaction();
            return [
                'status' => 'Error',
                'data' => NULL,
                'message' => 'Transacao nao autorizada.'
            ];
        }
    }

}
