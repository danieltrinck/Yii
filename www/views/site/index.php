<?php

/** @var yii\web\View $this */

$this->title = 'Teste de desenvolvimento em Yii2 Framework';
?>
<div>

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Teste de desenvolvimento em Yii2 Framework</h1>
    </div>


        <div class="row">
            <div class="col-6" style="margin: 0 auto;">
                <h2>Para esse teste teremos os seguintes requisitos obrigatórios:</h2>
                1. PHP 7.1<br>
                2. Composer na versão 1.10<br>
                3. Base de dados em MySQL 8<br>
                4. Usar JSON para o corpo na API<br>
                <br>
                <h2>E os seguintes requisitos desejáveis: </h2>
                1. Usar Docker como base para rodar, e fornecer o Dockerfile com instalação de todas dependências para validação e teste posterior<br>
                2. Estruturar base com boas práticas<br>
                3. Usar de conceitos atuais de desenvolvimento (ao critério do desenvolvedor)<br>
                4. Subir num repositório git (github por exemplo) para compartilhar<br>
                5. Montar a base por migrations do Yii2<br>
                <br>
                <h2>Regras de negócio e funcionamento desejado:</h2>
                1. Autenticação por credencial (usuário/senha) e retorno de token (Bearer sugerido)<br>
                2. Para criar um usuário, faça um comando de terminal, que recebe o login, senha e nome
                desejados.<br>
                3. Todas APIs (exceto a de autenticação) devem ter a validação do token fornecido ao efetuar<br>
                4. Desenvolver APIs para os seguinte<br>
                <div style="margin-left:30px">
                a. Autenticação<br>
                b. Cadastro de cliente básico<br>
                <div style="margin-left:30px">
                    i.Nome<br>
                    ii.CPF (com validação)<br>
                    iii.Dados de endereço (CEP, Logradouro, Número, Cidade, Estado, Complemento)<br>
                    iv.Foto<br>
                    v.Sexo<br>
                </div>
                c. Lista dos clientes<br>
                <div style="margin-left:30px">
                    i.Usar paginação para o retorno<br>
                </div>
                d. Cadastro de produto<br>
                <div style="margin-left:30px">
                    i.Nome<br>
                    ii.Preço<br>
                    iii.Cliente (detentor do produto)<br>
                    iv.Foto<br>
                </div>
                e. Lista dos produtos<br>
                <div style="margin-left:30px">
                    i.Retornar paginado<br>
                    ii.Permitir filtrar pelo cliente<br>
                </div>
                <br>
                <h2>Migrate</h2>
                Rodar o comando após a instalação: php yii migrate<br>
                Irá criar as tabelas: clients, products e users<br>
                Para executar no Docker executar: docker exec -it docker_image_yii php yii seed<br>
                <br>
                <h2>Seed</h2>
                Rodar o comando após a instalação: php yii seed (comando automático)<br>
                Irá popular as tabelas com dados automaticamente: clients, products e users<br>
                Para criar um usuário através do terminal digite: php yii seed username password nome<br>
                Para executar no Docker executar: docker exec -it docker_image_yii php yii seed daniel 123 daniel<br>
                <br>
                <h2>Collection e Environment do Postman para realizar os testes</h2>
                Na raiz do projeto: Yii-Postman-Collection.json, Yii2.Postman_Environment.json
                </div>
                <br><br><br>
            </div>
        </div>

</div>