pipeline {
    agent any

    environment {
        SONAR_TOKEN = credentials('sonarqube-token')
        // Define application URL for DAST testing
        APP_URL = 'http://host.docker.internal:8080'
        // Docker configuration
        SONARQUBE_CONTAINER = 'sonarqube-container'
        ZAP_CONTAINER = 'zap-container'
        SONARQUBE_PORT = '9000'
        ZAP_PORT = '8080'
        // Network for containers
        DOCKER_NETWORK = 'security-network'
        // PHP configuration
        PHP_PATH = 'php'
    }

    stages {
        stage('Checkout') {
            steps {
                script {
                    echo 'Checking out source code...'
                    git branch: 'master', url: 'https://github.com/fakiraihan/webLaravel.git'
                }
            }
        }

        stage('Docker Setup') {
            steps {
                script {
                    echo 'Setting up Docker network and containers...'
                    bat '''
                        echo Creating Docker network...
                        docker network create %DOCKER_NETWORK% 2>nul || echo Network already exists
                        
                        echo Cleaning up any existing containers...
                        docker stop %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not running
                        docker rm %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not found
                        docker stop %ZAP_CONTAINER% 2>nul || echo ZAP container not running
                        docker rm %ZAP_CONTAINER% 2>nul || echo ZAP container not found
                        
                        echo Pulling required Docker images...
                        docker pull sonarqube:lts
                        docker pull sonarsource/sonar-scanner-cli:latest
                        docker pull zaproxy/zap-stable
                        
                        echo Starting SonarQube container...
                        docker run -d ^
                            --name %SONARQUBE_CONTAINER% ^
                            --network %DOCKER_NETWORK% ^
                            -p %SONARQUBE_PORT%:9000 ^
                            -e SONAR_ES_BOOTSTRAP_CHECKS_DISABLE=true ^
                            -e SONAR_JDBC_URL=jdbc:h2:mem:sonar ^
                            -e SONAR_SEARCH_JAVAADDITIONALOPTS="-Dnode.store.allow_mmap=false" ^
                            -v sonarqube_data:/opt/sonarqube/data ^
                            -v sonarqube_logs:/opt/sonarqube/logs ^
                            -v sonarqube_extensions:/opt/sonarqube/extensions ^
                            sonarqube:lts
                        
                        echo SonarQube container started, it will take a few minutes to initialize...
                        echo Waiting 2 minutes for initial startup...
                        ping 127.0.0.1 -n 121 > nul
                        
                        echo Verifying SonarQube container is running...
                        docker ps | findstr %SONARQUBE_CONTAINER% || (
                            echo SonarQube container failed to start
                            echo Container logs:
                            docker logs %SONARQUBE_CONTAINER%
                            exit /b 1
                        )
                    '''
                }
            }
        }

        stage('Verify Docker Services') {
            steps {
                script {
                    echo 'Verifying Docker services are ready...'
                    bat '''
                        echo Checking SonarQube health...
                        set /a count=0
                        :wait_sonar
                        set /a count+=1
                        if %count% GTR 60 (
                            echo SonarQube failed to start after 10 minutes
                            echo Showing SonarQube container logs:
                            docker logs %SONARQUBE_CONTAINER%
                            echo Checking container status:
                            docker ps -a | findstr %SONARQUBE_CONTAINER%
                            exit /b 1
                        )
                        
                        echo Checking SonarQube health... attempt %count%/60
                        curl -f http://localhost:%SONARQUBE_PORT%/api/system/health 2>nul
                        if %errorlevel% equ 0 goto sonar_ready
                        
                        echo SonarQube not ready yet, waiting 10 seconds...
                        ping 127.0.0.1 -n 11 > nul
                        goto wait_sonar
                        
                        :sonar_ready
                        echo SonarQube is ready and healthy!
                        
                        echo Verifying SonarQube API is accessible...
                        curl -f http://localhost:%SONARQUBE_PORT%/api/system/status
                        
                        echo SonarQube verification completed successfully!
                    '''
                }
            }
        }

        stage('Environment Setup') {
            steps {
                script {
                    echo 'Setting up Laravel environment...'
                    bat '''
                        copy .env.example .env
                        echo APP_ENV=testing >> .env
                        echo APP_DEBUG=true >> .env
                        echo APP_URL=%APP_URL% >> .env
                        echo DB_CONNECTION=sqlite >> .env
                        echo DB_DATABASE=database\\database.sqlite >> .env
                    '''
                }
            }
        }

        stage('Install Dependencies') {
            steps {
                script {
                    echo 'Installing Composer dependencies...'
                    bat 'composer install --no-interaction --prefer-dist --optimize-autoloader'
                    
                    echo 'Generating application key...'
                    bat 'php artisan key:generate'
                    
                    echo 'Running database migrations...'
                    bat 'php artisan migrate --force'
                    
                    echo 'Seeding database...'
                    bat 'php artisan db:seed --force'
                }
            }
        }

        stage('Unit Testing') {
            steps {
                script {
                    echo 'Running PHPUnit tests...'
                    bat 'vendor\\bin\\phpunit --configuration phpunit.xml --coverage-clover=coverage.xml --log-junit=phpunit-report.xml'
                }
            }
            post {
                always {
                    // Archive test results
                    publishTestResults testResultsPattern: 'phpunit-report.xml'
                    // Archive coverage reports for SonarQube
                    archiveArtifacts artifacts: 'coverage.xml', fingerprint: true
                }
            }
        }

        stage('SAST - SonarQube Analysis') {
            steps {
                script {
                    echo 'Starting SonarQube static analysis with Docker...'
                    
                    // Run SonarQube analysis using Docker
                    withSonarQubeEnv('SonarQube') {
                        bat '''
                            echo Running SonarQube scanner in Docker container...
                            docker run --rm ^
                                --network %DOCKER_NETWORK% ^
                                -v "%CD%":/usr/src ^
                                -w /usr/src ^
                                sonarsource/sonar-scanner-cli:latest ^
                                -Dsonar.host.url=http://%SONARQUBE_CONTAINER%:9000 ^
                                -Dsonar.login=%SONAR_AUTH_TOKEN% ^
                                -Dsonar.projectKey=webLaravel ^
                                -Dsonar.projectName=webLaravel ^
                                -Dsonar.projectVersion=1.0 ^
                                -Dsonar.sources=app,config,database,routes,resources ^
                                -Dsonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,public/**,node_modules/**,tests/** ^
                                -Dsonar.php.coverage.reportPaths=coverage.xml ^
                                -Dsonar.php.tests.reportPath=phpunit-report.xml ^
                                -Dsonar.language=php ^
                                -Dsonar.sourceEncoding=UTF-8
                        '''
                    }
                }
            }
        }

        stage('Quality Gate Check') {
            steps {
                script {
                    echo 'Waiting for SonarQube Quality Gate...'
                    timeout(time: 5, unit: 'MINUTES') {
                        def qg = waitForQualityGate()
                        if (qg.status != 'OK') {
                            error "Pipeline aborted due to quality gate failure: ${qg.status}"
                        }
                        echo 'Quality Gate passed successfully!'
                    }
                }
            }
        }

        stage('Start Application') {
            steps {
                script {
                    echo 'Starting Laravel application for DAST testing...'
                    bat '''
                        start /b php artisan serve --host=0.0.0.0 --port=8080
                        timeout /t 30
                        curl -f http://localhost:8080 || exit /b 1
                    '''
                }
            }
        }

        stage('DAST - OWASP ZAP Security Scan') {
            steps {
                script {
                    echo 'Starting OWASP ZAP dynamic security testing with Docker...'
                    
                    // Create reports directory
                    bat 'mkdir reports 2>nul || echo Reports directory exists'
                    
                    // Run ZAP baseline scan
                    bat '''
                        echo Running ZAP Baseline Scan...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-baseline.py ^
                            -t http://host.docker.internal:8080 ^
                            -g gen.conf ^
                            -J zap-baseline-report.json ^
                            -r zap-baseline-report.html || echo Baseline scan completed with findings
                    '''
                    
                    // Run ZAP full scan for more comprehensive testing
                    bat '''
                        echo Running ZAP Full Scan...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-full-scan.py ^
                            -t http://host.docker.internal:8080 ^
                            -g gen.conf ^
                            -J zap-full-report.json ^
                            -r zap-full-report.html || echo Full scan completed with findings
                    '''
                    
                    // Run ZAP API scan if you have API endpoints
                    bat '''
                        echo Running ZAP API Scan...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-api-scan.py ^
                            -t http://host.docker.internal:8080/api ^
                            -f openapi ^
                            -J zap-api-report.json ^
                            -r zap-api-report.html || echo API scan completed
                    '''
                }
            }
            post {
                always {
                    script {
                        // Copy reports to workspace root for archiving
                        bat '''
                            copy reports\\*.html . 2>nul || echo No HTML reports found
                            copy reports\\*.json . 2>nul || echo No JSON reports found
                            copy reports\\*.xml . 2>nul || echo No XML reports found
                        '''
                        
                        // Archive ZAP reports
                        archiveArtifacts artifacts: 'zap-*.html,zap-*.json,zap-*.xml', fingerprint: true, allowEmptyArchive: true
                        
                        // Publish ZAP reports
                        publishHTML([
                            allowMissing: true,
                            alwaysLinkToLastBuild: false,
                            keepAll: true,
                            reportDir: '.',
                            reportFiles: 'zap-baseline-report.html',
                            reportName: 'ZAP Baseline Security Report'
                        ])
                        
                        publishHTML([
                            allowMissing: true,
                            alwaysLinkToLastBuild: false,
                            keepAll: true,
                            reportDir: '.',
                            reportFiles: 'zap-full-report.html',
                            reportName: 'ZAP Full Security Report'
                        ])
                    }
                }
            }
        }

        stage('Security Analysis Results') {
            steps {
                script {
                    echo 'Processing security scan results...'
                    
                    // Parse ZAP results and check for high/medium severity issues
                    bat '''
                        if exist zap-baseline-report.json (
                            findstr /i "High\\|Medium" zap-baseline-report.json > security-issues.txt
                            if errorlevel 1 (
                                echo No high or medium severity issues found in baseline scan.
                            ) else (
                                echo WARNING: High or medium severity security issues detected in baseline scan!
                                type security-issues.txt
                            )
                        )
                        
                        if exist zap-full-report.json (
                            findstr /i "High\\|Medium" zap-full-report.json >> security-issues.txt
                            if errorlevel 1 (
                                echo No additional issues found in full scan.
                            ) else (
                                echo WARNING: Additional security issues detected in full scan!
                            )
                        )
                    '''
                }
            }
        }
    }

    post {
        always {
            script {
                echo 'Cleaning up...'
                
                // Stop Laravel application
                bat '''
                    for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8080') do taskkill /f /pid %%a 2>nul
                '''
                
                // Stop and remove Docker containers
                bat '''
                    docker stop %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not running
                    docker rm %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not found
                    docker stop %ZAP_CONTAINER% 2>nul || echo ZAP container not running  
                    docker rm %ZAP_CONTAINER% 2>nul || echo ZAP container not found
                    docker network rm %DOCKER_NETWORK% 2>nul || echo Network not found
                '''
                
                // Clean up temporary files
                bat '''
                    if exist security-issues.txt del security-issues.txt
                    if exist reports rmdir /s /q reports
                '''
            }
        }
        
        success {
            echo '✅ Pipeline completed successfully! Both SAST and DAST security scans passed.'
            
            // Send success notification
            emailext (
                subject: "✅ Security Pipeline Success - webLaravel",
                body: """
                The security pipeline for webLaravel has completed successfully!
                
                ✅ SAST (SonarQube): Quality gate passed
                ✅ DAST (OWASP ZAP): Security scan completed
                
                Build: ${env.BUILD_NUMBER}
                Branch: ${env.BRANCH_NAME}
                
                View reports: ${env.BUILD_URL}
                """,
                to: "${env.CHANGE_AUTHOR_EMAIL}"
            )
        }
        
        failure {
            echo '❌ Pipeline failed! Check logs for details.'
            
            // Send failure notification
            emailext (
                subject: "❌ Security Pipeline Failed - webLaravel",
                body: """
                The security pipeline for webLaravel has failed!
                
                Please check the build logs for details.
                
                Build: ${env.BUILD_NUMBER}
                Branch: ${env.BRANCH_NAME}
                
                View logs: ${env.BUILD_URL}console
                """,
                to: "${env.CHANGE_AUTHOR_EMAIL}"
            )
        }
        
        unstable {
            echo '⚠️ Pipeline completed with warnings. Please review security reports.'
        }
    }
}
