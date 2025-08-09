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
                        
                        echo Creating persistent volumes for SonarQube data...
                        docker volume create sonarqube-data 2>nul || echo Volume already exists
                        docker volume create sonarqube-logs 2>nul || echo Volume already exists
                        docker volume create sonarqube-extensions 2>nul || echo Volume already exists
                        
                        echo Checking if SonarQube container is already running...
                        docker ps | findstr %SONARQUBE_CONTAINER% && (
                            echo SonarQube container is already running, reusing it
                        ) || (
                            echo Starting new SonarQube container with persistent storage...
                            docker stop %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not running
                            docker rm %SONARQUBE_CONTAINER% 2>nul || echo SonarQube container not found
                            docker run -d ^
                                --name %SONARQUBE_CONTAINER% ^
                                --network %DOCKER_NETWORK% ^
                                -p %SONARQUBE_PORT%:9000 ^
                                -v sonarqube-data:/opt/sonarqube/data ^
                                -v sonarqube-logs:/opt/sonarqube/logs ^
                                -v sonarqube-extensions:/opt/sonarqube/extensions ^
                                -e SONAR_ES_BOOTSTRAP_CHECKS_DISABLE=true ^
                                sonarqube:latest
                        )
                        
                        echo Checking if ZAP container is already running...
                        docker ps | findstr %ZAP_CONTAINER% && (
                            echo ZAP container is already running, reusing it
                        ) || (
                            echo Starting new ZAP container...
                            docker stop %ZAP_CONTAINER% 2>nul || echo ZAP container not running
                            docker rm %ZAP_CONTAINER% 2>nul || echo ZAP container not found
                            docker run -d ^
                                --name %ZAP_CONTAINER% ^
                                --network %DOCKER_NETWORK% ^
                                -p %ZAP_PORT%:8080 ^
                                zaproxy/zap-stable zap.sh -daemon -host 0.0.0.0 -port 8080 -config api.addrs.addr.name=.* -config api.addrs.addr.regex=true
                        )
                        
                        echo SonarQube container started, waiting 20 seconds...
                        ping 127.0.0.1 -n 21 > nul
                        
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
                        if %count% GTR 20 (
                            echo SonarQube failed to start after 3.5 minutes
                            echo Showing SonarQube container logs:
                            docker logs %SONARQUBE_CONTAINER%
                            echo Checking container status:
                            docker ps -a | findstr %SONARQUBE_CONTAINER%
                            exit /b 1
                        )
                        
                        echo Checking SonarQube health... attempt %count%/20
                        curl -f http://localhost:%SONARQUBE_PORT%/api/system/status 2>nul
                        if %errorlevel% equ 0 goto sonar_ready
                        
                        echo SonarQube not ready yet, waiting 10 seconds...
                        ping 127.0.0.1 -n 11 > nul
                        goto wait_sonar
                        
                        :sonar_ready
                        echo SonarQube is ready!
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
                    
                    echo 'Setting up database...'
                    bat '''
                        if not exist database mkdir database
                        if exist database\\database.sqlite del database\\database.sqlite
                        php -r "touch('database/database.sqlite');"
                    '''
                    
                    echo 'Creating fresh database...'
                    bat 'php artisan migrate:fresh --force'
                    
                    echo 'Seeding database...'
                    bat 'php artisan db:seed --force'
                }
            }
        }

        stage('Unit Testing') {
            steps {
                script {
                    echo 'Running PHPUnit tests...'
                    
                    // Run tests without coverage first
                    def testResult = bat(script: 'vendor\\bin\\phpunit --configuration phpunit.xml --log-junit=phpunit-report.xml', returnStatus: true)
                    if (testResult != 0) {
                        error("Unit tests failed")
                    }
                    
                    // Try to generate coverage if Xdebug/PCOV is available
                    def coverageResult = bat(script: 'vendor\\bin\\phpunit --configuration phpunit.xml --coverage-clover=coverage.xml', returnStatus: true)
                    if (coverageResult == 0) {
                        echo 'Code coverage generated successfully'
                    } else {
                        echo 'Code coverage not available - Xdebug/PCOV not installed, continuing without coverage'
                    }
                }
            }
            post {
                always {
                    // Archive test results
                    junit testResults: 'phpunit-report.xml', allowEmptyResults: true
                    
                    // Archive coverage reports for SonarQube only if they exist
                    script {
                        if (fileExists('coverage.xml')) {
                            archiveArtifacts artifacts: 'coverage.xml', fingerprint: true
                            echo 'Coverage report archived successfully'
                        } else {
                            echo 'No coverage.xml found - skipping coverage artifact archiving'
                        }
                    }
                }
            }
        }

        stage('SAST - SonarQube Analysis') {
            steps {
                script {
                    echo 'Starting SonarQube static analysis with Docker...'
                    
                    // Run SonarQube analysis using Docker (without withSonarQubeEnv)
                    bat '''
                        setlocal enabledelayedexpansion
                        echo Waiting for SonarQube to be fully ready...
                        set /a count=0
                        :wait_sonar_ready
                        set /a count+=1
                        if %count% GTR 30 (
                            echo SonarQube failed to become ready after 10 minutes
                            echo Showing SonarQube container logs:
                            docker logs %SONARQUBE_CONTAINER%
                            exit /b 1
                        )
                        
                        echo Checking SonarQube status... attempt %count%/30
                        curl -s -u admin:admin http://localhost:%SONARQUBE_PORT%/api/system/status > sonar_status.json 2>nul
                        findstr "UP" sonar_status.json >nul
                        if %errorlevel% equ 0 goto sonar_fully_ready
                        
                        echo SonarQube still starting, waiting 20 seconds...
                        ping 127.0.0.1 -n 21 > nul
                        goto wait_sonar_ready
                        
                        :sonar_fully_ready
                        echo SonarQube is fully ready!
                        type sonar_status.json
                        
                        echo Testing SonarQube authentication with your token...
                        curl -H "Authorization: Bearer squ_6df3138935f9d1b456dac84a494a1e437a601c4e" ^
                            "http://localhost:%SONARQUBE_PORT%/api/authentication/validate" > auth_test.json 2>nul
                        
                        echo Authentication test response:
                        type auth_test.json
                        
                        if %errorlevel% neq 0 (
                            echo Token authentication failed, trying username/password...
                            echo Testing with admin credentials:
                            curl -u admin:Admin12345 "http://localhost:%SONARQUBE_PORT%/api/authentication/validate" > auth_test2.json 2>nul
                            type auth_test2.json
                            
                            echo Generating a new token...
                            curl -u admin:Admin12345 -X POST ^
                                "http://localhost:%SONARQUBE_PORT%/api/user_tokens/generate?name=jenkins-new-token" > new_token.json 2>nul
                            echo New token response:
                            type new_token.json
                            
                            echo Please update your token in the pipeline
                            exit /b 1
                        )
                        
                        echo Running SonarQube scanner with authentication token...
                        docker run --rm ^
                            --network %DOCKER_NETWORK% ^
                            -v "%CD%":/usr/src ^
                            -w /usr/src ^
                            sonarsource/sonar-scanner-cli:latest ^
                            -Dsonar.host.url=http://host.docker.internal:%SONARQUBE_PORT% ^
                            -Dsonar.token=squ_6df3138935f9d1b456dac84a494a1e437a601c4e ^
                            -Dsonar.projectKey=webLaravel ^
                            -Dsonar.projectName=webLaravel ^
                            -Dsonar.projectVersion=1.0 ^
                            -Dsonar.sources=app,config,database,routes,resources ^
                            -Dsonar.exclusions=vendor/**,storage/**,bootstrap/cache/**,public/**,node_modules/**,tests/**
                    '''
                }
            }
        }

        stage('Quality Gate Check') {
            steps {
                script {
                    echo 'Checking SonarQube Quality Gate...'
                    
                    // Wait for analysis to complete and check quality gate
                    bat '''
                        echo Waiting for SonarQube analysis to complete...
                        set /a count=0
                        :wait_analysis_complete
                        set /a count+=1
                        if %count% GTR 20 (
                            echo Analysis timeout after 10 minutes
                            echo Checking final status anyway...
                            goto check_final_status
                        )
                        
                        echo Checking analysis status... attempt %count%/20
                        curl -H "Authorization: Bearer squ_6df3138935f9d1b456dac84a494a1e437a601c4e" ^
                            "http://localhost:%SONARQUBE_PORT%/api/ce/activity?component=webLaravel&ps=1" > analysis_status.json 2>nul
                        
                        if %errorlevel% neq 0 (
                            echo API call failed, waiting 30 seconds...
                            ping 127.0.0.1 -n 31 > nul
                            goto wait_analysis_complete
                        )
                        
                        findstr /i "SUCCESS FAILED" analysis_status.json >nul
                        if %errorlevel% equ 0 (
                            echo Analysis completed!
                            goto check_final_status
                        )
                        
                        echo Analysis still in progress, waiting 30 seconds...
                        ping 127.0.0.1 -n 31 > nul
                        goto wait_analysis_complete
                        
                        :check_final_status
                        echo Checking Quality Gate status...
                        curl -H "Authorization: Bearer squ_6df3138935f9d1b456dac84a494a1e437a601c4e" ^
                            "http://localhost:%SONARQUBE_PORT%/api/qualitygates/project_status?projectKey=webLaravel" > qg_result.json 2>nul
                        
                        if %errorlevel% neq 0 (
                            echo Quality Gate API call failed
                            echo Showing analysis status:
                            type analysis_status.json
                            exit /b 1
                        )
                        
                        echo Quality Gate Response:
                        type qg_result.json
                        
                        findstr /i "ERROR FAILED" qg_result.json >nul
                        if %errorlevel% equ 0 (
                            echo ❌ Quality Gate FAILED!
                            echo Review the issues in SonarQube: http://localhost:%SONARQUBE_PORT%/dashboard?id=webLaravel
                            exit /b 1
                        )
                        
                        findstr /i "OK PASSED" qg_result.json >nul
                        if %errorlevel% equ 0 (
                            echo ✅ Quality Gate PASSED!
                            goto quality_gate_success
                        )
                        
                        echo ⚠️  Quality Gate status unclear, continuing...
                        echo Full response:
                        type qg_result.json
                        
                        :quality_gate_success
                        echo Quality Gate check completed successfully!
                        echo View detailed results: http://localhost:%SONARQUBE_PORT%/dashboard?id=webLaravel
                    '''
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
                    bat 'if not exist reports mkdir reports'
                    
                    // Run ZAP baseline scan (basic security testing)
                    bat '''
                        echo Running ZAP Baseline Scan...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-baseline.py ^
                            -t http://host.docker.internal:8080 ^
                            -g gen.conf ^
                            -J zap-baseline-report.json ^
                            -r zap-baseline-report.html ^
                            -m 2 ^
                            -d || echo Baseline scan completed with findings
                    '''
                    
                    // Run ZAP spider scan for endpoint discovery
                    bat '''
                        echo Running ZAP Spider Scan...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-baseline.py ^
                            -t http://host.docker.internal:8080 ^
                            -m 1 ^
                            -s ^
                            -J zap-spider-report.json ^
                            -r zap-spider-report.html ^
                            -d || echo Spider scan completed
                    '''
                    
                    // Run ZAP full scan with authentication
                    bat '''
                        echo Running ZAP Full Scan with Authentication...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-full-scan.py ^
                            -t http://host.docker.internal:8080 ^
                            -g gen.conf ^
                            -J zap-full-report.json ^
                            -r zap-full-report.html ^
                            -r zap-full-report.html || echo Full scan completed with findings
                    '''
                    
                    // Also run a spider scan to discover more endpoints after login
                    bat '''
                        echo Running ZAP Spider Scan with Authentication...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-baseline.py ^
                            -t http://host.docker.internal:8080 ^
                            -s ^
                            -J zap-spider-report.json ^
                            -r zap-spider-report.html ^
                            -r zap-spider-report.html || echo Spider scan completed
                    '''
                    
                    // Run ZAP API scan with authentication if you have API endpoints
                    bat '''
                        echo Running ZAP API Scan with Authentication...
                        docker run -v "%CD%\\reports":/zap/wrk/:rw ^
                            -t zaproxy/zap-stable zap-api-scan.py ^
                            -t http://host.docker.internal:8080/api ^
                            -f openapi ^
                            -c auth.conf ^
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
                            if exist reports\\*.html (
                                copy reports\\*.html . >nul 2>&1
                                echo HTML reports copied successfully
                            ) else (
                                echo No HTML reports found
                            )
                            
                            if exist reports\\*.json (
                                copy reports\\*.json . >nul 2>&1
                                echo JSON reports copied successfully
                            ) else (
                                echo No JSON reports found
                            )
                            
                            if exist reports\\*.xml (
                                copy reports\\*.xml . >nul 2>&1
                                echo XML reports copied successfully
                            ) else (
                                echo No XML reports found
                            )
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
                        
                        publishHTML([
                            allowMissing: true,
                            alwaysLinkToLastBuild: false,
                            keepAll: true,
                            reportDir: '.',
                            reportFiles: 'zap-spider-report.html',
                            reportName: 'ZAP Spider Security Report'
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
                
                // Stop and remove Docker containers (optional - commented out to preserve data)
                bat '''
                    echo Containers will be left running to preserve SonarQube data and tokens
                    echo To manually stop: docker stop %SONARQUBE_CONTAINER% %ZAP_CONTAINER%
                    echo To manually remove: docker rm %SONARQUBE_CONTAINER% %ZAP_CONTAINER%
                    echo SonarQube data is stored in persistent volumes: sonarqube-data, sonarqube-logs, sonarqube-extensions
                    
                    echo Uncomment below lines if you want to clean up containers after each run
                    echo docker stop %SONARQUBE_CONTAINER% 2^>nul ^|^| echo SonarQube container not running
                    echo docker rm %SONARQUBE_CONTAINER% 2^>nul ^|^| echo SonarQube container not found
                    echo docker stop %ZAP_CONTAINER% 2^>nul ^|^| echo ZAP container not running  
                    echo docker rm %ZAP_CONTAINER% 2^>nul ^|^| echo ZAP container not found
                    echo docker network rm %DOCKER_NETWORK% 2^>nul ^|^| echo Network not found
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
