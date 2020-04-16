pipeline {
    agent any
	stages {
		stage('Checkout') {
			steps {
				checkout scm
			}
		}
		stage('Composer Install Dev') {
			steps {
				sh 'composer install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
			}
		}
		stage ('Static code analysis') {
			steps {
				parallel (
					"Check PSR-2": {
						sh 'php74 vendor/bin/phpcs --standard="PSR12" -n src/ tests/'
					},
					"PHPStan": {
						sh 'php74 vendor/bin/phpstan analyse src -l 5'
					}
				)
			}
		}
		stage('Unit Tests') {
			steps {
				sh 'php74 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit'
			}
		}
		stage('Integration Tests') {
			steps {
				sh 'php74 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration'
			}
		}
		stage('Deploy to production') {
			when { branch 'master' }
			steps {
				sh 'composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
				sh 'echo "{\"release_date\": \"$(date)\", \"change_id\": \"$env.CHANGE_ID\"}" > "info.json"'
				sh 'composer archive --format=tar --file=artifact'
				sh 'ansible-playbook ansible/deploy.yml'
			}
		}
	}
}