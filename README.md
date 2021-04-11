
Tabela de conteúdos
=================
<!--ts-->
   * [Sobre](#Sobre SimpPay)
   * [Tabela de Conteudo](#tabela-de-conteudo)
   * [Instalação](#instalacao)
   * [Como usar](#como-usar)
      * [Pre Requisitos](#pre-requisitos)
      * [Instalando](#clone-repositorio)
      * [Remote files](#remote-files)
      * [Multiple files](#multiple-files)
      * [Combo](#combo)
   * [Tests](#testes)
   * [Tecnologias](#tecnologias)
<!--te-->

# Sobre SimpPay

SimpPay é uma aplicação simplificada de envio e recebimento de dinheiro:


# Instalacao

## Pre requisitos:

- [Php 8.0](https://www.php.net/releases/8.0/en.php).
- [Laravel 7.0](https://laravel.com/docs/7.x).
- [Docker](https://www.docker.com)


## Clonando Repositório

```
git clone https://github.com/pitombeira00/SIMPPAY.git
```

## Baixando Pacotes Composer
```
composer install
```

## Crie o arquivo .ENV 

Copie o arquivo .env.example e deixe somente .env, nele substitua as informações do banco:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=simppay
DB_USERNAME=root
DB_PASSWORD=testedopic
```

## Gere a Key 
```
php artisan key:generate
```

## Subir os Servicos e gerar tabelas no banco

Assim que você informou o banco, host e o password no .env, agora é a hora de subir o serviço.
```
docker-compose up -d

##Gere as tabelas no banco de dados com o migrate executada diretamente no app.

docker-compose exec app php artisan migrate
```

## Incluindo Usuário E Lojista

POST /api/auth/register.
```

#HEADERS
Accept: application/json

#BODY INCLUINDO USUARIO
{
  "name": "Danilo Pitombeira",
  "email": "danilo@user.com",
  "password": "teste123",
  "password_confirmation": "teste123",
  "document": "12345678900"
}


#BODY INCLUINDO LOJISTA
#CNPJ FOI PEGO DO SITE https://www.4devs.com.br/gerador_de_cnpj
{
  "name": "Danilo Pitombeira Lojista",
  "email": "danilo@lojista.com",
  "password": "teste123",
  "password_confirmation": "teste123",
  "document": "13670495000108"
}
```

Guardar o TOKEN para realizar as Transferencias

## Realizando Transferência
```
#HEADERS
Authorization: Bearer [TOKEN]
#BODY
{
    "payer": "1",
    "payee": "2",
    "value": 10.00
}
```

## Retornos

Transferência Executada com sucesso
```
{
    "status": "Success",
    "data": {
        "status": "2"
    },
    "message": "Transacao finalizada com sucesso"
}
```

Caso o Autorizador Externo não esteja Online, a transferencia ficará pendente e entrará na fila para tentar novamente.
```
{
    "status": "Success",
    "data": {
        "status": "1"
    },
    "message": "Transacao pendente, aguardando autorizador."
}
```
OBS: Ficará pendente, porém estará executando uma fila para tentar autorizar.


## Melhorias Futuras

- Delimitar quantidade de tentavias antes de cancelar a transferência;

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
