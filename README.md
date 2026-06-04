# Codeception API Automation Suite

---
Automated API testing suite for the Media Buyers (public) mockup API (https://app.mockfly.dev/ + 6a1b64f18823bcd776a68e67), built using PHP and the **Codeception** framework. It features dynamic state factories, strict JSON schema validation, and multi-environment configuration mapping.
The public API responds to both GET and POST to the same endpoint, responding HTTP200 and HTTP400 based on a custom header. 
---

## Project Structure

The project follows a modular, scalable directory hierarchy optimized for contract and functional API testing:

```text
codeception_api/
├── tests/
│   ├── _output/              # Test reports
│   ├── Api/                  # Actual API test files (Get/Post scenarios)
│   │   ├── getMediabuyersCest.php
│   │   └── postMediabuyersCest.php
│   ├── Envs/                 # Environment-specific configurations (dev, prod)
│   │   ├── dev.yml
│   │   └── prod.yml
│   └── Support/              # Data builders
│       ├── _generated/       # Auto-generated Codeception wrapper functions
│       ├── Data/             # Test data assets
│       │   ├── Schemas/      # Raw JSON schema files for contract validation
│       │   └── Payloads.php  # State factory for dynamic request payload generation
│       └── ApiTester.php     # Codeception generated Actor class blueprint
├── Api.suite.yml             # Specific configuration for the API test suite execution
└── codeception.yml           # Global framework project configurations
```

### Prerequisites
```
    PHP 8.1+

    Composer installed globally
```
### Installation

Clone the repository and navigate into the project root directory.

1. Install the required vendor packages:
```Bash
composer install
```
2. Validate your composer setup configuration:
```Bash
composer validate
```
3. Build the Codeception actor classes to register custom helper actions:
```Bash
vendor/bin/codecept build
```

## Executing the Test Suite

- Run command variations
```Bash
vendor/bin/codecept run Api --env dev -g sanity --html --debug
php vendor/bin/codecept run Api --env dev -g sanity --html --debug
composer test_everything # composer.json script
```

- Core CLI Options
```Bash
--env dev                  # target environment
-g sanity / --group sanity # execution Groups (/annotations /suites). Can be chained (-g sanity -g get), in which case the test runner treats it as an OR operation 
-x /--skip-group           # skips(filters) a group. Can also be chained, e.g. (-g sanity -x collision)
--html                     # generate native HTML report available at tests/_output/report.html
--xml                      # xml report
-g failed                  # rerunning only failed tests
--debug                    # console debug 
```

Wipe old execution report histories and lingering failure logs
```Bash
vendor/bin/codecept clean
```

## Parallel option usage

Parallel running using the '--shard' option which divides the total test volume into parallel chunks for concurrent processing pipelines. 
Expects fraction notation (e.g. --shard 1/3 runs the first third of your suite on that particular runner instance).
In order to generate a unified test report, Allure can be used as it offers a gorgeous dashboard.
I've included a 1 line script in composer.json ("test:dev_sharding") that works in Win11

```Bash
"test:dev_sharding": "start /b codecept run Api --env dev --shard 1/3 & start /b codecept run Api --env dev --shard 2/3 & codecept run Api --env dev --shard 3/3 & allure serve tests/_output/allure-results",
```
Script breakdown
```
- "start /b"                                  // start independent background process
- "codecept run Api --env dev --shard 1/3"    // run the first chunk of tests
- "codecept run Api --env dev --shard 2/3"    // run the second chunk of tests
- "codecept run Api --env dev --shard 3/3"    // run the third chunk of tests
- "allure serve tests/_output/allure-results" // Allure compiles the unified test report and opens up a local web server to serve it
```