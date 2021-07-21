lint:
	composer run-script phpcs -- --standard=PSR12 src bin
validate:
	composer validate
autoload:
	composer dump-autoload
install:
	composer install
test:
	composer exec --verbose phpunit tests