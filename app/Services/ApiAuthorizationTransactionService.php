<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiAuthorizationTransactionService
{

    private $error = true;
    private $authorize = false;
    private $erroMessage = '';
    private $message = '';

    public function tryAuthorize()
    {
        try {
            $retorno = $this->authorizationIsActive();
            $this->error = false;
            $this->authorize = $retorno['message'] === config('transaction.return.ApiAuthorizationMessage') ? true : false;
            $this->message = $retorno['message'];
        } catch (\Exception $e) {
            Log::error('Try AuthorizationApi', [$e->getCode(), $e->getMessage()]);
            $this->erroMessage = [
                'message' => 'No momento estamos com indisponibilidade, tente mais tarde.'
            ];
        }
        return false;
    }

    private function authorizationIsActive()
    {
        $response = Http::retry(3, 300)->get(config('services.transaction.url_validation'));

        $response->throw();

        return $response->json() ;
    }

    public function erroAuthorization()
    {
        return $this->error;
    }

    public function isNotAuthorized()
    {

        return !$this->authorize;
    }

    public function getErroMessage()
    {
        return [
            'message' => $this->erroMessage
        ];
    }

    public function getMessage()
    {
        return [
            'message' => $this->message
        ];
    }
}
