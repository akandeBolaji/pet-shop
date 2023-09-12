Useful Commands

./laravel-docker.sh storage:link

./laravel-docker.sh test  

docker exec -it laravel_app chown -R www-data:www-data /var/www/storage

php artisan l5-swagger:generate

./laravel-docker.sh l5-swagger:generate

./laravel-docker.sh insights

./vendor/bin/phpstan analyse --memory-limit=2G



docker-compose down
docker-compose up -d --build
