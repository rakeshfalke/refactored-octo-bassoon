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
 *   id = "name_to_skill_id",
 * )
 */
class NameToSkillId extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }
    $skills = explode('|', $value);
    $skill = strtolower($skills[1]);
    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms('candidate_skills');
    foreach ($terms as $term) {
      if ($skill == strtolower($term->getName())) {
        return $term->id();
      }
    }
    return 0;
  }
}
