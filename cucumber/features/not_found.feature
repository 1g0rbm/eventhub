Feature: Page Not Found

  Scenario: Page Not Found
    Given I am a guest user
    When I open "/not-found" page
    Then I see "Page is not found"
