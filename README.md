# Zenhack

Um complemento para fóruns baseados na ferramenta do zendesk.

Essa ferramenta tem o propósito de listar todos posts não respondido por um grupo de operadores.

## Instalação

- Baixe o projeto usando o git `git clone https://github.com/Sena/zenhack.git` (ou o botão de download do githug).
- Insira os dados de banco de dados em `/application/config/database.php`;
- Insira a URL da sua aplicação na variável `$config['base_url']` dentro do arquivo `/application/config/config.php`;
- Insira o subdominio do seu fórum na linha 55 do arquivo `/application/controllers/Welcome.php`;
- Acesse na URL a página `migrate` para a criação das tabelas no banco de dados. 