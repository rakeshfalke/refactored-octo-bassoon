<?php
/**
 * @file
 * Contains \Drupal\referral_migrate\Plugin\migrate\process\NameToSkillId
 */

namespace Drupal\referral_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\MigrateSkipRowException;
/**
 *
 * @MigrateProcessPlugin(
 *   id = "notice_period_string_to_number",
 * )
 */
class NoticePeriod extends ProcessPluginBase {
/**
  *  Less than 1 month 25 days
  *  1 to 2 month 45 days
  *  More than 2 months 65 days
  *  Not Sure 0
  *
  **/

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null value');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }
    $notice_period = ['Less than 1 month' => 25, '1 to 2 month' => 45,
    'More than 2 months' => 65, 'Not Sure' => 0];
    $notice_days = $notice_period[$value];
    return $notice_days;
  }
}
