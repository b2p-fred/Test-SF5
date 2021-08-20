# Symfony
## Installation
```shell
# Start the Docker environment
$ docker-compose up

# Dive into the PHP container with a shell
$ docker exec -it docker_sf5_php-fpm bash

# Create a new symfony project
$ symfony new .
# This will create all the necessary Symfony files and directories in the current location
# Starting from an existing application you must move all the created stuff to the root 
# directory of the project... this is the complicated part with Docker!
# Let us consider this is done and forget this part!!!
```

## Development
```shell
# Start the Docker environment
$ docker-compose up
```

First solution, run `bash` inside the PHP container:
```shell
# Dive into the PHP container with a shell
$ docker exec -it docker_sf5_php-fpm bash
www-data@456b61389e7d:~$ symfony check:requirements
www-data@456b61389e7d:~$ symfony check:security
www-data@456b61389e7d:~$ symfony server:start
...
...
```
**Hint**: To use this solution, you can also `Create terminal` in a container from the PHPStorm IDE 

Second solution, run Symfony commands in the container:
```shell
# Check Symfony requirements
$ docker exec -it docker_sf5_php-fpm symfony check:requirements

# Check Symfony security
$ docker exec -it docker_sf5_php-fpm symfony check:security

# 1/ Start the Symfony development server
$ docker exec -it docker_sf5_php-fpm symfony server:start
# Tail the log files ...
# Then Navigate to http://localhost:8000


# 2/ Start the Symfony development server
$ docker exec -it docker_sf5_php-fpm symfony server:start -d
# Navigate to http://localhost:8000
$ docker exec -it docker_sf5_php-fpm symfony server:log
```

**Note** that the recent Docker compose services are auto-starting the Symfony server. Thus, you do not need anymore to execute any command for this.

If it happens an error message as "`server is already running`", this is because of a badly interrupted former execution. To cope with this problem, remove all the **.pid* files locate in the `.symfony/var` directory.

To run a Symfony console command:
```shell
# Symfony console
$ docker exec -it docker_sf5_php-fpm symfony console about

 -------------------- --------------------------------- 
  Symfony                                               
 -------------------- --------------------------------- 
  Version              5.3.3                            
  Long-Term Support    No                               
  End of maintenance   01/2022 (in +194 days)           
  End of life          01/2022 (in +194 days)           
 -------------------- --------------------------------- 
  Kernel                                                
 -------------------- --------------------------------- 
  Type                 App\Kernel                       
  Environment          dev                              
  Debug                true                             
  Charset              UTF-8                            
  Cache directory      ./var/cache/dev (526 KiB)        
  Build directory      ./var/cache/dev (526 KiB)        
  Log directory        ./var/log (0 B)                  
 -------------------- --------------------------------- 
  PHP                                                   
 -------------------- --------------------------------- 
  Version              7.4.21                           
  Architecture         64 bits                          
  Intl locale          en_US_POSIX                      
  Timezone             UTC (2021-07-21T06:51:15+00:00)  
  OPcache              true                             
  APCu                 false                            
  Xdebug               false                            
 -------------------- --------------------------------- 
```

## Unit tests

### PHPUnit

Installation : 
```shell
$ composer require --dev phpunit/phpunit symfony/test-pack symfony/phpunit-bridge
```

Créer les fichiers de test dans le répertoire _tests_. Symfony installe un bridge qui permet de lancer les tests en pré chargeant l'environnement de test. Pour lancer les tests : 

```shell
$ ./bin/phpunit

# La "vraie" version de phpunit est installée dans /usr/local/bin ou dans ./vendor/bin  
```

Par la suite on va installer un package pour disposer de fixtures de tests dans des fichiers Yaml; ce package remplace avantageusement `dama/doctrine-test-bundle` donc on ne l'installe plus !

**DEPRECATED**

    Pour optimiser les performances des tests, installer le bundle:
    ```shell
    $ composer require --dev dama/doctrine-test-bundle
    ```
    et voir la [doc ici](https://github.com/dmaicher/doctrine-test-bundle). L'idée est que chaque modification apportée à la base de données pendant un test est rollback à la fin de l'exécution du test :) 
    
    Ce bundle est également utilisable avec Behat ! Par contre, il envoie une deprecation notice:
    ```
    Remaining indirect deprecation notices (1)
    
      1x: The "DAMA\DoctrineTestBundle\Doctrine\DBAL\AbstractStaticDriverV2" class implements "Doctrine\DBAL\Driver\ExceptionConverterDriver" that is deprecated.
        1x in PHPUnitExtension::executeBeforeFirstTest from DAMA\DoctrineTestBundle\PHPUnit
    ```
    qu'il est impossible de fixer ... [voir ici](https://github.com/dmaicher/doctrine-test-bundle/issues/129).
    
## Some useful plugins/bundles

### Debug stuff
```shell
$ composer require --dev debug
```

This to install the Symfony debug packages (var-dumper, web profiler, monolog...).

### Route annotations
```shell
$ composer require annotations
```

This to allow using annotations for the controllers' methods rules:
```
    /**
     * @Route ("/lucky/number")
     */
```
rather than updating the *config/routes.yaml* configuration file. 

### Monolog logging facility
```shell
$ composer require symfony/monolog-bundle
```

This to include the Monolog logger in the application. 

Then configure the *dev/monolog.yaml* main handler to use a `rotating_file` type; this will avoid having too huge files in the development environment. 

Read the doc on the Monolog powerful features [here](https://github.com/symfony/monolog-bundle).

### Symfony's security features
```shell
composer require symfony/security-bundle
```

This to include the [Symfony security features](https://www.doctrine-project.org/) in the application.

### Doctrine ORM
Doctrine is a popular ORM; see [here](https://www.doctrine-project.org/projects.html)
```shell
$ composer require --with-all-dependencies doctrine
```

Add some specific bundles to help matching with more databases: 
```shell
$ composer req fresh/doctrine-enum-bundle='~7.3'
```
See the `src/DBAL` directory content and the User entity *gender* attribute.


**Note** sometimes one must use `--no-scripts` else the default cache clearing script declared in the *composer.json* will fail the installation.

This to include the [Doctrine ORM](https://www.doctrine-project.org/) in the application.

Install the Maker bundle to help build entities ...
```shell
$ composer require symfony/maker-bundle --dev

# Create a new entity
$ symfony console make:entity Building
# Fields are: name, address, zipcode (integer), city

# And again a new entity
$ symfony console make:entity Company
# Fields are: name, building (relation ManyToOne)

# Create the DB migration
$ symfony console make:migration

# Create the database
$ symfony console doctrine:database:create
$ symfony console doctrine:database:create --if-not-exists 

# Run the DB migration (on an existing database)
$ symfony console doctrine:migrations:migrate
```


Creat and implement a controller for the entities
```shell
# Create the controllers
$ symfony console make:controller Company
$ symfony console make:controller Building
```

And then edit the *src/Controller/CompanyController.php* and *src/Controller/BuildingController.php* (view the [source code](../src/Controller/CompanyController.php)).
And the *templates/building/index.html.twig* and *templates/company/index.html.twig* 
And navigate to `http://localhost:8000/company`


```shell
# View the company table content
# PHPStorm is also your best friend for this -)
$ symfony console doctrine:query:sql "select * from company"
```

### User authentication
According to [this page](https://symfony.com/doc/current/security.html#create-user-class)

```shell
# Create a User class
$ symfony console make:user
# Accept all the default proposed choices

# More user information
$ symfony console make:entity User
# FirstName, LastName
```

Add the Doctrine fixtures package to create data in the database:
```shell
# Add the package
$ composer require orm-fixtures --dev

# Create some fixtures
$ symfony console make:fixtures

# Populate the database with the test fixtures
$ symfony console doctrine:fixtures:load
```

And edit the [Fixtures creation](../src/DataFixtures/UserFixtures.php).

Update the [firewall configuration](../config/packages/security.yaml):
- login_throttling (optional)
- login method (eg. login_form, login json, ...)
- ...

```shell
# Add the package for the logging throttling feature
$ composer require symfony/rate-limiter
```


Create a user [registration form](https://symfony.com/doc/current/forms.html:
```shell
# Forms
$ composer require symfony/form
$ composer require symfony/validator
$ composer require symfony/security-bundle
$ composer require symfony/security-core

# Verification email
$ composer require symfonycasts/verify-email-bundle symfony/mailer

# Create a user registration form
$ php bin/console make:registration-form

```

### JWT Token
According to [this page](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.md#getting-started)

```shell
# Install the bundle
$ composer require lexik/jwt-authentication-bundle

# Generate SSL keys
$ symfony console lexik:jwt:generate-keypair
```

### API Platform
More about [API Platform here](https://api-platform.com/)

```shell
# Install the bundle
$ composer require api

# Testing utilities
$ composer require --dev symfony/browser-kit symfony/http-client 
$ composer require --dev nelmio/alice
```

Create some test fixtures files in the fixtures directory 

