-- Schema do sistema de certificados Previne
-- Importa este ficheiro no phpMyAdmin (hPanel > Bases de Dados > phpMyAdmin) dentro da tua base de dados

CREATE TABLE IF NOT EXISTS certificados (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nome_aluno VARCHAR(150) NOT NULL,
  empresa VARCHAR(150) DEFAULT NULL,
  curso VARCHAR(150) NOT NULL,
  data_emissao DATE NOT NULL,
  carga_horaria VARCHAR(50) DEFAULT NULL,
  instrutor VARCHAR(150) DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilizador VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
);

-- Não insere o utilizador admin aqui. Depois de importar este schema,
-- abre setup.php UMA VEZ no browser para criar o teu utilizador e password
-- em segurança (o PHP gera o hash correto), depois apaga o setup.php.
