<?php

/**
 * @file
 * Contains \Drupal\globant_referral\Plugin\Block\ReportOverallStatus.
 */
namespace Drupal\globant_referral\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'globant_referral' block.
 *
 * @Block(
 *   id = "overall_status",
 *   admin_label = @Translation("Report: Overall Status By Recruiter"),
 *   category = @Translation("Report")
 * )
 */
class ReportOverallStatusBlock extends BlockBase implements BlockPluginInterface {

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
        '#attributes' => array('id' => 'report-overall-status', 'class' => array('well')),
        '#header' => $this->_headers,
        '#rows' => $this->_rows,
      ],
      'pager' => [
        '#type' => 'pager',
      ],
    ];
  }

  protected function getReportData() {
    $config = $this->getConfiguration();
    $header_name = t('@report_by', array('@report_by' => ucfirst($config['report_by'])));
    $this->_headers[] = ['data' => $header_name, 'field' => 'name', 'sort' => 'desc'];
    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms("candidate_status");
    if ($config['report_by'] == 'recruiter') {
      $this->_rows = \Drupal::service('globant_referral.services')
        ->getCandidateStatusForRecruiter();
    }
    else {
      $this->_rows = \Drupal::service('globant_referral.services')
        ->getCandidateStatusForSkill();
    }
    foreach ($terms as $term) {
      $this->_headers[] = $term->getName();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['report_by'] = array (
      '#type' => 'select',
      '#required' => TRUE,
      '#options' => ['' => t('Select'),'skills' => t('Skills'),
      'recruiter' => t('Recruiter')],
      '#title' => $this->t('Report By'),
      '#description' => $this->t('Report by Skill/Recruiter'),
      '#default_value' => isset($config['report_by']) ? $config['report_by'] : ''
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['report_by'] = $values['report_by'];
  }
}

