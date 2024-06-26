# API USER CRUD

Este é um projeto Docker utilizando Laravel.

## Pré-requisitos

Certifique-se de ter o Docker e o Docker Compose instalados na sua máquina.

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Como Rodar

1. Clone o repositório:
```bash
   git clone https://github.com/Nathanbelob/api-iso-4217
```

2. Navegue até o diretório do projeto:
```bash
   cd api-iso-4217
```

3. Copie o arquivo .env.example para o arquivo .env:
```bash
   cp .env.example .env
```

4. Construa as imagens Docker:
```bash
   docker-compose build
```

5. Inicie os containers:
```bash
   docker-compose up
```

6. Acesse o container do PHP para rodar os comandos Laravel:
```bash
   docker-compose exec app bash
```

7. Dentro do container, rode o comando:
```bash
   composer install
```

8. Dentro do container, execute as migrações do banco de dados e popule as tabelas:
```bash
   php artisan migrate
```

9. Dê permissão a pasta storage:
```bash
   chmod -R 777 storage/
```

10. Rode o seguinte comando para gerar documentação do projeto:
```bash
   php artisan l5-swagger:generate
```

## Como parar

Para parar os containers, pressione Ctrl + C no terminal onde o docker-compose up está rodando.