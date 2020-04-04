node {
	stage('Checkout') {
		checkout scm
	}
	stage('Composer Install') {
    	sh 'composer install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
    }
    stage ('Static code analysis') {
		parallel (
			"Security check": {
				sh 'php74 bin/security-checker security:check'
			}
			"Yaml linting": {
				sh 'php74 bin/console lint:yaml ./config/'
			},
        	"Check PSR-2": {
				sh 'php74 vendor/bin/phpcs --standard="PSR12" -n src/ tests/'
        	},
        	"PHPStan": {
				sh 'php74 vendor/bin/phpstan analyse src -l 5'
			}
        )
	}
	stage('Unit Tests') {
		sh 'php74 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit'
	}
	stage('Integration Tests') {
		sh 'php74 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration'
	}
}