# Teste de desenvolvimento em Yii2 Framework
# Para esse teste teremos os seguintes requisitos obrigatórios:
1. PHP 7.1
2. Composer na versão 1.10
3. Base de dados em MySQL 8
4. Usar JSON para o corpo na API

# E os seguintes requisitos desejáveis:
1. Usar Docker como base para rodar, e fornecer o Dockerfile com instalação de todas dependências para validação e teste posterior
2. Estruturar base com boas práticas
3. Usar de conceitos atuais de desenvolvimento (ao critério do desenvolvedor)
4. Subir num repositório git (github por exemplo) para compartilhar
5. Montar a base por migrations do Yii2

# Regras de negócio e funcionamento desejado:
1. Autenticação por credencial (usuário/senha) e retorno de token (Bearer sugerido)
2. Para criar um usuário, faça um comando de terminal, que recebe o login, senha e nome desejados.
3. Todas APIs (exceto a de autenticação) devem ter a validação do token fornecido ao efetuar
4. Desenvolver APIs para os seguinte
a. Autenticação
b. Cadastro de cliente básico
i.Nome
ii.CPF (com validação)
iii.Dados de endereço (CEP, Logradouro, Número, Cidade, Estado, Complemento)
iv.Foto
v.Sexo
c. Lista dos clientes
i.Usar paginação para o retorno
d. Cadastro de produto
i.Nome
ii.Preço
iii.Cliente (detentor do produto)
iv.Foto
e. Lista dos produtos
i.Retornar paginado
ii.Permitir filtrar pelo cliente

# Docker
1. Rodar o docker-compose build na raiz do projeto para instalar as dependências
2. Após compilar rodar docker-compose up -d
3. Instalar os pacotes do projeto com o comando:
3.1 Se estiver fora do bash Docker: docker exec -i php_yii composer install
3.2 Dentro do docker no terminal: composer install
4 Após instalação executar no navegador http://localhost
4.1 Se der falha de permissão executar o comando sudo chmod -R 777 /Yii

# Migrate
Rodar o comando após a instalação: php yii migrate
Irá criar as tabelas: clients, products e users
Para executar no Docker executar: docker exec -it php_yii php yii seed

# Seed
Rodar o comando após a instalação: php yii seed (comando automático)
Irá popular as tabelas com dados automaticamente: clients, products e users
Para criar um usuário através do terminal digite: php yii seed username password nome
Para executar no Docker executar: docker exec -it php_yii php yii seed daniel 123 daniel

# Collection e Environment do Postman para realizar os testes
Na raiz do projeto: Yii-Postman-Collection.json, Yii2.Postman_Environment.json
Importar os dois arquivos no Postman para realizar os testes.