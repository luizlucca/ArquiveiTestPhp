# ArquiveiTestPhp

## Requisitos
- Php 7.3
- Composer 1.9.0

## Antes de executar a aplicação

Antes de tudo, suba os containers
```
docker run -d --name docker-postgres -e POSTGRES_DB=arquiveinfe -e POSTGRES_USER=postgres -e POSTGRES_PASSWORD=postgres -p 5432:5432 postgres:9.5
```
```
docker run -d --name docker-rabbit -p 5672:5672 -p 15672:15672 rabbitmq:3-management
```
```
docker run -d --name docker-redis -p 6379:6379 redis:alpine
```
## Dentro da pasta worker executar

```
composer install
```

Depois

```
php artisan rabbitmq:listen
```

## Dentro da pasta arquivei1

Execute

```
composer install
```
```
php artisan migrate
```

```
php artisan serve
```
