<!DOCTYPE html>
<html>
<head>
    <title>Teste SuperAdmin</title>
</head>
<body>
    <h1>Teste SuperAdmin</h1>
    <a href="/superadmin/users/create">Cadastrar Novo Usuário</a>
    <hr>
    <h2>Usuários</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['nome']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['tipo']; ?></td>
                    <td><?php echo $user['status']; ?></td>
                    <td>
                        <a href="/superadmin/users/delete?id=<?php echo $user['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a> |
                        <a href="/superadmin/users/toggle-block?id=<?php echo $user['id']; ?>">Mudar Status</a> |
                        <a href="/superadmin/users/reset-password?id=<?php echo $user['id']; ?>" onclick="return confirm('Isso irá gerar e salvar uma nova senha. Deseja continuar?');">Resetar Senha</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
