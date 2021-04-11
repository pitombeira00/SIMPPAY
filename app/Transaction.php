<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'status','user_payer','user_payee','send_message'
    ];

    /**
     * Status Transaction
     * 1. -> pending;
     * 2. -> end;
     * 3. -> not authorized;
     */

    /**
     * Cancel Transaction
     */
    public function cancelTransaction(){

        $this->status = '3';
        $this->save();

        $this->payer->wallet->value += $this->value;
        $this->payer->wallet->save();

    }

    /**
     * End status Transaction
     */
    public function finishedTransaction(){

        $this->status = '2';
        $this->save();

    }

    /**
     * How Payer Id
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payer(){

        return $this->hasOne(User::class,'id','user_payer');
    }

    /**
     * Send Value for Payer
     */
    public function removeValuePayer(){

        $this->payer->wallet->value -= $this->value;

        $this->payer->wallet->save();

    }
}
