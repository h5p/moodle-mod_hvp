@mod @mod_hvpactivity @mod_hvp @_file_upload @_switch_iframe
Feature: Add mod_hvp H5P activity
  In order to let students access a H5P package
  As a teacher
  I need to add H5P activity to a course

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1 | 0 |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "admin"
    And I am on site homepage
    And I run the scheduled task "\mod_hvp\task\look_for_updates"
    And I wait "60" seconds

  @javascript
  Scenario: Add an hvpactivity to a course
    When I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "Interactive Content" to section "1" using the activity chooser
    And I wait until "h5p-editor-iframe" iframe is interactable and switch to it
    Then I should see "Select content type"
