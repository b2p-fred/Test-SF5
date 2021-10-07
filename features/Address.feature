# features/Address.feature
Feature: Address
  As a simple user, I can get the addresses
  As an administrator, i am allowed to create, update and delete some addresses

  Background:
    Given the "Accept" request header is "application/ld+json"
    Given the "Content-Type" request header is "application/json; charset=utf-8"
    Given the "X-User-Agent" request header is "appName/appVersion (comments)"

  @dumpEnvironment @createDatabase @loadFixtures
  #----------------------------------------
  # GET
  #----------------------------------------
  Scenario: Not available for anonymous user
    Given I am not authenticated
    When I request "addresses"
    Then the response code is 401
    And the response reason phrase is Unauthorized
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "code": 401,
      "message": "JWT Token not found"
    }
    """

  Scenario: Get all available adresses as an administrator
    Given I am authenticated as an administrator
    # Set the pagination rules
    Given the following query parameters are set:
      | key          | value |
      | pagination   | 1     |
      | itemsPerPage | 5     |
    When I request "addresses"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
    And print the corresponding curl command
    And print last JSON response
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": "@storeSet(totalItems)",
      "hydra:view": {
        "@id": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "@type": "hydra:PartialCollectionView",
        "hydra:first": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "hydra:last": "/api/addresses?pagination=1&itemsPerPage=5&page=20",
        "hydra:next": "/api/addresses?pagination=1&itemsPerPage=5&page=2"
      }
    }
    """
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "/api/addresses",
      "@type": "hydra:Collection",
      "hydra:member": "@arrayLength(5)",
      "hydra:totalItems": 100,
      "hydra:view": {
        "@id": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "@type": "hydra:PartialCollectionView",
        "hydra:first": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "hydra:last": "/api/addresses?pagination=1&itemsPerPage=5&page=20",
        "hydra:next": "/api/addresses?pagination=1&itemsPerPage=5&page=2"
      }      
    }
    """
    # Check all the fields are present with correct type
    And the response body contains JSON:
    """
    {
      "hydra:member[0]": {
        "@id": "@variableType(string)",
        "@type": "Address",
        "id": "@variableType(string)",
        "type": "@variableType(string)",
        "address": "@variableType(null|string)",
        "address2": "@variableType(null|string)",
        "zipcode": "@variableType(null|string)",
        "city": "@variableType(null|string)",
        "country": "@variableType(null|string)",
        "lat": "@variableType(double)",
        "lng": "@variableType(double)",
        "site": "@variableType(null|string)",
        "createdAt": "@variableType(string)",
        "updatedAt": "@variableType(string)"
      }
    }
    """
    # Check identifiers and datetime fields with regexp
    And the response body contains JSON:
    """
    {
      "hydra:member[0]": {
        "@id": "@regExp(/^\\/api\\/addresses\\/[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
        "id": "@regExp(/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
        "createdAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)",
        "updatedAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)"
      }
    }
    """
    And the response body contains JSON:
    """
    {
      "hydra:member[0]": {
        "id": "@storeSet(uuid)"
      }
    }
    """
#    When I am using "<<pagesCount>>"
    When I request a unique "addresses" identified with "<<uuid>>"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
    And print the corresponding curl command
    And print last JSON response

  Scenario: Get all available adresses as a simple user
    Given I am authenticated as a simple user
    # Set the pagination rules
    Given the following query parameters are set:
      | key          | value |
      | pagination   | 1     |
      | itemsPerPage | 5     |
    When I request "addresses"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
    And print the corresponding curl command
    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "/api/addresses",
      "@type": "hydra:Collection",
      "hydra:member": "@arrayLength(5)",
      "hydra:totalItems": 100,
      "hydra:view": {
        "@id": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "@type": "hydra:PartialCollectionView",
        "hydra:first": "/api/addresses?pagination=1&itemsPerPage=5&page=1",
        "hydra:last": "/api/addresses?pagination=1&itemsPerPage=5&page=20",
        "hydra:next": "/api/addresses?pagination=1&itemsPerPage=5&page=2"
      }
    }
    """

  #----------------------------------------
  # CREATE
  #----------------------------------------
  Scenario: Create an address without correct rights
    Given I am authenticated as a simple user
    Given the request body is:
    """
    {}
    """
    When I request "addresses" using HTTP "POST"
    Then the response code is 403
    And the response reason phrase is "Forbidden"
    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "type": "https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
      "title": "An error occurred",
      "detail": "Access Denied.",
      "trace": [
      ]
    }
    """
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 100
    }
    """

  Scenario: Create an address with incorrect data
    Given I am authenticated as an administrator
    Given the request body is:
    """
    """
    When I request "addresses" using HTTP "POST"
    Then the response code is 400
    And the response reason phrase is "Bad Request"
    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "\/api\/contexts\/Error",
      "@type": "hydra:Error",
      "hydra:title": "An error occurred",
      "hydra:description": "Syntax error",
      "trace": [
      ]
    }
    """
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 100
    }
    """

  Scenario: Create the default address
    Given I am authenticated as an administrator
    Given the request body is:
    """
    {}
    """
    When I request "addresses" using HTTP "POST"
    Then the response code is 201
    And the response reason phrase is "Created"
    And print the corresponding curl command
    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "@regExp(/^\\/api\\/addresses\\/[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "@type": "Address",
      "id": "@regExp(/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "type": "main",
      "address": "",
      "address2": "",
      "zipcode": "",
      "city": "",
      "country": "",
      "lat": 43.816889,
      "lng": 5.045658,
      "site": null,
      "createdAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)",
      "updatedAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)"
    }
    """
    # Get the newly created item identifier and some other information
    And the response body contains JSON:
    """
    {
      "id": "@storeSet(uuid)",
      "type": "@storeSet(addressType)",
      "lat": "@storeSet(addressLat)",
      "lng": "@storeSet(addressLng)",
      "createdAt": "@storeSet(createdAt)",
      "updatedAt": "@storeSet(updatedAt)"
    }
    """
#    When I am using "<<pagesCount>>"
    When I request a unique "addresses" identified with "<<uuid>>"
    Then the response code is 200
    And the response reason phrase is OK
    And the "Content-Type" response header is "application/ld+json; charset=utf-8"
    And print the corresponding curl command
    And print last JSON response
    # Get the newly created item identifier and some other information
    And the response body contains JSON:
    """
    {
      "id": "@storeGet(uuid)",
      "type": "@storeGet(addressType)",
      "lat": "@storeGet(addressLat)",
      "lng": "@storeGet(addressLng)",
      "createdAt": "@storeGet(createdAt)",
      "updatedAt": "@storeGet(updatedAt)"
    }
    """
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 101
    }
    """

  Scenario: Create an address with some information
    Given I am authenticated as an administrator
    Given the request body is:
    """
    {
      "address": "201 allée des Fleurs",
      "address2": "Résidence les Dames du Lac",
      "zipcode": "26000",
      "city": "Valence",
      "country": "France"
    }
    """
    When I request "addresses" using HTTP "POST"
    And print the corresponding curl command
    Then the response code is 201
    And the response reason phrase is "Created"
    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "@regExp(/^\\/api\\/addresses\\/[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "@type": "Address",
      "id": "@regExp(/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "type": "main",
      "address": "201 allée des Fleurs",
      "address2": "Résidence les Dames du Lac",
      "zipcode": "26000",
      "city": "Valence",
      "country": "France",
      "lat": 43.816889,
      "lng": 5.045658,
      "site": null,
      "createdAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)",
      "updatedAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)"
    }
    """
    # Get the newly created item identifier and some other information
    And the response body contains JSON:
    """
    {
      "id": "@storeSet(uuid)"
    }
    """
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 102
    }
    """

  #----------------------------------------
  # EDIT
  #----------------------------------------
  Scenario: Edit an address without correct rights
    Given I am authenticated as a simple user
    Given the request body is:
    """
    {
      "address": "1 place de la liberté",
      "address2": "Bâtiment 1",
      "zipcode": "26000",
      "city": "Valence",
      "country": "France"
    }
    """
    # <<uuid>> is the UUID of the lst created item
    When I request a unique "addresses" identified with "<<uuid>>" using HTTP "PUT"
    Then the response code is 403
    And the response reason phrase is "Forbidden"
#    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "type": "https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
      "title": "An error occurred",
      "detail": "Access Denied.",
      "trace": [
      ]
    }
    """

  Scenario: Edit an address without correct rights
    Given I am authenticated as an administrator
    Given the request body is:
    """
    {
      "address": "1 place de la liberté",
      "address2": "Bâtiment 1",
      "zipcode": "26000",
      "city": "Valence",
      "country": "France"
    }
    """
    When I wait 3 second
    When I request a unique "addresses" identified with "<<uuid>>" using HTTP "PUT"
    And print the corresponding curl command
    Then the response code is 200
    And the response reason phrase is "OK"
    And print last JSON response
    And the response body contains JSON:
    """
    {
      "@context": "/api/contexts/Address",
      "@id": "@regExp(/^\\/api\\/addresses\\/[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "@type": "Address",
      "id": "@regExp(/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89AB][0-9a-f]{3}-[0-9a-f]{12}$/i)",
      "type": "main",
      "address": "1 place de la liberté",
      "address2": "Bâtiment 1",
      "zipcode": "26000",
      "city": "Valence",
      "country": "France",
      "lat": 43.816889,
      "lng": 5.045658,
      "site": null,
      "createdAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)",
      "updatedAt": "@regExp(/^[0-9]{4}-0[1-9]|1[0-2]-0[1-9]|[1-2][0-9]|3[0-1]T2[0-3]|[01][0-9]:[0-5][0-9]:[0-5][0-9]+00:00$/i)"
    }
    """
    And the response body contains JSON:
    """
    {
      "createdAt": "@storeEqual(createdAt)",
      "updatedAt": "@storeNotEqual(updatedAt)"
    }
    """

  #----------------------------------------
  # DELETE
  #----------------------------------------
  Scenario: Delete an address without correct right
    Given I am authenticated as a simple user
    # <<uuid>> is the UUID of the lst created item
    When I request a unique "addresses" identified with "<<uuid>>" using HTTP "DELETE"
    Then the response code is 403
    And the response reason phrase is "Forbidden"
#    And print the corresponding curl command
#    And print last JSON response
    And the response body contains JSON:
    """
    {
      "type": "https:\/\/tools.ietf.org\/html\/rfc2616#section-10",
      "title": "An error occurred",
      "detail": "Access Denied.",
      "trace": [
      ]
    }
    """
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 102
    }
    """

  Scenario: Delete an address with correct right
    Given I am authenticated as an administrator
    # <<uuid>> is the UUID of the lst created item
    When I request a unique "addresses" identified with "<<uuid>>" using HTTP "DELETE"
    Then the response code is 204
    And the response reason phrase is "No Content"
    And print the corresponding curl command
    And print last JSON response
    # Not any more item
    Given I am authenticated as an administrator
    When I request "addresses"
    Then the response code is 200
    And the response body contains JSON:
    """
    {
      "hydra:totalItems": 101
    }
    """
