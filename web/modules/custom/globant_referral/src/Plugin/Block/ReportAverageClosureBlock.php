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
 *   id = "average_closure",
 *   admin_label = @Translation("Report: Average Closure By Recruiter/Skills"),
 *   category = @Translation("Report")
 * )
 */
class ReportAverageClosureBlock extends BlockBase implements BlockPluginInterface {

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
        '#attributes' => array('id' => 'report-average-closure'),
        '#header' => $this->_headers,
        '#rows' => $this->_rows,
        '#prefix' => '<div class="well contextual-region view">',
        '#suffix' => '</div>',
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
    $this->_headers[] = ['data' => 'Average Closure Time (days)'];

    $terms = \Drupal::service('globant_referral.services')->getVocabularyTerms("candidate_status");
    if ($config['report_by'] == 'recruiter') {
      $this->_rows = \Drupal::service('globant_referral.services')
        ->getAverageClosureForRecruiter();
    }
    else {
      $this->_rows = \Drupal::service('globant_referral.services')
        ->getAverageClosureForSkill();
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
      '#title' => $this->t('Average Closure Report By'),
      '#description' => $this->t('Average Closure Report by Skill/Recruiter'),
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

