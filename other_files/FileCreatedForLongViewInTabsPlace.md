-- Recharger la table manquante avant de faire un chargement de fixtures
php bin/console doctrine:migrations:execute --up 20200114042747

-- debug translations
php bin/console debug:translation

-- Catalogue des traductions définies par symfony
var/cache/dev/translations/catalogue.fr.5i9nlVK.php

-- Nomenclature des fichiers de translation
domain.locale.xlf 
exemple : messages.en.xlf

-- Recuperer une variable dans le fichier .env
 email_from: '%env(MAILER_FROM)%'
 
-- Definir un argument glocal d'un service
App\Mailer\Mailer:
        arguments:
            $mailFrom: '%email_from%'