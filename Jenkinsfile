pipeline {
    agent any
    options {
        timestamps()
    }
    environment {
        CI = 'true'
    }
    stages {
        stage("Init") {
            steps {
                sh 'make init'
            }
        }
        stage("Valid") {
            steps {
                sh "make api-validate-schema"
            }
        }
    }
    post {
        always {
            sh 'make docker-down-clear || true'
        }
    }
}
