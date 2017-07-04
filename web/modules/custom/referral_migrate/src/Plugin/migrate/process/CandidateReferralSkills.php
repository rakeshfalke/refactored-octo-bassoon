<?php
/**
 * @file
 * Contains \Drupal\referral_migrate\Plugin\migrate\process\CandidateReferralSkills
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
 *   id = "candidate_referral_skills",
 * )
 */
class CandidateReferralSkills extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }

    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms('candidate_skills');
    foreach ($terms as $term) {
      $temp[] = strtolower($term->getName());
    }
    $skills = explode('|', $value);
    $skill = strtolower($skills[1]);
    if (!in_array($skill, $temp)) {
      return $skill;
    }
    $message = t('Duplicated Term');
    throw new MigrateSkipRowException($message);
  }
}
