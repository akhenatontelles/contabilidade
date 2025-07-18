<!DOCTYPE html>
<html>
<head>
    <title>Teste | Cadastrar Cliente</title>
</head>
<body>
    <h1>Cadastrar Novo Cliente</h1>
    <form action="/gerente/clients/create" method="post">
        <p>Nome: <input type="text" name="nome" required></p>
        <p>Email: <input type="email" name="email" required></p>
        <p>Senha: <input type="password" name="senha" required></p>
        <p>Telefone: <input type="text" name="telefone"></p>
        <p>WhatsApp: <input type="text" name="whatsapp"></p>
        <p>Empresa: <input type="text" name="nome_empresa"></p>
        <p><input type="submit" value="Cadastrar"></p>
    </form>
</body>
</html>
