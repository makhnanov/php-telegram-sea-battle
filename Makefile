run:
	docker-compose build && docker-compose up
up:
	docker-compose build && docker-compose up
shell:
	docker-compose exec app sh
test:
	docker-compose exec app php vendor/bin/phpunit --colors=always --testdox Test #  --filter testSimplePrivateToUser Makhnanov\Telegram81\Test\SendMessageTest
redis:
	docker-compose exec redis sh
