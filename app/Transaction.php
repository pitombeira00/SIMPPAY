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

    public function cancelTransaction(){

        $this->status = '3';
        $this->save();

        $this->payer->wallet->value += $this->value;
        $this->payer->wallet->save();

    }

    public function finishedTransaction(){

        $this->status = '2';
        $this->save();

    }

    public function payer(){

        return $this->hasOne(User::class,'id','user_payer');
    }


    public function sendValuePayer(){

        $this->payer->wallet->value -= $this->value;

        $this->payer->wallet->save();

    }
}
