#!/bin/sh
set -e

CYAN='\x1b[36m'
MAGENTA='\x1b[35m'

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'artisan' ]; then

	echo "Launch project Filament Demo!"

	until nc -z -v -w30 mysql ${DB_PORT}
	do
	  echo "Waiting for database connection..."
	  # wait for 5 seconds before check again
	  sleep 5
	done

    if [ -d "vendor" ]; then
        echo -e "$MAGENTA Filament Demo already install 🚀"
    else
        echo "$BLUE Starting installation..."
        composer install
        php artisan migrate
#        php artisan app:create-admin --firstname=${ADMIN_FIRSTNAME} --lastname=${ADMIN_LASTNAME} --email=${ADMIN_EMAIL} --password=${ADMIN_PASSWORD}
#        php artisan app:install -n
        php artisan db:seed
        php artisan storage:link
        php artisan filament:assets
        php artisan livewire:publish --assets
#        php artisan app:search:index
	fi

    echo -e "Your project is live ! Storefront available here: http://localhost"
fi

exec docker-php-entrypoint "$@"
