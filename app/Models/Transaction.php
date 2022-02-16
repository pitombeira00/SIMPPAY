<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'status','user_payer','user_payee'
    ];

    /**
     * How Payer Id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payer(){

        return $this->hasOne(User::class,'id','user_payer');
    }

    /**
     * How Payee Id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payee(){

        return $this->hasOne(User::class,'id','user_payee');
    }


    public function createTransactionNotAuthorized(Request $request){

        Transaction::create([
            'value' => $request->value,
            'user_payer' => $request->payer,
            'user_payee' => $request->payee,
            'status' => config('transaction.status.notAuthorized')
        ]);
    }

    public function createTransactionAuthorized(Request $request){

        $newTransaction = Transaction::create([
                                'value' => $request->value,
                                'user_payer' => $request->payer,
                                'user_payee' => $request->payee,
                                'status' => config('transaction.status.pending')
                            ]);

        $walletPayerActual = $newTransaction->payer->wallet->value - $request->value;
        $walletPayeeActual = $newTransaction->payee->wallet->value + $request->value;

        DB::transaction(function () use ($newTransaction,$walletPayerActual,$walletPayeeActual){

            DB::table('wallets')->where('id', $newTransaction->user_payer)->update([
                'value' => $walletPayerActual
            ]);

            DB::table('wallets')->where('id', $newTransaction->user_payee)->update([
                'value' => $walletPayeeActual
            ]);

        });

        $newTransaction->finishedTransaction();

        return $newTransaction;

    }

    /**
     * End status Transaction
     */
    private function finishedTransaction(){

        //TODO alterar para status OK
        $this->status = '2';
        $this->save();

    }
}
