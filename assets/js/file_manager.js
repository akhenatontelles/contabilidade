let currentFolderId = null;

// Dropzone Configuration
Dropzone.autoDiscover = false;
const myDropzone = new Dropzone("#main-content-dropzone", {
    url: "api/upload.php",
    paramName: "file",
    autoProcessQueue: true,
    clickable: "#upload-btn", // Use o botão de upload para abrir o seletor de arquivos
    previewsContainer: false, // Não mostrar previews, a tabela será atualizada
    dragover: function(event) {
        document.getElementById('main-content-dropzone').classList.add('drag-over');
    },
    dragleave: function(event) {
        document.getElementById('main-content-dropzone').classList.remove('drag-over');
    },
    drop: function(event) {
        document.getElementById('main-content-dropzone').classList.remove('drag-over');
    },
    sending: function(file, xhr, formData) {
        formData.append("cliente_id", CLIENT_ID);
        formData.append("pasta_id", currentFolderId); // Enviar a pasta atual
    },
    success: function(file, response) {
        console.log(response);
        listFiles(currentFolderId); // Recarregar a lista de arquivos
    },
    error: function(file, response) {
        alert("Erro no upload: " + response);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    listFiles();

    // Theme switcher
    const themeSwitch = document.getElementById('theme-switch');
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        document.body.classList.add(currentTheme);
        if (currentTheme === 'dark-theme') themeSwitch.checked = true;
    }
    themeSwitch.addEventListener('change', function(event) {
        if (event.target.checked) {
            document.body.classList.add('dark-theme');
            localStorage.setItem('theme', 'dark-theme');
        } else {
            document.body.classList.remove('dark-theme');
            localStorage.setItem('theme', 'light-theme');
        }
    });

    // Folder Upload
    const folderUploadInput = document.getElementById('folder-upload-input');
    document.getElementById('upload-folder-btn').addEventListener('click', function(e) {
        e.preventDefault();
        folderUploadInput.click();
    });
    folderUploadInput.addEventListener('change', function(e) {
        for (const file of e.target.files) {
            myDropzone.addFile(file);
        }
    });

    // New Folder Button
    document.getElementById('new-folder-btn').addEventListener('click', function() {
        const folderName = prompt("Digite o nome da nova pasta:");
        if (folderName) {
            fetch('api/criar_pasta.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    nome: folderName,
                    cliente_id: CLIENT_ID,
                    pai_id: currentFolderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    listFiles(currentFolderId);
                } else {
                    alert(data.message);
                }
            });
        }
    });

    // Search functionality
    document.getElementById('search-input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll("#file-list tr");
        rows.forEach(row => {
            const fileName = row.querySelector('td:first-child').textContent.toLowerCase();
            if (fileName.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});

function listFiles(folderId = null) {
    currentFolderId = folderId;
    // A função para listar arquivos (similar à do cliente.js) será implementada aqui.
    // Por enquanto, vamos deixar um placeholder.
    const tbody = document.getElementById('file-list');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center">Carregando...</td></tr>';

    fetch(`api/listar_arquivos_gestor.php?cliente_id=${CLIENT_ID}&pasta_id=${folderId || ''}`)
    .then(response => response.json())
    .then(data => {
        tbody.innerHTML = '';
        // Render folders
        data.pastas.forEach(pasta => {
            tbody.innerHTML += `
                <tr ondblclick="listFiles(${pasta.id})">
                    <td><i class="fas fa-folder me-2"></i>${pasta.nome}</td>
                    <td>-</td>
                    <td>${new Date(pasta.criado_em).toLocaleDateString()}</td>
                    <td><!-- Ações --></td>
                </tr>
            `;
        });
        // Render files
        data.arquivos.forEach(arquivo => {
            tbody.innerHTML += `
                <tr>
                    <td><i class="fas fa-file-alt me-2"></i>${arquivo.nome_original}</td>
                    <td>${formatBytes(arquivo.tamanho)}</td>
                    <td>${new Date(arquivo.criado_em).toLocaleDateString()}</td>
                    <td><!-- Ações --></td>
                </tr>
            `;
        });
    })
    .catch(error => {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Erro ao carregar arquivos.</td></tr>';
        console.error('Error:', error);
    });
}

function formatBytes(bytes, decimals = 2) {
    if (!+bytes) return '0 Bytes'
    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB']
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}
