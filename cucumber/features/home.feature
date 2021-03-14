Feature: View homepage
  In order to check homepage content
  As a guest user
  I want to be a able to view homepage

  @smoke
  Scenario: View homepage content
    Given I am a guest user
    And I do not have "JOIN_US" feature
    When I open "/" page
    Then I see "Eventhub" header
    And I see "We will be here soon"
    And I do not see "We are here"

  Scenario: View homepage content with
    Given I am a guest user
    And I have "JOIN_US" feature
    When I open "/" page
    Then I see "Eventhub" header
    And I do not see "We will be here soon"
    And I see "We are here"
    And I see "join-link" element

  Scenario: Click to join
    Given I am a guest user
    And I have "JOIN_US" feature
    And I am on "/" page
    When I click "join-link" element
    Then I see "Join us" header
