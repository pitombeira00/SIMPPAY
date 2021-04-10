<?php

namespace App\Http\Requests;

use App\Rules\PayeeUser;
use App\Rules\PayerCash;
use App\Rules\TokenUser;
use App\Rules\TypeUser;
use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payer' => ['required', 'string', new TokenUser(), new TypeUser()],
            'payee' => ['required','string',new PayeeUser()],
            'value' => ['required', 'numeric', new PayerCash()]
        ];
    }

}
