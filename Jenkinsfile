node {
	stage('Checkout') {
		checkout scm
	}
	stage('Composer Install') {
    	sh 'composer install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
    }
}