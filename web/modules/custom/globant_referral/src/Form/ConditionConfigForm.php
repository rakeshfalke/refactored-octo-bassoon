<?php

namespace Drupal\globant_referral\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConditionConfigForm.
 *
 * @package Drupal\globant_referral\Form
 */
class ConditionConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'globant_referral.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'globant_referral_condition_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('globant_referral.settings');

    $form['referral_condition'] = array(
      '#type' => 'details',
      '#title' => t('Referral Condition'),
      '#description' => t('Referral Condition settings.'),
      '#open' => TRUE,
    );
    $match_email = $config->get('match_email');
    $form['referral_condition']['match_email'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Match email'),
      '#description' => $this->t('Match email id for validating referral'),
      '#default_value' => isset($match_email) ? $match_email : TRUE,
    ];
    $before_month = $config->get('before_month');
    $form['referral_condition']['before_month'] = [
      '#type' => 'number',
      '#title' => $this->t('Month referr before'),
      '#description' => $this->t('Month referr before to validate'),
      '#default_value' => isset($before_month) ? $before_month : 6,
      '#size' => 2,
    ];
    $referral_status = $config->get('referral_status');
    $form['referral_condition']['referral_status'] = [
      '#type' => 'select',
      '#title' => $this->t('Referral default status'),
      '#description' => $this->t('Referral default status to validate'),
      '#default_value' => isset($referral_status) ? $referral_status : '',
      '#options' => $this->getOptions('candidate_status'),
    ];
    $error_message = $config->get('error_message');
    $form['referral_condition']['error_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Error message'),
      '#description' => $this->t('Error message for validate'),
      '#default_value' => isset($error_message) ? $error_message : 'error_message',
    ];
    $job_id_prefix = $config->get('job_id_prefix');
    $form['referral_condition']['job_id_prefix'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Job ID Prefix'),
      '#description' => $this->t('Job ID Prefix'),
      '#default_value' => isset($job_id_prefix) ? $job_id_prefix : 'JOB-IN',
    ];
    return parent::buildForm($form, $form_state);
  }


  /**
   * Utility: find term by name and vid.
   * @param null $name
   *  Term name
   * @param null $vid
   *  Term vid
   * @return int
   *  Term id or 0 if none.
   */
  protected function getOptions($name = NULL, $vid = NULL) {
    $options = [];
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', "candidate_status");
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    foreach ($terms as $term) {
      $options[$term->id()] = t('%name', array('%name' => $term->getName()));
    }
    return $options;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $this->config('globant_referral.settings')
     ->set('match_email', $form_state->getValue('match_email'))
     ->set('before_month', $form_state->getValue('before_month'))
     ->set('referral_status', $form_state->getValue('referral_status'))
     ->set('error_message', $form_state->getValue('error_message'))
     ->set('job_id_prefix', $form_state->getValue('job_id_prefix'))
     ->save();
  }
}
