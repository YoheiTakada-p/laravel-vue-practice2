tinker:
	php artisan tinker
start:
	php artisan serve
watch:
	npm run watch
dev:
	npm run dev
migrate:
	php artisan migrate
reset:
	php artisan migrate:reset
refresh:
	php artisan migrate:refresh
seed:
	php artisan migrate --seed
test:
	"./vendor/bin/phpunit" --testdox
sql:
	mysql -u root -P 3306 -p
