document.addEventListener('DOMContentLoaded', function() {
    listarGestores();
    listarClientesMaster();

    document.getElementById('add-gestor-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('api/adicionar_gestor.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                listarGestores();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addGestorModal'));
                modal.hide();
                this.reset();
            } else {
                alert(data.message);
            }
        });
    });
});

function listarGestores() {
    fetch('api/listar_gestores.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('gestores-lista');
        tbody.innerHTML = '';
        data.forEach(gestor => {
            tbody.innerHTML += `
                <tr>
                    <td>${gestor.nome}</td>
                    <td>${gestor.email}</td>
                    <td>${gestor.empresa}</td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-secondary"><i class="fas fa-lock"></i></button>
                    </td>
                </tr>
            `;
        });
    });
}

function listarClientesMaster() {
    fetch('api/listar_clientes.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('clientes-lista-master');
        tbody.innerHTML = '';
        data.forEach(cliente => {
            tbody.innerHTML += `
                <tr>
                    <td>${cliente.nome}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.empresa}</td>
                    <td>
                        <button class="btn btn-sm btn-info"><i class="fas fa-folder-open"></i></button>
                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-secondary"><i class="fas fa-lock"></i></button>
                    </td>
                </tr>
            `;
        });
    });
}
