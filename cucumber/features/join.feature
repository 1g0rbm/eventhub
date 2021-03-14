Feature: View join page

  Scenario: View join page without feature
    Given I am a guest user
    And I do not have "JOIN_US" feature
    When I open "/join" page
    Then I see "Page is not found"

  Scenario: View join page with feature
    Given I am a guest user
    And I have "JOIN_US" feature
    When I open "/join" page
    Then I see "Join us" header
