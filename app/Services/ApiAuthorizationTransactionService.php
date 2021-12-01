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
            //TODO Colocar o message em caixa baixa para ser mais assertivo na comparação.
            $this->authorize = $retorno['message'] === config('transaction.return.ApiAuthorizationMessage') ? true : false;
            if($this->authorize){
             $this->message = 'Transacao finalizada com sucesso';
            }else{
                //TODO Caso não seja authorizado, em vez de enviar a mensgem da API, enviar uma customizada.
                $this->message = $retorno['message'];
            }

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
