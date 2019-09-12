node {
	stage('Checkout') {
		checkout scm
	}
	stage('Composer Install') {
    	sh 'composer install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
    }
    stage('Check PSR-2') {
		sh 'vendor/bin/phpcs --standard="PSR2" -n src/'
	}
	stage('Unit Tests') {
		sh 'vendor/bin/phpunit -c phpunit.xml.dist'
	}
}