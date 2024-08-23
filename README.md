# LARAVEL API OPEN FOOD FACTS

>  This is a challenge by [Coodesh](https://coodesh.com/)

## Introdução

Este projeto é um desafio de desenvolvimento de uma API REST para utilizar dados do projeto Open Food Facts. O objetivo é fornecer suporte à equipe de nutricionistas da empresa Fitness Foods LC para revisar informações nutricionais dos alimentos. 

A API permite a importação e gerenciamento de dados alimentícios com suporte a operações CRUD, e utiliza Docker para facilitar o ambiente de desenvolvimento.

## Tecnologias Utilizadas

- **Linguagem**: PHP
- **Framework**: Laravel
- **Banco de Dados**: SQLite (para testes) / MySQL
- **Containerização**: Docker
- **Testes**: PHPUnit
- **Automação**: Laravel

## Pré-requisitos

Certifique-se de ter os seguintes softwares instalados:

- Docker
- Docker Compose

## Instalação e Uso

1. **Clonar o Repositório**

    ```bash
    git clone https://github.com/alancapellao/laravel-api-open-food.git
    cd laravel-api-open-food
    ```

2. **Configurar o Ambiente**

    Crie um arquivo `.env` a partir do arquivo `.env.example` e configure suas variáveis de ambiente.

    ```bash
    cp .env.example .env
    ```

3. **Configurar o Docker**

    - **Construir e iniciar os containers**

      ```bash
      docker-compose up --build
      ```

    - **Executar as migrations e seeders**

      ```bash
      docker-compose exec open-food-api-api php artisan migrate --seed
      ```

4. **Configuração da Automação**

    - **Executar tarefas agendadas do Laravel**

    ```bash
    docker-compose exec open-food-api-api php artisan schedule:work
    ```

5. **Executar Comandos Cron Manualmente**

    Para executar o comando de cron manualmente:

    ```bash
    docker-compose exec open-food-api-api php artisan app:import-openfood
    ```

 6. **Executar Testes**

    Para executar os testes rode o comando:

    ```bash
    docker-compose exec open-food-api-api ./vendor/bin/phpunit
    ```

## Endpoints da API

- **GET /**: Detalhes da API, status da conexão com o banco de dados, horário da última execução do CRON, tempo online e uso de memória.
- **PUT /products/{code}**: Atualiza um produto com o código fornecido.
- **DELETE /products/{code}**: Muda o status de um produto para 'trash'.
- **GET /products/{code}**: Obtém a informação de um produto específico.
- **GET /products**: Lista todos os produtos com paginação.
  Parâmetros são aceitos.
  - page: Número da página
  - per_page: Número de registros por página

