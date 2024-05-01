## Sistema de Transferência de Dinheiro
Este é um sistema de transferência de dinheiro entre usuários, desenvolvido com o framework Laravel.

# Funcionalidades

* Permite que usuários transfiram dinheiro entre si.
* Validações de saldo suficiente, tipo de usuário e autorização externa.
* Transações seguras com rollback em caso de erro.
  

## Instalação

* Clonar o repositório:
            ``` https://github.com/LeonardoCasanova/sistema-de-transferencia.git```
* Instalar as dependências do Composer:
            ``` cd nome-do-repositorio ```
            ``` composer install```
* Configurar o arquivo de ambiente:
            Renomeie o arquivo ``` .env.example ``` para  ```.env```
            Configure as variáveis de ambiente, como conexão com o banco de dados.
* Subir o ambiente docker:
            ``` docker-compose up -d ```    
* Gerar a chave de aplicação:
            ``` php artisan key:generate```
* Executar as migrações do banco de dados:
            ``` docker-compose exec app php artisan migrate```
* Iniciar o servidor de desenvolvimento:
            ``` php artisan serve```

    <p>Agora você pode acessar o sistema em <a href="http://localhost">http://localhost</a>.</p>

    <h2>Consumindo a API:</h2>
    <p>Registrar um cliente:</p>
    <code>POST: http://localhost/api/register</code><br>
    <p>Payload de exemplo:</p>
    <code>{
        "name": "Joao",
        "email": "jc.teste@gmail.com",
        "cpf_cnpj" :"41127580814",
        "password": "12345678",
        "password_confirmation": "12345678",
        "type": "cliente"
    }</code><br>
    <p>Registrar um lojista:</p>
    <code>POST: http://localhost/api/register</code><br>
    <p>Payload de exemplo:</p>
    <code>{
        "name": "JC LATICINIOS LTDA",
        "email": "jc.teste@gmail.com",
        "cpf_cnpj" :"91509685000151",
        "password": "12345678",
        "password_confirmation": "12345678",
        "type": "lojista"
    }</code><br>
    <p>Fazer uma transferencia:</p>
    <code>POST: http://localhost/api/transferencia</code><br>
    <p>Payload de exemplo:</p>
    <code>{
        "value": 10.0,
        "payer": 3,
        "payee": 2
      }</code><br>