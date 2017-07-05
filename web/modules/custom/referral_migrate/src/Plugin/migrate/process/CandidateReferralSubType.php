<?php
/**
 * @file
 * Contains \Drupal\referral_migrate\Plugin\migrate\process\CandidateReferralSubType
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
 *   id = "candidate_referral_sub_type",
 * )
 */
class CandidateReferralSubType extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }
    // return sub type or sub skill
    $skills = explode('|', $value);
    if(empty($skills[0])) {
      throw new MigrateSkipRowException($message);
    }

    $skill = strtolower($skills[0]);
    return $skill;
  }
}
