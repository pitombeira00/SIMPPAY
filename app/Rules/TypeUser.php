<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Remover caracteres especias
        $cpf = preg_replace('/[^0-9]/', '', auth()->user()->document);

        // Verifica se o numero de digitos informados
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'payer cannot transfer';
    }
}
