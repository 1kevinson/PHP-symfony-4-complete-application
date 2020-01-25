-- Recharger la table manquante avant de faire un chargement de fixtures
php bin/console doctrine:migrations:execute --up 20200114042747


-- Source langage is which we use in the html.twig , target langage is which is define in translation default_locale

-- debug translations
php bin/console debug:translation


-- Catalogue des traductions définies par symfony
var/cache/dev/translations/catalogue.fr.5i9nlVK.php

Pour Override un message contenu dasn une section du cataloque, il faut renseigner la source dans notre fichier de traduction et modifier la target

-- Nomenclature des fichiers de translation
domain.locale.xlf 
exemple : messages.en.xlf


-- Recuperer une variable dans le fichier .env
 email_from: '%env(MAILER_FROM)%'


-- Definir un argument glocal d'un service
App\Mailer\Mailer:
        arguments:
            $mailFrom: '%email_from%'



-- Solution 1 for migrations problem
   Comment section which have the create table


-- Solution 2 for migrations problem

1' php bin/console doctrine:database:drop --force
2' php bin/console doctrine:database:create
3' php bin/console doctrine:migration:status
4' php bin/console doctrine:migration:migrate -n       ou       php bin/console doctrine:schema:update --force


#-Composer
Public keys stored in /Users/mac/.composer

#-Composer public keys
https://composer.github.io/pubkeys.html


Launch a test :
./vendor/bin/simple-phpunit


TEST PART NOT FINISHED - 95 / 96




-- DIGITAL OCEAN START --

Create droplets -> server
connect via ssh by login/password send via mail

To connect without password : ( create RSA without password )
ssh-keygen -t rsa
/Users/mac/.ssh/id_rsa_digital_ocean

SSH KEY is use to access a server securely without enter a password alone

add the key via DO (Digital ocean)
settings -> security

or

cat ~/.ssh/id_rsa_digital_ocean.pub | ssh root@64.225.116.61 "cat >> ~/.ssh/authorized_keys"

MacOS --
~/ = /Users/{username}


To configure a ssh key automatically  we have to create a config file if not exist in ~/.ssh/config
and had this configuration
------------
IdentifyFile ~/.ssh/id_rsa   #Default Key for any connection

Host 64.225.116.61
  IdentifyFile ~/.ssh/id_rsa_digital_ocean
------------


copy gitlab RSA pub file:
scp ~/.ssh/id_rsa_gitlab root@64.225.116.61:~/.ssh/
scp ~/.ssh/id_rsa_gitlab.pub root@64.225.116.61:~/.ssh/

-- DIGITAL OCEAN END --




-- APACHE START --

Test apache2 configuration
sudo apache2ctl  configtest

edit file -> sudo vim /etc/apache2/apache2.conf
add at the very end of the file : ServerName {serverIpAdress}

rerun : sudo apache2ctl  configtest

Allow net trafic : sudo ufw allow in "Apache Full"
Check Config :  sudo ufw app info "Apache Full"

We have :
Ports:
  80,443/tcp    # Traffic is allow on this port


Create a conf file in /etc/apache2/sites-enabled  --> {file}.conf

-- APACHE END --


-- INSTALL PHP7 START --

Install php7.2 :
apt install php7.2

make sure php7.2 is enable by apache2 :
a2enmod php7.2

-- INSTALL PHP7 END --


-- INSTALL MySQL START --

sudo apt install mysql-server
user / password : root/ {nopassword}

check status of service
systemctl status mysql.service

-- INSTALL MySQL END --


---- DEPLOYMENT IN PRODUCTION ENVIRONNEMENT ----

cd /var/www :
git clone git@gitlab.com:kkouomeu/udemy-symfony-4.git micropost

create symlink for CI/CD :
ln -s /var/www/micropost_current /var/www/micropost

In micropost dir :
composer install -n --prefer-dist

Install yarn & npm on server :
sudo apt-get install -y nodejs
node -v

sudo apt-get update && sudo apt-get install yarn
yarn install && yarn encore production

sudo a2enmod rewrite
sudo service apache2 restart


************** PROBLEM mmap() failed: [12] Cannot allocate memory - solved

[best way use fallocate](https://github.com/geerlingguy/drupal-vm/issues/547#issuecomment-426530245)
 :
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile

show status  sudo swapon --show



extensions missing:
sudo apt install php-xml
sudo apt-get install php-mbstring


*******

define global env variable for system :
export DATABASE_URL=mysql://admin:admin@127.0.0.1:3306/micro-post
export APP_ENV=prod

echo $DATABASE_URL
echo $APP_ENV


To keep this variable , we have to create a ~/.bash_profile and store this lines:
export APP_ENV=prod
export DATABASE_URL=mysql://admin:admin@127.0.0.1:3306/micro-post
export MAILER_URL=smtp://0e107e69d52be3:eab5687e2bd497@smtp.mailtrap.io:25
export MAILER_FROM=me@me.com

load database
APP_ENV=$APP_ENV DATABASE_URL=$DATABASE_URL php bin/console doctrine:migrations:migrate


error pdo_mysql ---
active it in php.ini
sudo apt-get install php-common php-mysql php-cli


Change the owner of the website :
sudo chown -R www-data:www-data /var/www/micropost_current/
sudo chown -h www-data:www-data /var/www/micropost/


tail the log :
tail var/log/prod.log



----- Configure phpmyadmin VPS
create file :
/etc/apache2/conf-enabled/phpmyadmin.conf

add this configuration :
Alias /phpmyadmin /usr/share/phpmyadmin

<Directory /usr/share/phpMyAdmin/>
 AddDefaultCharset UTF-8
 <IfModule mod_authz_core.c>
 # Apache 2.4
 <RequireAny>
 #Require ip 127.0.0.1
 #Require ip ::1
  Require all granted
 </RequireAny>
 </IfModule>
 <IfModule !mod_authz_core.c>
 # Apache 2.4
  Allow from 77.207.29.214
 </IfModule>
</Directory>

<Directory /usr/share/phpMyAdmin/setup/>
 <IfModule mod_authz_core.c>
  # Apache 2.4
  <RequireAny>
   #Require ip 127.0.0.1
   #Require ip ::1
   Require all granted
  </RequireAny>
 </IfModule>
 <IfModule !mod_authz_core.c>
 # Apache 2.4
   Allow from 77.207.29.214
 </IfModule>
</Directory>

reload service apache after that:
sudo service apache2 restart



Erreur d'affichage warning sql.lib.php du VPS :
Rends toi dans le fichier /usr/share/phpmyadmin/libraries/sql.lib.php à l'aide de cette commande : nano /usr/share/phpmyadmin/libraries/sql.lib.php
Recherche (count($analyzed_sql_results['select_expr'] == 1) à l'aide des touches CTRL + W
Remplace le par ((count($analyzed_sql_results['select_expr']) == 1)
-------