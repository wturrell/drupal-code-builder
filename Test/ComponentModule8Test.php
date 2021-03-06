<?php

/**
 * @file
 * Contains ComponentModule8Test.
 */

namespace DrupalCodeBuilder\Test;

/**
 * Tests the AdminSettingsForm generator class.
 *
 * Run with:
 * @code
 *   vendor/phpunit/phpunit/phpunit Test/ComponentModule8Test.php
 * @endcode
 */
class ComponentModule8Test extends TestBase {

  protected function setUp() {
    $this->setupDrupalCodeBuilder(8);
  }

  /**
   * Test requesting a module with no options produces basic files.
   */
  function testNoOptions() {
    // Create a module.
    $module_name = 'testmodule8a';
    $module_data = array(
      'base' => 'module',
      'root_name' => $module_name,
      'readable_name' => 'Test module',
      'short_description' => 'Test Module description',
      'hooks' => array(
      ),
      'readme' => FALSE,
    );
    $files = $this->generateModuleFiles($module_data);
    $file_names = array_keys($files);

    $this->assertCount(1, $files, "One file is returned.");

    $this->assertContains("$module_name.info.yml", $file_names, "The files list has a .info.yml file.");
  }

  /**
   * Test the helptext option produces hook_help().
   */
  function testHelptextOption() {
    // Create a module.
    $module_name = 'testmodule8b';
    $help_text = 'This is the test help text';
    $module_data = array(
      'base' => 'module',
      'root_name' => $module_name,
      'readable_name' => 'Test module',
      'short_description' => 'Test Module description',
      'hooks' => array(
      ),
      'readme' => FALSE,
      'module_help_text' => $help_text,
    );
    $files = $this->generateModuleFiles($module_data);
    $file_names = array_keys($files);

    $this->assertCount(2, $files, "Two files are returned.");

    $this->assertContains("$module_name.module", $file_names, "The files list has a .module file.");
    $this->assertContains("$module_name.info.yml", $file_names, "The files list has a .info.yml file.");

    // Check the .module file.
    $module_file = $files["$module_name.module"];

    $this->assertNoTrailingWhitespace($module_file, "The module file contains no trailing whitespace.");

    $this->assertWellFormedPHP($module_file, "Module file parses as well-formed PHP.");

    $this->assertFileHeader($module_file, "The module file contains the correct PHP open tag and file doc header");

    $this->assertHookDocblock('hook_help', $module_file, "The module file contains the docblock for hook_menu().");
    $this->assertHookImplementation($module_file, 'hook_help', $module_name, "The module file contains a function declaration that implements hook_menu().");

    $this->assertFunctionCode($module_file, $module_name . '_help', $help_text, "The hook_help() implementation contains the requested help text.");
  }

}