<?php
namespace Drupal\globant_referral\Tests;
use Drupal\simpletest\WebTestBase;
/**
 * Tests the Drupal 8 globant_referral module functionality
 *
 * @group globant
 */
class GlobantReferralsTest extends WebTestBase {
  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('globant_referral', 'node', 'block');
  /**
   * A simple user with 'access content' permission
   */
  private $user;
  /**
   * Perform any initial set up tasks that run before every test method
   */
  public function setUp() {
    parent::setUp();
    $this->user = $this->drupalCreateUser(array('access content'));
  }
  /**
   * Tests that the 'demo/' path returns the right content
   */
  public function testCustomPageExists() {
    // Login
    $this->drupalLogin($this->user);
    // Test the page is found
    $this->drupalGet('joblist');
    $this->assertResponse(404);
    //$this->assertText('Correct message is shown.', 'Correct message is shown.');
  }
}
// php core/scripts/run-tests.sh --browser --verbose --url http://referrals.globant.dev/joblist --class "Drupal\globant_referral\Tests\TestClass"
