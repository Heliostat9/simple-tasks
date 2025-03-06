# Запуск проекта

## Для запуска проект нужно ввести следующие команды:
- docker compose up -d
- docker compose composer install
- docker compose run artisan key:generate 
- docker compose run artisan migrate
- docker compose run artisan db:seed

## Команда для запуска Scheduler:
- docker compose run queue:work
- docker compose run artisan schedule:run