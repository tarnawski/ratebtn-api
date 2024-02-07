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
				sh 'composer2 install --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
			}
		}
		stage ('Static code analysis') {
			steps {
				parallel (
					"Check PSR-2": {
						sh 'php83 vendor/bin/phpcs --standard="PSR12" -n src/ tests/'
					},
					"PHPStan": {
						sh 'php83 vendor/bin/phpstan analyse src -l 5 -c phpstan.neon'
					}
				)
			}
		}
		stage('Unit Tests') {
			steps {
				sh 'php83 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=unit'
			}
		}
		stage('Integration Tests') {
			steps {
				sh 'php83 vendor/bin/phpunit -c phpunit.xml.dist --testsuite=integration'
			}
		}
		stage('Deploy to production') {
			when { branch 'master' }
			steps {
				script {
				   def date = new Date()
				   writeFile(file: 'info.json', text: "{\"release_date\": \"" + date + "\"}")
				}
				sh 'composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs --no-progress --no-suggest'
				sh 'composer archive --format=tar --file=artifact'
				sh 'ansible-playbook ansible/deploy.yml'
			}
		}
	}
}
