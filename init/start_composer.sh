#! /bin/bash
set -e

if [ ! -d "/var/www/html/vendor/" ]; then
    echo ">> Instalando dependências do Composer..."
    composer install --no-interaction
    composer require --dev phpunit/phpunit --no-interaction
    composer dump-autoload --no-interaction
    echo ">> Dependências instaladas com sucesso!"
else
    echo "Dependencias ja instaladas"
fi