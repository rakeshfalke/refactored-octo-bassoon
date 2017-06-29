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
  public function getVocabularyTerms($vocab, $pager = FALSE) {
    $terms = [];
    $param = \Drupal::request()->query->all();
    $sort = (empty($param['sort']) || $param['sort'] == 'asc')? 'ASC' : 'DESC';
    if ($vocab) {
      $query = \Drupal::entityQuery('taxonomy_term');
      $query->condition('vid', $vocab);
      if ($pager) {
        $query->pager(10);
      }
      $query->sort('name', $sort);
      $tids = $query->execute();
      $terms = Term::loadMultiple($tids);
    }
    return $terms;
  }

  /**
   * {@inheritdoc}
   */
  public function getCandidateStatusForRecruiter() {
    $rows = [];
    $status = $this->getVocabularyTerms('candidate_status');
    $users = $this->getUserByRoles(['recruiter'], TRUE);

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

  /**
   * {@inheritdoc}
   */
  public function getCandidateStatusForSkill() {
    $rows = [];
    $status = $this->getVocabularyTerms('candidate_status');
    $skills = $this->getVocabularyTerms('candidate_skills', TRUE);

    foreach ($skills as $skill) {
      $row = [];
      $row[] = $skill->getName();
      foreach ($status as $term) {
        $nids = \Drupal::entityQuery('node')
          ->condition('type', 'referrals')
          ->condition('status', 1)
          ->condition('field_candidate_primary_skills', $skill->id())
          ->condition('field_candidate_status', $term->id())
          ->condition('field_isactive', 1)
          ->execute();
        $row[] = count($nids);
      }
      $row_key = 'skill-' . $skill->id();
      $rows[$row_key] = $row;
    }
    return $rows;
  }

  /**
   * {@inheritdoc}
   */
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
  public function getUserByRoles($roles = ['recruiter'], $pager = FALSE) {
    $param = \Drupal::request()->query->all();
    $sort = (empty($param['sort']) || $param['sort'] == 'asc')? 'ASC' : 'DESC';
    $query = \Drupal::entityQuery('user');
    $query->condition('status', 1);
    $query->condition('roles', $roles, 'IN');
    if ($pager) {
      $query->pager(10);
    }
    $query->sort('name', $sort);
    $ids = $query->execute();
    $users = user_load_multiple(array_keys($ids));
    return $users;
  }
}
