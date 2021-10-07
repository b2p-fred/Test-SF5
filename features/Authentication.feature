# features/Authentication.feature
Feature: Authentication
  In order to access the application
  Users should be authenticated

  Background:
    Given the "Accept" request header is "application/ld+json"
    Given the "Content-Type" request header is "application/json; charset=utf-8"
    Given the "X-User-Agent" request header is "appName/appVersion (comments)"

  # --------------------------------------------------------------------------------
  # Basic cases - token exists and is provided on API call
  # --------------------------------------------------------------------------------
  @dumpEnvironment @createDatabase @loadFixtures
  Scenario: Not available for anonymous user
    Given I am not authenticated
    When I request "addresses"
    Then the response code is 401
    And the response reason phrase is Unauthorized
    And the response body contains JSON:
    """
    {
      "code": 401,
      "message": "JWT Token not found"
    }
    """

  Scenario: Get some data as an administrator
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
#    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "/api/addresses",
      "@type": "hydra:Collection",
      "hydra:member": "@arrayLength(10)",
      "hydra:totalItems": 100,
      "hydra:view": {
        "@id": "/api/addresses?page=1",
        "@type": "hydra:PartialCollectionView",
        "hydra:first": "/api/addresses?page=1",
        "hydra:last": "/api/addresses?page=10",
        "hydra:next": "/api/addresses?page=2"
      }
    }
    """

  Scenario: Get all available adresses as a simple user
    Given I am authenticated as a simple user
    When I request "addresses"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
#    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "/api/addresses",
      "@type": "hydra:Collection",
      "hydra:member": "@arrayLength(10)",
      "hydra:totalItems": 100,
      "hydra:view": {
        "@id": "/api/addresses?page=1",
        "@type": "hydra:PartialCollectionView",
        "hydra:first": "/api/addresses?page=1",
        "hydra:last": "/api/addresses?page=10",
        "hydra:next": "/api/addresses?page=2"
      }
    }
    """

  # --------------------------------------------------------------------------------
  # Real cases - login errors
  # --------------------------------------------------------------------------------
  Scenario: Login with bad parameters
    Given the request body is:
    """
    {
      "username": "gaston.lagaffe@edition-dupuis.com",
      "password": "Gaston!"
    }
    """
    When I request "login_check" using HTTP "POST"
    Then the response code is 400
    And the response reason phrase is "Bad Request"
    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "type": "https://tools.ietf.org/html/rfc2616#section-10",
      "title": "An error occurred",
      "status": 400,
      "detail": "The key \"email\" must be provided.",
      "class": "Symfony\\Component\\HttpKernel\\Exception\\BadRequestHttpException",
      "trace": [
      ]
    }
    """


  # --------------------------------------------------------------------------------
  # Real cases - login to get a token
  # --------------------------------------------------------------------------------
  Scenario: Login as a simple user
    Given the request body is:
    """
    {
      "email": "gaston.lagaffe@edition-dupuis.com",
      "password": "Gaston!"
    }
    """
    When I request "login_check" using HTTP "POST"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/json"
    And print the corresponding curl command
    And print last JSON response
    # Got a valid JWT token
    And the response body contains JSON:
    """
    {
      "token": "@regExp(/^[A-Za-z0-9-_\\=]+\\.[A-Za-z0-9-_\\=]+\\.?[A-Za-z0-9-_.+\\/\\=]*$/)"
    }
    """

  Scenario: Login as an administrator
    Given the request body is:
    """
    {
      "email": "big.brother@the-world.com",
      "password": "I@mTh3B0ss!"
    }
    """
    When I request "login_check" using HTTP "POST"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/json"
    And print the corresponding curl command
    And print last JSON response
    # Got a valid JWT token
    And the response body contains JSON:
    """
    {
      "token": "@regExp(/^[A-Za-z0-9-_]+\\.[A-Za-z0-9-_]+\\.?[A-Za-z0-9-_.+\\/]*$/)"
    }
    """

