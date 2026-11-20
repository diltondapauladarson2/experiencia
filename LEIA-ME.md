# Painel Previne — Certificados, Alunos/Cursos, Estoque e Tarefas

## Instalação (Hostinger)
1. hPanel → Bases de Dados MySQL → cria a base de dados.
2. phpMyAdmin → Importar → `schema_completo.sql` (cria todas as tabelas de uma vez).
3. Edita `config.php` (DB_NAME, DB_USER, DB_PASS e SITE_URL).
4. Envia TODOS os ficheiros (incluindo `.htaccess`) para `public_html/certificados/`.
5. Abre `/certificados/setup.php` uma vez, cria o utilizador admin e **apaga o `setup.php`**.
6. Entra em `/certificados/login.php`.
7. (Opcional) `acesso-admin.html` é uma página de entrada para colocar no site, apontando para `/certificados/login.php`.

## Módulos
Painel · Certificados (código único + QR) · Alunos/Cursos (filtro por estado) · Estoque (alerta de mínimo) · Tarefas (prioridade, prazo, concluir rápido).
`verificar.php` é público de propósito (destino do QR).

## Segurança incluída
- Token CSRF em todos os formulários
- Apagar/Concluir só por POST
- Sessão httponly/samesite, regeneração no login, atraso em login falhado
- `.htaccess` bloqueia config.php, auth.php, nav.php, .sql e .md
