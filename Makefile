.PHONY: cs-fix

cs-fix:
	docker compose exec -e PHP_CS_FIXER_IGNORE_ENV=1 laravel.test vendor/bin/php-cs-fixer fix
