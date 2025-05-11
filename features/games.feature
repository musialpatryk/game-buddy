Feature: Game management

    Scenario: Get empty list of games and then test all CRUD operations
        When I send a GET request to "/api/games"
        Then the response status code should be 200
        And the response content should be collection of 0 elements

        When I send a POST request to "/api/games" with payload:
            | name | Test |
        Then the response status code should be 201
        And the response content should be an object with payload:
            | id | 1 |
            | name | Test |

        Given I send a GET request to "/api/games"
        Then the response status code should be 200
        And the response content should be collection of 1 elements

        When I send a POST request to "/api/games" with payload:
            | name | Test |
        Then the response status code should be 409

        When I send a POST request to "/api/games" with payload:
            | name | Test2 |
        Then the response status code should be 201
        And the response content should be an object with payload:
            | id | 2 |
            | name | Test2 |

        When I send a GET request to "/api/games"
        Then the response status code should be 200
        And the response content should be collection of 2 elements

        When I send a DELETE request to "/api/games/3"
        Then the response status code should be 404

        When I send a DELETE request to "/api/games/2"
        Then the response status code should be 204

        When I send a GET request to "/api/games"
        Then the response status code should be 200
        And the response content should be collection of 1 elements

        When I send a PUT request to "/api/games/1" with payload:
            | name | Test3 |
        Then the response status code should be 200
        And the response content should be an object with payload:
            | id | 1 |
            | name | Test3 |

        When I send a GET request to "/api/games/1"
        Then the response status code should be 200
        And the response content should be an object with payload:
            | id | 1 |
            | name | Test3 |
