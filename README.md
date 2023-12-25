# recurring_payments_php_stripe

Кастомная интеграция платежной системы stripe для реккурентных платежей с менеджментом платежей и подписок.

## Требования

Для запуска проекта вам понадобятся:

- Docker: [Установка Docker](https://docs.docker.com/get-docker/)
- Docker Compose: [Установка Docker Compose](https://docs.docker.com/compose/install/)

## Установка и Запуск

1. Клонируйте репозиторий на ваш сервер:

    ```bash
    git clone https://github.com/F1linnn/recurring_payments_php_stripe.git
    cd recurring_payments_php_stripe
    ```
2. Установите зависимости Composer:

    ```bash
    composer install
    ```

3. Запустите контейнеры Docker:

    ```bash
    docker-compose up -d
    ```

    Эта команда запустит контейнеры в фоновом режиме.

4. Начните прослушивать webhook.php локально:
    В новом терминале регистрируем сервер в stripe, где нам будет необходимо перейти по ссылке для подтверждения.
    ```bash
   stripe login
    ```

    Затем начните прослушивать webhook.php, заменив your_port на свой порт сервера.
    ```bash
   stripe login stripe listen --forward-to localhost:your_port/webhook.php
    ```

5. Откройте приложение в браузере:

    Перейдите по адресу `http://localhost:your_port/index.html` и проведите тестовый платеж картой 4242.

## Остановка и Удаление

Чтобы остановить контейнеры:
```bash
docker-compose down
```
## Логика отмены подписки
В проекте есть файлы cancel_sub_api.php и cancel_sub_cron.php, где расписана в общем логика отмены подписки через api и с помощью использования cron.

