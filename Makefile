CWD:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))

archive:
	composer archive --format=tar --file=ratebtn
cs:
	php vendor/bin/phpcs --standard="PSR2" -n src/
test-unit:
	php vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit
test-integration:
	php vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration