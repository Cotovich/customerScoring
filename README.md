# Customer Scoring

> Определение оператора по номеру телефона без обращения в специализированный сервис сейчас невозможно, т.к. у пользователей есть возможность перехода с сохранением номера. Для реализации задачи без интеграции я выбрал следующие коды: Мегафон - 902, Билайн - 900, МТС - 901

## Развертывание
* `docker-compose build`
* `docker-compose up -d`
* `docker-compose exec php bash`
* `bin/console doctrine:migration:migrate`

Создание клиента: `/customer/new`\
Список клиентов: `/customer`

## Тесты
* `docker-compose exec php bash`
* `composer test`