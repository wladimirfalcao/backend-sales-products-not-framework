Backend - Mercado

Este é o repositório do projeto do Desafio Técnico - Mercado. O projeto consiste em um sistema de mercado desenvolvido em PHP e PostgreSQL/MSSQL Server.

Requisitos:
- PHP 7.4 ou superior
- PostgreSQL
- Composer

Configuração:

1. Clone o repositório em sua máquina local:

   git clone https://github.com/wladimirfalcao/backend-sales-products-not-framework.git

2. Navegue até o diretório do projeto:

   cd backend

3. Instale as dependências usando o Composer:

   composer install

4. Configure o banco de dados:
    - Crie um novo banco de dados no PostgreSQL ou MSSQL Server.
    - Importe o arquivo de backup do banco de dados fornecido (salesproducts.sql) usando a ferramenta de backup/restauração do seu banco de dados.
    - Edite o arquivo config.php na raiz do projeto e atualize as informações de conexão com o banco de dados.

5. Inicie o servidor PHP embutido:

   php -S localhost:8000 -t public

Funcionalidades:
- Cadastro de produtos.
- Cadastro de tipos de produtos.
- Cadastro de valores percentuais de imposto dos tipos de produtos.

Observações:
- O sistema foi desenvolvido sem o uso de frameworks como Laravel, Symfony ou CodeIgniter, mas foram utilizadas bibliotecas como Bootstrap e Material Design para facilitar o desenvolvimento da interface.
- O arquivo de backup do banco de dados está localizado na raiz do repositório com o nome salesproducts.sql.
