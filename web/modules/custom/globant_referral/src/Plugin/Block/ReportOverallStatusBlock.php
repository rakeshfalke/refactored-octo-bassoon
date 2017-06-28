<?php

/**
 * @file
 * Contains \Drupal\globant_referral\Plugin\Block\ReportOverallStatus.
 */
namespace Drupal\globant_referral\Plugin\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'globant_referral' block.
 *
 * @Block(
 *   id = "overall_status",
 *   admin_label = @Translation("Overall status report block"),
 *   category = @Translation("Report: Overall Status By Recruiter")
 * )
 */
class ReportOverallStatusBlock extends BlockBase {

  /* table header */
  protected $_headers;
  /* table rows */
  protected $_rows;
  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->getReportData();
    return [
      'results' => [
        '#theme' => 'table',
        '#responsive' => TRUE,
        '#attributes' => array('id' => 'report-overall-status'),
        '#header' => $this->_headers,
        '#rows' => $this->_rows,
      ],
      'pager' => [
        '#type' => 'pager',
      ],
    ];
  }

  protected function getReportData() {
    $this->_headers[] = t('Recruiter');
    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms("candidate_status");
    $this->_rows = \Drupal::service('globant_referral.services')->getCandidateStatusForRecruiter();
    foreach ($terms as $term) {
      $this->_headers[] = $term->getName();
    }
  }
}

