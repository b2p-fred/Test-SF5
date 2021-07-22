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

To run a Symfony console command:
```shell
# Symfony console
$ docker exec -it docker_sf5_php-fpm php bin/console about

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
