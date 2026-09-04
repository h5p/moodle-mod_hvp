@mod @mod_hvp @_file_upload @_switch_iframe
Feature: Add mod_hvp dialog cards H5P activity
  In order to let students access a H5P dialog cards
  As a teacher
  I need to add a dialog cards H5P activity to a course

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
    And I add a "hvp" activity to course "Course 1" section "1"
    And I wait until "h5p-editor-iframe" iframe is interactable and switch to it
    And I click on "Get" "button" in the "#h5p-dialogcards" "css_element"
    And I wait until ".h5p-hub-content-type-detail-button-bar .h5p-hub-button-install" "css_element" exists
    And I wait "2" seconds
    And I click on "Install" "button" in the ".h5p-hub-content-type-detail-button-bar" "css_element"
    And I wait until ".h5p-hub-message" "css_element" exists
    And I wait "2" seconds
    And I should see "Dialog Cards successfully installed!"
    And I should see "Added"
    And I should see "new H5P libraries."
    And I switch to the main frame
    And I visit '/mod/hvp/library_list.php'
    And I should see "Dialog Cards"

  @javascript
  Scenario: Add a Dialog Cards H5P activity to a course by uploading
    When I log in as "teacher1"
    And I add a "hvp" activity to course "Course 1" section "1"
    And I wait until "h5p-editor-iframe" iframe is interactable and switch to it
    And I follow "Upload"
    And I should see "Upload an H5P file."
    And I upload "mod/hvp/tests/fixtures/dialog-cards.h5p" file to "Upload an H5P file" HVP filemanager
    And I click on "Use" "button" in the ".h5p-hub-upload-form" "css_element"
    And I wait until the page is ready
    And I should see "Dialog Cards was successfully uploaded!"
    And I wait "10" seconds
    And I switch to the main frame
    And I click on "Save and return to course" "button"
    And I am on "Course 1" course homepage
    Then I should see "Dialog Cards"
