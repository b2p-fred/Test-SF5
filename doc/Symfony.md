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

### Symfony security features
```shell
composer require symfony/security-bundle
```

This to include the [Symfony security features](https://www.doctrine-project.org/) in the application.

### Doctrine ORM
```shell
composer require --with-all-dependencies doctrine
```

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


Add a favicon:
```shell
# Copy the favicon.ico file in to the `public` folder and then:
$ composer require symfony/asset
```


Create a user [registration form](https://symfony.com/doc/current/forms.html:
```shell
# Forms
$ composer require symfony/form
$ composer require symfony/validator

# Verification email
$ composer require symfonycasts/verify-email-bundle symfony/mailer

// Create a user registration form
$ php bin/console make:registration-form

```