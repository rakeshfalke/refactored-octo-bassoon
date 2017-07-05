<?php
/**
 * @file
 * Contains \Drupal\referral_migrate\Plugin\migrate\process\CandidateStatus
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
 *   id = "candidate_status",
 * )
 */
class CandidateStatus extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }
    $referral_status = \Drupal::configFactory()->getEditable('globant_referral.settings')->get('referral_status');
    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms('candidate_status');
    $temp = [];
    foreach ($terms as $term) {
      if (strtolower($value) == strtolower($term->getName())) {
        return $term->id();
      }
    }
    return $referral_status;
  }
}
