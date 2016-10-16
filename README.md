## Назначение веток
master - стабильная ветка для релиза

dev - ветка разработки для слияния изменения разработчикв

issue-№ - ветка для конкретного issue


## Состав сборки в Vagrant
1. Ubuntu LTS 14.04
2. PHP 7.0
3. MySQL 5.7
4. Nginx
5. Yii2
6. Codeception
7. Composer
8. Git


## Развертывание

### С помощью Vagrant

1. Клонируем репозиторий и копируем файл настроек

    ```bash
    git clone https://github.com/balamyt92/InfoDesk.git
    cd InfoDesk/vagrant/config
    cp vagrant-local.example.yml vagrant-local.yml
    ```

2. Правим файл настроек vagrant-local.yml
3. Добавляем GitHub personal API token в `vagrant-local.yml`
4. Переходим в корень проекта
5. Устанавливаем плагин и запускаем Vagrant

    ```bash
    vagrant plugin install vagrant-hostmanager // если плагин еще не установлен
    vagrant up
    ```

### Без Vagrant

1. Установить MySQL >=5.7, PHP >=7.0 и Composer
2. Создать в MySQL базу данных под приложение (по умолчанию имя app_base, кодировка utf-8)
3. Сконфигурировать параметры доступа к базе даннхы в файле `config/db.php`
4. В корне проекта запустить `composer install` и дождаться окончания
5. В корне проекта запустить `./yii serve` для запуска проекта (только для режима разработки, для полноценной работы следует установить и настроить Nginx)

## Импорт данных из старой базы

1. Поместить файлы выгрузки из старой базы в папку /import
2. Перейти в корень проета и выполнить `./yii legacy-import`
    
## Запуск тестов
Создан алиас `cc` который запускает codeception
    
```bash
cd /app/tests
cc run
```