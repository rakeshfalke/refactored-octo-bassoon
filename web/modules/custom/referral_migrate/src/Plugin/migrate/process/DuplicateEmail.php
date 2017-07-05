<?php
/**
 * @file
 * Contains \Drupal\referral_migrate\Plugin\migrate\process\DuplicateEmail
 */

namespace Drupal\referral_migrate\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\MigrateSkipRowException;
use \DateTime;
/**
 *
 * @MigrateProcessPlugin(
 *   id = "duplicate_email",
 * )
 */
class DuplicateEmail extends ProcessPluginBase {
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {

    $message = t('Null Term');
    if(empty($value)) {
      throw new MigrateSkipRowException($message);
    }

    $nids = \Drupal::service('globant_referral.services')->getActiveReferralByEmail($value);

    if (!empty($nids)) {
      $months = \Drupal::configFactory()->getEditable('globant_referral.settings')->get('before_month');

      $node_id = array_shift($nids);
      $node = node_load($node_id);
      // strtotime("+7 months") $node->getCreatedTime()
      $date_created = date('d-m-Y', $node->getCreatedTime());
      $start_date = new DateTime($date_created);
      $end_date = new DateTime();
      $interval = $end_date->diff($start_date);

      if ($interval->m >= $months || $interval->y > 1) {
        // set in active and add new with active
        $node->set('field_isactive', 0);
        $node->setNewRevision(TRUE);
        $node->save();
      }
      else {
        $message = t('Email already exists');
        throw new MigrateSkipRowException($message);
      }
    }
    return $value;
  }
}
