#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"


info "Update OS software and add repo for php7"
apt-get update
apt-get upgrade -y
apt-get install -y python-software-properties
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
apt-get update

info "Allocate swap for MySQL 5.6"
fallocate -l 2048M /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap defaults 0 0' >> /etc/fstab

info "Configure locales"
apt-get install -y language-pack-ru
dpkg-reconfigure locales

info "Configure timezone"
echo ${timezone} | tee /etc/timezone
dpkg-reconfigure --frontend noninteractive tzdata

info "Prepare root password for MySQL"
export DEBIAN_FRONTEND="noninteractive"
debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password password \"''\""
debconf-set-selections <<< "mysql-server-5.6 mysql-server/root_password_again password \"''\""
echo "Done!"

info "Install additional software"
apt-get install -y git php7.0 php7.0-fpm php7.0-mysql php7.0-mbstring php7.0-intl php7.0-xml php7.0-xsl php7.0-zip php7.0-curl php7.0-gd nginx mysql-server-5.6


info "Configure MySQL"
#sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
sed -i '/\[mysqld\]/a character-set-server=utf8\ncollation-server=utf8_general_ci' /etc/mysql/my.cnf
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
sed -i "s/key_buffer/key_buffer_size/" /etc/mysql/my.cnf
sed -i "s/myisam-recover/myisam-recover-options/" /etc/mysql/my.cnf

echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.0/fpm/pool.d/www.conf
sed -i 's/memory_limit = 128MB/memory_limit = -1/g' /etc/php/7.0/fpm/php.ini
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE app_base"
mysql -uroot <<< "CREATE DATABASE app_base_test"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
