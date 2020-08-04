
# Desafio Backend

A implementação visa resolver o desafio levando os requisitos informados como consideração.

## Para executar o projeto

Após baixar o projeto, siga os comandos abaixo:

```
docker-compose up -d
cp .env.example .env
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate:refresh --seed
```

## Testes

```
docker-compose exec app vendor/bin/phpunit
```

## Endpoint

```
(post) http://localhost/api/transaction
```

## Swagger

Após rodar o projeto, acessar o endereço:

```
http://localhost/swagger
```

## Melhorias de arquitetura

1. Utilização de cache (Redis) para agilizar a consulta de saldos
2. Utilização de serviço de mensageria (RabbitMQ, por exemplo) para as transações
3. Modelagem de uma carteira para armazenar métodos de pagamento
4. Log de todas as interações

## Modelo

Modelo de dados básico necessário para resolução da arquitetura proposta

![alt text](https://github.com/zepaduajr/pp-backend/blob/master/modelo.png?raw=true)