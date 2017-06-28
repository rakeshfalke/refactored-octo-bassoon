<?php

/**
 * @file
 * Contains \Drupal\globant_referral\Plugin\Block\ReportOverallStatus.
 * Common operations that many Globant Referral modules will need to reference.
 */
namespace Drupal\globant_referral\Services;
use Drupal\taxonomy\Entity\Term;

class CommonHelperServices {
 /*
  * Get the taxonomy terms  of a particular vocabulary.
  * How to Use : \Drupal::service('globant_referral.services')->getVocabularyTerms();
  * @return tems object
  */
  public function getVocabularyTerms($vocab) {
    $terms = [];
    if ($vocab) {
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', $vocab);
      $tids = $query->execute();
      $terms = Term::loadMultiple($tids);
    }
    return $terms;
  }

  public function getCandidateStatusForRecruiter() {
    $rows = [];
    $status = $this->getVocabularyTerms('candidate_status');
    $users = $this->getUserByRoles();

    foreach ($users as $user) {
      $row = [];
      $row[] = $user->getUsername();
      foreach ($status as $term) {
        $nids = \Drupal::entityQuery('node')
          ->condition('type', 'referrals')
          ->condition('status', 1)
          ->condition('field_assigned_to', $user->id())
          ->condition('field_candidate_status', $term->id())
          ->condition('field_isactive', 1)
          ->execute();
        $row[] = count($nids);
      }
      $row_key = 'recruiter-' . $user->id();
      $rows[$row_key] = $row;
    }
    return $rows;
  }

  public function getActiveReferralByEmail($email) {
    $nids = [];
    if ($email) {
      $nids = \Drupal::entityQuery('node')
        ->condition('type', 'referrals')
        ->condition('status', 1)
        ->condition('field_candidate_email', $email)
        ->condition('field_isactive', 1)
        ->execute();
    }
    return $nids;
  }

  /**
   * Get user by roles array.
   */
  public function getUserByRoles($roles = ['recruiter']) {
    $ids = \Drupal::entityQuery('user')
      ->condition('status', 1)
      ->condition('roles', $roles, 'IN')
      ->pager(10)
      ->execute();
    $users = user_load_multiple(array_keys($ids));
    return $users;
  }
}
