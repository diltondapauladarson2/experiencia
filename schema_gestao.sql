-- Schema adicional — Gestão de Alunos/Cursos, Estoque e Tarefas
-- Importa este ficheiro no phpMyAdmin, na MESMA base de dados dos certificados
-- (Bases de Dados > phpMyAdmin > seleciona a BD > Importar > schema_gestao.sql)

CREATE TABLE IF NOT EXISTS cursos_alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  idade INT DEFAULT NULL,
  sexo ENUM('M','F') DEFAULT NULL,
  morada VARCHAR(200) DEFAULT NULL,
  proveniencia VARCHAR(150) DEFAULT NULL,
  curso VARCHAR(150) DEFAULT NULL,
  estado ENUM('nao_escrito','escrito','concluido') NOT NULL DEFAULT 'nao_escrito',
  outras_informacoes TEXT DEFAULT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS estoque (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item VARCHAR(150) NOT NULL,
  categoria VARCHAR(100) DEFAULT NULL,
  quantidade INT NOT NULL DEFAULT 0,
  unidade VARCHAR(30) DEFAULT NULL,
  quantidade_minima INT DEFAULT 0,
  notas TEXT DEFAULT NULL,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tarefas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  descricao TEXT DEFAULT NULL,
  responsavel VARCHAR(100) DEFAULT NULL,
  prazo DATE DEFAULT NULL,
  prioridade ENUM('baixa','media','alta') NOT NULL DEFAULT 'media',
  estado ENUM('pendente','em_progresso','concluida') NOT NULL DEFAULT 'pendente',
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
