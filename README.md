# Crew Master 4

## Installation steps:

Если вы пользователь Windows, убедитесь, что вы запускаете проект под WSL, а также что проект разположен в WSL-директории.

<code>[How to access files, stored from WSl, with PHP storm?](https://stackoverflow.com/questions/71284111/how-to-access-files-created-by-laravel-sail-with-phpstorm)
</code>


1. Убедитесь, что ваш юзер добавлен в **docker** группу:

        sudo usermod -a -G docker $USER

2. Запустите команду (Это команда использует небольшой докер-контейнер чтобы установить зависимости приложения): 

        docker run --rm \
            -u "$(id -u):$(id -g)" \
            -v "$(pwd):/var/www/html" \
            -w /var/www/html \
            laravelsail/php81-composer:latest \
            composer install --ignore-platform-reqs

3. Добавьте элиас для команды **sail** в конфиг bash:

        alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

   (*) To make sure this alias is always available, you may add this to your shell configuration file in your home directory, such as ~/.zshrc or ~/.bashrc, and then restart your shell.


4. Запустите Laravel-приложение:
        
        sail up
    или

        sail up -d


7. Запустите миграции и сидеры:
        
        sail artisan migrate --seed

        sail artisan queue:start database


# Тестовое задание для front-end разработчика

1. После успешного выполнения предыдущих шагов, ознакомьтесь с [документацией API](http://localhost/api/docs). Приложение представляет собой шаблон модуля для отдела кадров крюинговой компании.
2. Реализуйте страницы: 
 - мой профиль - страница с отображением всей доступной информации залогиненого сотрудника и возможностью сменить пароль
 - список всех сотрудников (с пагинацией). На этой странице реализуйте поиск по имени/фамилии/email/должности. Также реализуйте выбор: показать всех, показать только моряков (is_seaman), показать только офисных сотрудников (is_office_employee).
 - страница сотрудника с отображением информации о нем.
 - реализуйте также возможность создания/удаления/редактирования сотрудника.
 - создайте дошборд с диаграммой, отобращающей соотношение следущих типов сотрудников: моряк, офисный сотрудник, офисный сотрудник-моряк.
 - также создайте страницы авторизации, регистрации, восстановления пароля (забыл пароль).
3. Результат присылайте в виде ссылки на github. В readme опишите инструкцию по запуску.
