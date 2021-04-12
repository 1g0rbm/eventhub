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
    And I see "join-form" element

  @wip
  Scenario: Success join
    Given I am a guest user
    And I have "JOIN_US" feature
    And I am on "/join" page
    When I fill "input[name=email]" field with "join-new@app.test"
    And I fill "input[name=password]" field with "new-password"
    And I check "input[name=agree]" checkbox"
    And I click submit button
    Then I see success "Confirm join by link in email"

  @wip
  Scenario: Existing join
    Given I am a guest user
    And I have "JOIN_US" feature
    And I am on "/join" page
    When I fill "input[name=email]" field with "join-new@app.test"
    And I fill "input[name=password]" field with "new-password"
    And I check "input[name=agree]" checkbox"
    And I click submit button
    Then I see error "User already exist"

  @wip
  Scenario: Invalid join
    Given I am a guest user
    And I have "JOIN_US" feature
    And I am on "/join" page
    When I fill "input[name=email]" field with "join-new@app.test"
    And I fill "input[name=password]" field with "new"
    And I check "input[name=agree]" checkbox"
    And I click submit button
    Then I see validation error "This value is too short"
