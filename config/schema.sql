CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('superadmin', 'gerente', 'cliente') NOT NULL,
    telefone VARCHAR(20),
    whatsapp VARCHAR(20),
    nome_empresa VARCHAR(255),
    status ENUM('ativo', 'bloqueado') NOT NULL DEFAULT 'ativo',
    manager_id INT,
    theme ENUM('light', 'dark') NOT NULL DEFAULT 'light',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manager_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    parent_id INT,
    user_id INT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES folders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    extensao VARCHAR(10) NOT NULL,
    folder_id INT,
    user_id INT,
    path VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE shared_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_id INT,
    folder_id INT,
    tipo ENUM('file', 'folder') NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expiracao_opcional TIMESTAMP,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (file_id) REFERENCES files(id) ON DELETE CASCADE,
    FOREIGN KEY (folder_id) REFERENCES folders(id) ON DELETE CASCADE
);
