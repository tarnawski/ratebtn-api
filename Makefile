CWD:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

archive:
	composer archive --format=tar --file=ratebtn
phpcs:
	php vendor/bin/phpcs --standard="PSR12" -n src/ tests/
phpstan:
	php vendor/bin/phpstan analyse src -l 5 -c phpstan.neon
test-unit:
	php vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit
test-integration:
	php vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration