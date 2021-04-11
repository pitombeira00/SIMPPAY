<?php

namespace App\Jobs;

use App\Services\TransactionService;
use App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class TransactionAuthorization implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    protected $transaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $transactionValidate = new TransactionService($this->transaction);

        $authorizationExtern = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

        if($authorizationExtern->failed()){

            TransactionAuthorization::dispatch($this->transaction);


        } elseif ($authorizationExtern->json()['message'] == "Autorizado"){

            //Altera o Status e manda dinheiro para o recebedor...
            $this->transaction->finishedTransaction();

            $sendMenssage = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04');

            if($sendMenssage->failed()){
                TransactionMessage::dispatch($this->transaction);
            }

        }

    }
}
