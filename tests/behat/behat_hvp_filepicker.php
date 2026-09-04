<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filemanager and filepicker manipulation steps definitions.
 *
 * @package    mod_hvp
 * @copyright  2026 Luca Bösch <luca.boesch@bfh.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/core_behat_file_helper.php');

use Behat\Mink\Exception\ExpectationException,
    Behat\Mink\Exception\ElementNotFoundException,
    Behat\Gherkin\Node\TableNode;

/**
 * Steps definitions to deal with the filemanager and filepicker.
 *
 * @package    core_filepicker
 * @category   test
 * @copyright  2013 David Monllaó
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_hvp_filepicker extends behat_base {
    use core_behat_file_helper;

    /**
     * Uploads a file to the specified HVP filemanager leaving other fields in upload form default.
     * The paths should be relative to moodle codebase.
     *
     * @When /^I upload "(?P<filepath_string>(?:[^"]|\\")*)" file to "(?P<filemanager_field_string>(?:[^"]|\\")*)" HVP filemanager$/
     * @throws DriverException
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $filepath
     * @param string $filemanagerelement
     */
    public function i_upload_file_to_hvp_filemanager($filepath, $filemanagerelement) {
        $this->upload_file_to_hvp_filemanager($filepath, $filemanagerelement, new TableNode([]), false);
    }


    /**
     * Uploads a file to HVP filemanager
     *
     * @throws DriverException
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $filepath Normally a path relative to $CFG->dirroot, but can be an absolute path too.
     * @param string $filemanagerelement
     * @param TableNode $data Data to fill in upload form
     * @param false|string $overwriteaction false if we don't expect that file with the same name already exists,
     *     or button text in overwrite dialogue ("Overwrite", "Rename to ...", "Cancel")
     */
    protected function upload_file_to_hvp_filemanager($filepath, $filemanagerelement, TableNode $data, $overwriteaction = false) {
        global $CFG;

        if (!$this->has_tag('_file_upload')) {
            throw new DriverException('File upload tests must have the @_file_upload tag on either the scenario or feature.');
        }

        $filemanagernode = $this->get_hvp_filepicker_node($filemanagerelement);

        // Resolve the path of the file to upload.
        // Replace 'admin/' if it is in start of path with $CFG->admin .
        if (substr($filepath, 0, 6) === 'admin/') {
            $filepath = $CFG->dirroot . DIRECTORY_SEPARATOR . $CFG->admin .
                DIRECTORY_SEPARATOR . substr($filepath, 6);
        }
        $filepath = str_replace('/', DIRECTORY_SEPARATOR, $filepath);
        if (!is_readable($filepath)) {
            $filepath = $CFG->dirroot . DIRECTORY_SEPARATOR . $filepath;
            if (!is_readable($filepath)) {
                throw new ExpectationException('The file to be uploaded does not exist.', $this->getSession());
            }
        }

        // The H5P Hub upload form does not use the Moodle file picker: it has its own native
        // file input, hidden behind the "Upload a file" button. Attaching the file to that
        // input is the equivalent of choosing a file through the hub's file dialogue.
        $noinputexception = new ExpectationException('The H5P Hub file upload input could not be found', $this->getSession());
        $fileinput = $this->find(
            'xpath',
            ".//div[contains(concat(' ', normalize-space(@class), ' '), ' h5p-hub-upload-form ')]" .
            "//input[@type='file']",
            $noinputexception,
            $filemanagernode
        );
        $fileinput->attachFile($filepath);

        // We wait for all the JS to finish as it is registering the selected file.
        $this->getSession()->wait(self::get_timeout(), self::PAGE_READY_JS);
    }

    // phpcs:disable moodle.Files.LineLength.TooLong

    /**
     * Opens the contents of a filemanager folder. It looks for the folder in the current folder and in the path bar.
     *
     * @Given /^I open "(?P<foldername_string>(?:[^"]|\\")*)" folder from "(?P<filemanager_field_string>(?:[^"]|\\")*)" HVP filemanager$/
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $foldername
     * @param string $filemanagerelement
     */
    public function i_open_folder_from_hvp_filemanager($foldername, $filemanagerelement) {

        $fieldnode = $this->get_filepicker_node($filemanagerelement);

        $exception = new ExpectationException(
            'The "' . $foldername . '" folder can not be found in the "' . $filemanagerelement . '" filemanager',
            $this->getSession()
        );

        $folderliteral = behat_context_helper::escape($foldername);

        // We look both in the pathbar and in the contents.
        try {
            // In the current folder workspace.
            $folder = $this->find(
                'xpath',
                "//div[contains(concat(' ', normalize-space(@class), ' '), ' fp-folder ')]" .
                "/descendant::div[contains(concat(' ', normalize-space(@class), ' '), ' fp-filename ')]" .
                "[normalize-space(.)=$folderliteral]",
                $exception,
                $fieldnode
            );
        } catch (ExpectationException $e) {
            // And in the pathbar.
            $folder = $this->find(
                'xpath',
                "//a[contains(concat(' ', normalize-space(@class), ' '), ' fp-path-folder-name ')]" .
                "[normalize-space(.)=$folderliteral]",
                $exception,
                $fieldnode
            );
        }

        // It should be a NodeElement, otherwise an exception would have been thrown.
        $folder->click();
    }

    /**
     * Picks the file from repository leaving default values in select file dialogue.
     *
     * @When /^I add "(?P<filepath_string>(?:[^"]|\\")*)" file to "(?P<filemanagerelement_string>(?:[^"]|\\")*)" HVP filemanager$/
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $filepath
     * @param string $repository
     * @param string $filemanagerelement
     */
    public function i_add_file_to_hvp_filemanager($filepath, $repository, $filemanagerelement) {
        $this->add_file_to_hvp_filemanager($filepath, $repository, $filemanagerelement, new TableNode([]), false);
    }

    /**
     * Picks the file from private files repository
     *
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $filepath
     * @param string $repository
     * @param string $filemanagerelement
     * @param TableNode $data Data to fill the form in Select file dialogue
     * @param false|string $overwriteaction false if we don't expect that file with the same name already exists,
     *     or button text in overwrite dialogue ("Overwrite", "Rename to ...", "Cancel")
     */
    protected function add_file_to_hvp_filemanager(
        $filepath,
        $repository,
        $filemanagerelement,
        TableNode $data,
        $overwriteaction = false
    ) {
        $filemanagernode = $this->get_filepicker_node($filemanagerelement);

        // Opening the select repository window and selecting the upload repository.
        $this->open_add_file_window($filemanagernode, $repository);

        $this->open_element_contextual_menu($filepath);

        // Fill the form in Select window.
        $datahash = $data->getRowsHash();

        // The action depends on the field type.
        foreach ($datahash as $locator => $value) {
            $field = behat_field_manager::get_form_field_from_label($locator, $this);

            // Delegates to the field class.
            $field->set_value($value);
        }

        $selectfilebutton = $this->find_button(get_string('getfile', 'repository'));
        $selectfilebutton->click();

        // We wait for all the JS to finish as it is performing an action.
        $this->getSession()->wait(self::get_timeout(), self::PAGE_READY_JS);

        if ($overwriteaction !== false) {
            $overwritebutton = $this->find_button($overwriteaction);
            $overwritebutton->click();

            // We wait for all the JS to finish.
            $this->getSession()->wait(self::get_timeout(), self::PAGE_READY_JS);
        }
    }

    /**
     * Try to get the HVP filemanager node specified by the element
     *
     * @param string $filepickerelement
     * @return \Behat\Mink\Element\NodeElement
     * @throws ExpectationException
     */
    protected function get_hvp_filepicker_node($filepickerelement) {

        // More info about the problem (in case there is a problem).
        $exception = new ExpectationException('"' . $filepickerelement . '" filepicker can not be found', $this->getSession());

        // If no file picker label is mentioned take the first H5P Hub upload wrapper from the page.
        if (empty($filepickerelement)) {
            $filepickercontainer = $this->find(
                'xpath',
                "//div[contains(concat(' ', normalize-space(@class), ' '), ' h5p-hub-upload-wrapper ')]",
                $exception
            );
        } else {
            // Gets the H5P Hub upload wrapper whose instruction header matches the given label.
            // The header text may carry trailing punctuation (e.g. "Upload an H5P file."), so match
            // on a substring rather than the exact string.
            $filepickerelement = behat_context_helper::escape($filepickerelement);
            $filepickercontainer = $this->find(
                'xpath',
                "//h1[contains(concat(' ', normalize-space(@class), ' '), ' h5p-hub-upload-instruction-header ')]" .
                "[contains(normalize-space(.), $filepickerelement)]" .
                "/ancestor::div[contains(concat(' ', normalize-space(@class), ' '), ' h5p-hub-upload-wrapper ')]",
                $exception
            );
        }

        return $filepickercontainer;
    }

    /**
     * Selects a repository from the repository list in the file picker.
     *
     * @Then /^I select "(?P<repository_name_string>(?:[^"]|\\")*)" repository in HVP file picker$/
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $repositoryname
     */
    public function i_select_filepicker_repository($repositoryname) {
        $exception = new ExpectationException(
            "The '{$repositoryname}' repository can not be found in the file picker",
            $this->getSession()
        );
        // We look for a repository that matches a certain name in the file picker repository list.
        $xpath = "//div[contains(concat(' ', normalize-space(@class), ' '), ' filepicker ')]" .
            "//div[contains(concat(' ', normalize-space(@class), ' '), ' fp-repo-area ')]" .
            "//span[contains(concat(' ', normalize-space(@class), ' '), ' fp-repo-name ')]" .
            "[normalize-space(.)='{$repositoryname}']";

        $repository = $this->find('xpath', $xpath, $exception);
        // If the node exists, click on the node.
        $repository->click();
    }

    /**
     * Returns a specific element (file or folder) in the repository content area in the file picker.
     *
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $elementname The name of the element
     * @param string $elementtype The type of the element ("file" or "folder")
     * @return NodeElement
     */
    protected function get_element_in_filepicker_repository($elementname, $elementtype) {
        // We look for a .fp-{type} element with a certain name inside the content area of the file picker repository.
        $exception = new ExpectationException(
            "The '{$elementname}' {$elementtype} can not be found in the repository content area",
            $this->getSession()
        );
        $xpath = "//div[contains(concat(' ', normalize-space(@class), ' '), ' file-picker ')]" .
            "//div[contains(concat(' ', normalize-space(@class), ' '), ' fp-content ')]" .
            "//a[contains(concat(' ', normalize-space(@class), ' '), ' fp-{$elementtype} ')]" .
            "[normalize-space(.)='{$elementname}']";

        return $this->find('xpath', $xpath, $exception);
    }

    // phpcs:disable moodle.Files.LineLength.TooLong

    /**
     * Clicks on a specific element (file or folder) in the repository content area in the file picker.
     *
     * @Then /^I click on "(?P<element_name_string>(?:[^"]|\\")*)" "(?P<element_type_string>(?:[^"]|\\")*)" in HVP repository content area$/
     * @throws ExpectationException Thrown by behat_base::find
     * @param string $elementname The name of the element
     * @param string $elementtype The type of the element ("file" or "folder")
     */
    public function i_click_on_element_in_hvp_filepicker_repository($elementname, $elementtype) {
        $element = $this->get_element_in_hvp_filepicker_repository($elementname, $elementtype);
        $element->click();
    }
}
