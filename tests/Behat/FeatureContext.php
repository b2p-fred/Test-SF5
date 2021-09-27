<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;

class FeatureContext implements Context
{
    private string $environment;

    /**
     * Initializes context.
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->environment = $kernel->getEnvironment();
    }

    /**
     * @BeforeScenario
     *
     * @see https://gist.github.com/stof/930e968829cd66751a3a
     */
    public function gatherContexts(BeforeScenarioScope $scope): void
    {
        $environment = $scope->getEnvironment();
//        $subContext = $environment->getContext('restContext');
    }

    /**
     * Outputs all the variables defined in the current test environment
     * This allows to check for database name, configuration, ...
     *
     * @BeforeScenario @dumpEnvironment
     */
    public function dumpEnvironment(): void
    {
        echo 'Current Symfony environment: '.$_SERVER['APP_ENV'].PHP_EOL;
        echo '-----'.PHP_EOL;

        $sortedArray = $_SERVER;
        ksort($sortedArray);
        foreach ($sortedArray as $key => $val) {
            echo '- '.$key.' = '.(is_string($val) ? $val : serialize($val)).PHP_EOL;
        }
    }

    /**
     * @BeforeScenario @createDatabase
     */
    public function createDatabase(): void
    {
        $executableFinder = new ExecutableFinder();
        $symfonyExecutablePath = $executableFinder->find('symfony');

        try {
            echo "1 - Dropping an existing database...\n";
            $process = new \Symfony\Component\Process\Process(
                [$symfonyExecutablePath, 'console', '--env=test', 'doctrine:database:drop', '--force', '--if-exists']
            );
            $process->setTimeout(120);
            $process->mustRun();
            echo $process->getOutput();

            echo "2 - Creating a new database...\n";
            $process = new \Symfony\Component\Process\Process(
                [$symfonyExecutablePath, 'console', '--env=test', 'doctrine:database:create']
            );
            $process->setTimeout(120);
            $process->mustRun();
            echo $process->getOutput();

            echo "3 - Creating database schema...\n";
            $process = new \Symfony\Component\Process\Process(
                [$symfonyExecutablePath, 'console', '--env=test', 'doctrine:migrations:migrate']
            );
            $process->setTimeout(120);
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo '##### got an error: ';
            echo $exception->getMessage();
        }
    }

    /**
     * @BeforeScenario @loadFixtures
     */
    public function loadFixtures(): void
    {
        $executableFinder = new ExecutableFinder();
        $symfonyExecutablePath = $executableFinder->find('symfony');

        try {
            echo "Loading test fixtures...\n";
            $process = new \Symfony\Component\Process\Process(
                [$symfonyExecutablePath, 'console', '--env=test', 'hautelook:fixtures:load', '--no-interaction', '-vv']
            );
            $process->setTimeout(120);
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo '##### got an error: ';
            echo $exception->getMessage();
        }
    }
}
