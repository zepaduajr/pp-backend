
# Desafio Backend

A implementação visa resolver o desafio levando os requisitos informados como consideração.

## Para executar o projeto

Após baixar o projeto, siga os comandos abaixo:

```
composer install
cp .env.example .env
php artisan key:generate
docker-compose up -d
php artisan migrate --seed
```

## Testes

```
vendor/bin/phpunit
```

## Endpoint

```
(post) http://localhost/api/transaction
```

## Swagger
