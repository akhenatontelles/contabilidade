<!DOCTYPE html>
<html>
<head>
    <title>Teste | Cadastrar Usuário</title>
</head>
<body>
    <h1>Cadastrar Novo Usuário</h1>
    <form action="/superadmin/users/create" method="post">
        <p>Nome: <input type="text" name="nome" required></p>
        <p>Email: <input type="email" name="email" required></p>
        <p>Senha: <input type="password" name="senha" required></p>
        <p>Tipo:
            <select name="tipo" required>
                <option value="gerente">Gerente</option>
                <option value="cliente">Cliente</option>
            </select>
        </p>
        <p>Telefone: <input type="text" name="telefone"></p>
        <p>WhatsApp: <input type="text" name="whatsapp"></p>
        <p>Empresa: <input type="text" name="nome_empresa"></p>
        <p><input type="submit" value="Cadastrar"></p>
    </form>
</body>
</html>
