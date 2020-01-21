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

