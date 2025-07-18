document.addEventListener('DOMContentLoaded', function() {
    listarClientes();

    document.getElementById('add-cliente-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('api/adicionar_cliente.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                listarClientes();
                const modal = bootstrap.Modal.getInstance(document.getElementById('addClienteModal'));
                modal.hide();
                this.reset();
            } else {
                alert(data.message);
            }
        });
    });
});

function listarClientes() {
    fetch('api/listar_clientes.php')
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('clientes-lista');
        tbody.innerHTML = '';
        data.forEach(cliente => {
            tbody.innerHTML += `
                <tr>
                    <td>${cliente.nome}</td>
                    <td>${cliente.email}</td>
                    <td>${cliente.empresa}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="abrirPerfilCliente(${cliente.id})"><i class="fas fa-folder-open"></i></button>
                        <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        <button class="btn btn-sm btn-secondary"><i class="fas fa-lock"></i></button>
                    </td>
                </tr>
            `;
        });
    });
}

function abrirPerfilCliente(clienteId) {
    window.open(`file_manager.php?cliente_id=${clienteId}`, '_blank');
}

document.getElementById('btn-nova-pasta-modal').addEventListener('click', function() {
    const nomePasta = prompt("Digite o nome da nova pasta:");
    if (nomePasta) {
        fetch('api/criar_pasta.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome: nomePasta,
                cliente_id: clienteIdSelecionado,
                pai_id: pastaAtualId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                listarArquivosGestor(clienteIdSelecionado, pastaAtualId);
            } else {
                alert(data.message);
            }
        });
    }
});

function listarArquivosGestor(clienteId, pastaId = null) {
    pastaAtualId = pastaId;
    fetch(`api/listar_arquivos_gestor.php?cliente_id=${clienteId}&pasta_id=${pastaId || ''}`)
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('file-manager-gestor');
        container.innerHTML = '';
        // Aqui vai a lógica para renderizar os arquivos e pastas, similar ao cliente.js
        // Por simplicidade, vamos apenas logar os dados por enquanto.
        console.log(data);
    });
}


Dropzone.options.myAwesomeDropzone = {
    paramName: "file",
    maxFilesize: 100, // MB
    sending: function(file, xhr, formData) {
        formData.append("cliente_id", clienteIdSelecionado);
    },
    success: function(file, response) {
        console.log(response);
    }
};
