<!DOCTYPE html>
<html>
<head>
    <title>Teste Sistema de Arquivos</title>
</head>
<body>
    <h1>Teste Sistema de Arquivos</h1>
    <p>Localização: / (raiz)</p>
    <hr>

    <?php if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente'): ?>
    <h2>Ações de Admin</h2>
    <form action="/files/upload" method="post" enctype="multipart/form-data">
        <p>Upload de Arquivo: <input type="file" name="file"> <input type="submit" value="Upload"></p>
    </form>
    <form action="/folders/create" method="post">
        <p>Criar Pasta: <input type="text" name="folder_name" placeholder="Nome da pasta"> <input type="submit" value="Criar"></p>
    </form>
    <hr>
    <?php endif; ?>

    <h2>Pastas</h2>
    <table border="1">
        <?php foreach ($folders as $folder): ?>
            <tr>
                <td><a href="/cliente/files?folder_id=<?php echo $folder['id']; ?>"><?php echo $folder['nome']; ?></a></td>
                <td><a href="/folders/download?id=<?php echo $folder['id']; ?>">Download</a></td>
                <?php if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente'): ?>
                <td>
                    <form action="/folders/rename" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $folder['id']; ?>">
                        <input type="text" name="new_name" required>
                        <input type="submit" value="Renomear">
                    </form>
                </td>
                <td><a href="/folders/delete?id=<?php echo $folder['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Arquivos</h2>
    <table border="1">
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?php echo $file['nome']; ?>.<?php echo $file['extensao']; ?></td>
                <td><a href="/files/download?id=<?php echo $file['id']; ?>">Download</a></td>
                <?php if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente'): ?>
                <td>
                    <form action="/files/rename" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $file['id']; ?>">
                        <input type="text" name="new_name" required>
                        <input type="submit" value="Renomear">
                    </form>
                </td>
                <td><a href="/files/delete?id=<?php echo $file['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a></td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>
