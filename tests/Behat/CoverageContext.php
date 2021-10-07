<?php

namespace App\Tests\Behat;

//use App\Utils\Transliterator;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterFeatureScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Report\PHP;
use SebastianBergmann\CodeCoverage\Report\Clover;

class CoverageContext implements Context
{
    private static CodeCoverage $coverage;

    private static PHP $writer;
    private static Clover $cloverWriter;

    /**
     * @BeforeFeature
     */
    public static function setup()
    {
        $filter = new Filter();
        $filter->includeDirectory(__DIR__.'/../../src');
        $filter->excludeDirectory(__DIR__.'/../../migrations');

        $driver = new Driver\PcovDriver($filter);

        static::$coverage = new CodeCoverage($driver, $filter);
//        static::$writer = new PHP();
        static::$cloverWriter = new Clover();
    }

    /**
     * @BeforeScenario
     */
    public function startCoverage(BeforeScenarioScope $scope)
    {
        static::$coverage->start("{$scope->getFeature()->getTitle()}::{$scope->getScenario()->getTitle()}");
    }

    /**
     * @AfterScenario
     */
    public function stopCoverage(AfterScenarioScope $scope)
    {
        static::$coverage->stop();
    }

    /**
     * @AfterFeature
     */
    public static function writeReport(AfterFeatureScope $scope)
    {
        $featureTitle = $scope->getFeature()->getTitle();
//        $feature = Transliterator::transliterate($feature);

        // Coverage data files names must start with `coverage` to upload results to a service such as codecov.io
        static::$cloverWriter->process(static::$coverage, __DIR__.'/../../artifacts/Behat/coverage-'.$featureTitle.'.xml');
    }
}
