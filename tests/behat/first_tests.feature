@tool @tool_odeialba
Feature: Tests if the record is correctly inserted.

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | Frist    | teacher1@example.com |
      | student1 | Student   | First    | student1@example.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  Scenario: Check rights for students
    When I log in as "student1"
    And I am on "Course 1" course homepage
    Then I should see "My first Moodle plugin"
    And I follow "My first Moodle plugin"
    Then I should see "Nothing to display"
    And I should not see "Add a new row"

  Scenario: Check rights and insert for teachers
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "My first Moodle plugin"
    And I follow "My first Moodle plugin"
    Then I should see "Nothing to display"
    And I follow "Add a new row"
    And I set the following fields to these values:
      | Name        | Test1            |
      | Completed   | 1                |
      | Description | Test description |
    And I press "Save"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description      |
      | Test1 | Yes       | Test description |

  Scenario: Check delete record for teachers
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "My first Moodle plugin"
    And I follow "My first Moodle plugin"
    Then I should see "Nothing to display"
    And I follow "Add a new row"
    And I set the following fields to these values:
      | Name        | Test1            |
      | Completed   | 1                |
      | Description | Test description |
    And I press "Save"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description      |
      | Test1 | Yes       | Test description |
    And I follow "Add a new row"
    And I set the following fields to these values:
      | Name        | Test2             |
      | Completed   | 1                 |
      | Description | Test description2 |
    And I press "Save"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description       |
      | Test1 | Yes       | Test description  |
      | Test2 | Yes       | Test description2 |
    And I click on "Delete" "link" in the "Test1" "table_row"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description       |
      | Test2 | Yes       | Test description2 |

  @javascript
  Scenario: Check delete record for teachers with js
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    Then I should see "My first Moodle plugin"
    And I follow "My first Moodle plugin"
    Then I should see "Nothing to display"
    And I follow "Add a new row"
    And I set the following fields to these values:
      | Name        | Test1            |
      | Completed   | 1                |
      | Description | Test description |
    And I press "Save"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description      |
      | Test1 | Yes       | Test description |
    And I follow "Add a new row"
    And I set the following fields to these values:
      | Name        | Test2             |
      | Completed   | 1                 |
      | Description | Test description2 |
    And I press "Save"
    And I wait to be redirected
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description       |
      | Test1 | Yes       | Test description  |
      | Test2 | Yes       | Test description2 |
    And I click on "Delete" "link" in the "Test1" "table_row"
    And I press "Yes"
    Then the following should exist in the "tool_odeialba_table" table:
      | Name  | Completed | Description       |
      | Test2 | Yes       | Test description2 |
