# Codeception API Automation Suite

Automated API testing suite for the Media Buyers mockup API (https://app.mockfly.dev/ + 6a1b64f18823bcd776a68e67), built using PHP and the **Codeception** framework. It features dynamic state factories, strict JSON schema validation, and multi-environment configuration mapping.
The mockup responds to both GET and POST to the same endpoint, responding HTTP200 and HTTP400 based on a header. 
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

Getting Started

Prerequisites
```
    PHP 8.1+

    Composer installed globally
```
Installation

Clone the repository and navigate into the project root directory.

Install the required vendor packages:
```Bash
composer install
```
Validate your composer setup configuration:
```Bash
composer validate
```

Build the Codeception actor classes to register custom helper actions:
```Bash
vendor/bin/codecept build
```
Executing the Test Suite

Run using codecept
```Bash
vendor/bin/codecept run Api --env dev -g sanity --html --debug
```
Run using php
```Bash
php vendor/bin/codecept run Api --env dev -g sanity --html --debug
```
Run options
```Bash
--env dev                  # target environment
-g sanity / --group sanity # execution Groups (/annotations /suites)
--html                     # generate HTML report available at tests/_output/report.html
--debug                    # console debug 
```

Other mentionable run options
```Bash
--shard 1/3 # shard 1 out of 3 - Codecept splits the number of tests to run in 3. Two more similar commands are needed to be run in the same time in order to achieve what --shard is supposed to achieve (... --shard 2/3 and ... --shard 3/3) .
--xml       # xml report
-g failed   # rerunning only failed tests
```

Wipe old execution report histories and lingering failure logs:
```Bash
vendor/bin/codecept clean
```