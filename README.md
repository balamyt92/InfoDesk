## Назначение веток
master - стабильная ветка для релиза

dev - ветка разработки для слияния изменения разработчикв

issue-№ - ветка для конкретного issue


## Состав сборки
1. Ubuntu LTS 14.04
2. PHP 7.0
3. MySQL 5.7
4. Nginx
5. Yii2
6. Codeception
7. Composer
8. Git


## Развертывание

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
    
## Запуск тестов
Создан алиас `cc` который запускает codeception
    
```bash
cd /app/tests
cc run
```