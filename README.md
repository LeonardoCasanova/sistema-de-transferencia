    <h1>Sistema de Transferência de Dinheiro</h1>
    <p>Este é um sistema de transferência de dinheiro entre usuários, desenvolvido com o framework Laravel.</p>

    <h2>Funcionalidades</h2>
    <ul>
        <li>Permite que usuários transfiram dinheiro entre si.</li>
        <li>Validações de saldo suficiente, tipo de usuário e autorização externa.</li>
        <li>Transações seguras com rollback em caso de erro.</li>
    </ul>

    <h2>Instalação</h2>
    <ol>
        <li><strong>Clonar o repositório:</strong><br>
            <code>https://github.com/LeonardoCasanova/sistema-de-transferencia.git</code></li>
        <li><strong>Instalar as dependências do Composer:</strong><br>
            <code>cd nome-do-repositorio</code><br>
            <code>composer install</code></li>
        <li><strong>Configurar o arquivo de ambiente:</strong><br>
            Renomeie o arquivo <code>.env.example</code> para <code>.env</code>.<br>
            Configure as variáveis de ambiente, como conexão com o banco de dados.</li>
        <li><strong>Subir o ambiente docker:</strong><br>
            <code>docker-compose up -d</code></li>    
        <li><strong>Gerar a chave de aplicação:</strong><br>
            <code>php artisan key:generate</code></li>
        <li><strong>Executar as migrações do banco de dados:</strong><br>
            <code>docker-compose exec app php artisan migrate</code></li>
        <li><strong>Iniciar o servidor de desenvolvimento:</strong><br>
            <code>php artisan serve</code></li>
    </ol>

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