document.addEventListener('DOMContentLoaded', function() {
    const themeSwitch = document.getElementById('theme-switch');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme) {
        document.body.classList.add(currentTheme);
        if (currentTheme === 'dark-theme') {
            themeSwitch.checked = true;
        }
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

    listarArquivos();
});

function listarArquivos(pastaId = null) {
    fetch(`api/listar_arquivos.php?pasta_id=${pastaId || ''}`)
    .then(response => response.json())
    .then(data => {
        const tbody = document.getElementById('file-list');
        tbody.innerHTML = '';

        // Pastas primeiro
        data.pastas.forEach(pasta => {
            tbody.innerHTML += `
                <tr ondblclick="listarArquivos(${pasta.id})">
                    <td><i class="fas fa-folder me-2"></i>${pasta.nome}</td>
                    <td>-</td>
                    <td>${new Date(pasta.criado_em).toLocaleDateString()}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-share-alt me-2"></i>Compartilhar</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            `;
        });

        // Depois, arquivos
        data.arquivos.forEach(arquivo => {
            let icon = getFileIcon(arquivo.tipo);
            tbody.innerHTML += `
                <tr>
                    <td><i class="${icon} me-2"></i>${arquivo.nome_original}</td>
                    <td>${formatBytes(arquivo.tamanho)}</td>
                    <td>${new Date(arquivo.criado_em).toLocaleDateString()}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="api/download.php?id=${arquivo.id}"><i class="fas fa-download me-2"></i>Baixar</a></li>
                            <li><a class="dropdown-item" href="#" onclick="compartilharArquivo(${arquivo.id})"><i class="fas fa-share-alt me-2"></i>Compartilhar</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        `;
        });
    });
}

function getFileIcon(mimeType) {
    if (mimeType.startsWith('image/')) {
        return 'fas fa-file-image';
    }
    if (mimeType === 'application/pdf') {
        return 'fas fa-file-pdf';
    }
    return 'fas fa-file-alt';
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function compartilharArquivo(arquivoId) {
    const email = prompt("Digite o e-mail para compartilhar o arquivo:");
    if (email) {
        fetch('api/compartilhar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                arquivo_id: arquivoId,
                email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        });
    }
}
