<?php

namespace App\Tests\Behat;

use App\Entity\User;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\ORM\EntityManagerInterface;
use Imbo\BehatApiExtension\ArrayContainsComparator;
use Imbo\BehatApiExtension\Context\ApiContext;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\Assert;

class ExtendedApiContext extends ApiContext
{
    private JWTTokenManagerInterface $jwtManager;

    private EntityManagerInterface $em;

    private ?array $storedValues;

    /**
     * Initializes context.
     */
    public function __construct(EntityManagerInterface $em, JWTTokenManagerInterface $jwtManager)
    {
        $this->em = $em;
        $this->jwtManager = $jwtManager;
        $this->storedValues = [];
    }

    /**
     * Waits a while (e.g. for debugging).
     *
     * @When I wait :delay second(s)
     */
    public function wait(int $delay)
    {
        \sleep($delay);
    }

    /**
     * @BeforeFeature
     */
    public static function clearStoredVariables(BeforeFeatureScope $scope)
    {
        $title = basename($scope->getFeature()->getFile());
        $tempFile = getcwd()."/artifacts/$title.json";

        try {
            $fp = fopen($tempFile, 'w');
            fwrite($fp, json_encode([]));
            fclose($fp);
        } catch (\Exception $e) {
            echo "Tmp file write: $tempFile, error:".PHP_EOL;
            echo $e.PHP_EOL;
        }
    }

    /**
     * @BeforeScenario
     */
    public function restoreStoredVariables(BeforeScenarioScope $scope)
    {
        $title = basename($scope->getFeature()->getFile());
        $tempFile = getcwd()."/artifacts/$title.json";

        try {
            $jsonFileContents = file_get_contents($tempFile);
            $this->storedValues = json_decode($jsonFileContents, true);
        } catch (\Exception $e) {
//            echo "Tmp file: $tempFile, does not exist!".PHP_EOL;
//            echo $e.PHP_EOL;
        }
    }

    /**
     * @AfterScenario
     */
    public function persistStoredVariables(AfterScenarioScope $scope)
    {
        $title = basename($scope->getFeature()->getFile());
        $tempFile = getcwd()."/artifacts/$title.json";

        try {
            $fp = fopen($tempFile, 'w');
            fwrite($fp, json_encode($this->storedValues));
            fclose($fp);
        } catch (\Exception $e) {
            echo "Tmp file write: $tempFile, error:".PHP_EOL;
            echo $e.PHP_EOL;
        }
    }

    /**
     * Add some custom functions to the base comparator:
     * - @storeSet function that allows to memorize some values
     * - @storeGet function that checks a provided value equals the stored one.
     */
    public function setArrayContainsComparator(ArrayContainsComparator $comparator): ApiContext
    {
        // store set function
        $comparator->addFunction('storeSet', function ($haystackValue, $storeName, $realValue = null) {
//            echo "Store set: $storeName = $haystackValue".PHP_EOL;
            $haystackValue = (string) $haystackValue;
            $storeName = (string) $storeName;

            $this->storedValues[$storeName] = $realValue ?? $haystackValue;
            echo 'Stored values: '.PHP_EOL;
            foreach ($this->storedValues as $name => $value) {
                echo ' - '.$name.' = '.$value.PHP_EOL;
            }
        });

        // store get function
        $comparator->addFunction('storeGet', function ($haystackValue, $storeName) {
//            echo "Store get: $storeName = $haystackValue, from $remainingValue".PHP_EOL;
            $value = $this->storedValues[$storeName] ?? null;
            Assert::assertEquals(
                $value, $haystackValue,
                sprintf('Stored variable with name "%s" does not exist', $storeName)
            );
        });

        // store Equal function
        $comparator->addFunction('storeEqual', function ($haystackValue, $storeName) {
//            echo "storeEqual: $storeName = $haystackValue".PHP_EOL;
            $value = $this->storedValues[$storeName] ?? null;
            Assert::assertEquals(
                $value, $haystackValue,
                sprintf('Stored variable with name "%s" does not equal %s (-> %s)', $storeName, $haystackValue, $value)
            );
        });

        // store Not Equal function
        $comparator->addFunction('storeNotEqual', function ($haystackValue, $storeName) {
//            echo "storeNotEqual: $storeName = $haystackValue".PHP_EOL;
            $value = $this->storedValues[$storeName] ?? null;
            Assert::assertNotEquals(
                $value, $haystackValue,
                sprintf('Stored variable with name "%s" equals %s (-> %s)', $storeName, $haystackValue, $value)
            );
        });

        return parent::setArrayContainsComparator($comparator);
    }

    /**
     * Transform an identifier/variable if needed.
     *
     * @Transform /using "([^"]+)"/
     * @Transform /^uuid "([^"]+)"/
     * @Transform /^identified with "([^"]+)"/
     */
    public function transformVariable(string $variable): string
    {
        $value = $variable;

//        echo 'Stored values: '.serialize($this->storedValues).', searching: '.$variable.PHP_EOL;

        // Remove leading and trailing << >> if any
        $storeVariable = preg_replace('/\<\<([^"]+)\>\>/i', '${1}', $variable);

        if ($storeVariable !== $variable) {
            $value = $this->storedValues[$storeVariable] ?? null;
            Assert::assertNotNull(
                $value,
                sprintf('Stored variable with name "%s" does not exist', $storeVariable)
            );
        }

        return $value;
    }

    /**
     * Request a unique path.
     *
     * @param string $path The path to request
     * @param string $uuid The unique id
     *
     * @When /^I request a unique "([^"]+)" (identified with "[^"]+")$/
     */
    public function requestUniquePath(string $path, string $uuid, string $method = null): ApiContext
    {
        return $this->requestPath("$path/$uuid", $method);
    }

    /**
     * Request a unique path.
     *
     * @param string      $path   The path to request
     * @param string      $uuid   The unique id
     * @param string|null $method The HTTP method to use
     *
     * @When /^I request a unique "([^"]+)" (identified with "[^"]+") using HTTP "([^"]+)"$/
     */
    public function requestUniquePathHttp(string $path, string $uuid, string $method = null): ApiContext
    {
        return $this->requestPath("$path/$uuid", $method);
    }

    /**
     * @Given /^I am (using "\<\<[^"]+")$/
     *
     * @throws \Throwable
     */
    public function iAmUsing(string $variable): void
    {
        echo 'Using value: '.$variable.PHP_EOL;
    }

    /**
     * @BeforeScenario
     *
     * @Given I clear Authorization header
     */
    public function iClearAuthorizationHeader(): void
    {
        $this->request->withoutHeader('Authorization');
    }

    /**
     * @BeforeScenario
     *
     * @Given I am not authenticated
     */
    public function iAmNotAuthenticated(): void
    {
        $this->setRequestHeader('Authorization', '');
    }

    /**
     * @Given I am authenticated as an administrator
     *
     * @throws \Throwable
     */
    public function iAmAuthenticatedAsAnAdministrator(): void
    {
        $this->iAmAuthenticatedAs('big.brother@the-world.com');
    }

    /**
     * @Given I am authenticated as a simple user
     *
     * @throws \Throwable
     */
    public function iAmAuthenticatedAsASimpleUser(): void
    {
        $this->iAmAuthenticatedAs('gaston.lagaffe@edition-dupuis.com');
    }

    /**
     * @Given I am authenticated as :email
     *
     * @throws \Throwable
     */
    public function iAmAuthenticatedAs(string $email): void
    {
        /** @var User $user */
        $user = $this->em->getRepository(User::class)->findOneBy(
            [
                'email' => $email,
            ]
        );
        if (null !== $user) {
            echo "I am '".$user->getFirstName().' '.$user->getLastName()."' (".implode(',', $user->getRoles()).')';
            $token = $this->jwtManager->create($user);
            $this->setRequestHeader('Authorization', "Bearer $token");
        }
    }

    /**
     * @Then print the corresponding curl command
     */
    public function printTheCorrespondingCurlCommand()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUri();

        $headers = '';
        foreach ($this->request->getHeaders() as $name => $values) {
            // echo 'Header: '.$name.': '.implode(', ', $values)."\r\n";
            if ('HTTP_' !== substr($name, 0, 5) && 'HTTPS' !== $name) {
                $headers .= " --header '$name: ".implode(', ', $values)."'";
            }
        }

        $parameters = '';
        $data = '';
        if (isset($this->requestOptions['query'])) {
            // todo: manage form_params
            // todo: manage multipart
            $params = $this->requestOptions['query'];
            if (!empty($params)) {
                $query = http_build_query($params);
                if ('GET' === $method) {
                    $parameters = "?$query";
                } else {
                    $data = " --data '$query'";
                }
            }
        }

//        echo "Request body: ".$this->request->getBody().PHP_EOL;
        $data = '';
        if (!empty($this->request->getBody())) {
            $data = (string) $this->request->getBody();
            try {
                $data = json_encode(json_decode($data), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (\Exception $e) {
                $data = str_replace(['|', "\r\n", "\n", "\r", '  '], '', $data);
            }
            $data = " --data '$data'";
        }

        echo "curl -X $method$headers$data --verbose '$url$parameters'";
    }

    /**
     * @Then print last JSON response
     */
    public function printLastJsonResponse()
    {
        echo $this->response->getStatusCode().' '.$this->response->getReasonPhrase().PHP_EOL;
        $content = (string) $this->response->getBody();
        echo json_encode(json_decode($content), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
