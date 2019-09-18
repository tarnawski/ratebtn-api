node {
	stage('Checkout') {
		checkout scm
	}
	stage('Composer Install') {
    	sh 'composer install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
    }
    stage ('Static code analysis') {
		parallel (
        	"Check PSR-2": {
				sh 'php73 vendor/bin/phpcs --standard="PSR2" -n src/'
        	}
        )
	}
	stage('Unit Tests') {
		sh 'php73 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit'
	}
	stage('Integration Tests') {
		sh 'php73 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration'
	}
}