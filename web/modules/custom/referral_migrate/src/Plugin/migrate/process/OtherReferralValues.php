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
 *   id = "other_referral_values",
 * )
 */
class OtherReferralValues extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }
    return implode('|', $value);
  }
}
