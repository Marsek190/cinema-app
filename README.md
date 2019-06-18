## Deploy
## Бэкенд
Создать бд movies:
```mysql
CREATE DATABASE movies;
SHOW DATABASES;
```
Создать файл .env:
```bash
$ cp .env.test .env
```
И заполнить имеющиеся поля.
Настройка перед деплоем:
```bash
$ cd cinema-app
$ composer update
$ composer dump-autoload -o
$ php bin/console cache:clear
$ php bin/console doctrine:migrations:migrate
$ php bin/console server:start --port 8080
```

## Фронтэнд
```bash
$ cd cinema-app/frontend
$ npm i
```

## Запуск
```bash
$ cd cinema-app/frontend
$ npm run dev -- --port 3000
```
В случае успешной установки и запуска в браузере по адресу http://localhost:3000/movies отобразится начальная страница

