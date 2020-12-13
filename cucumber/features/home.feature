Feature: View homepage
  In order to check homepage content
  As a guest user
  I want to be a able to view homepage

  Scenario: View homepage content
    Given I am a guest user
    When I open "/" page
    Then I see welcome block
