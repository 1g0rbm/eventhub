Feature: View homepage
  In order to check homepage content
  As a guest user
  I want to be a able to view homepage

  @smoke
  Scenario: View homepage content
    Given I am a guest user
    And I do not have "WE_ARE_HERE" feature
    When I open "/" page
    Then I see welcome block
    And I see "We will be here soon"
    And I do not see "We are here"

  Scenario: View homepage content with
    Given I am a guest user
    And I have "WE_ARE_HERE" feature
    When I open "/" page
    Then I see welcome block
    And I do not see "We will be here soon"
    And I see "We are here"
