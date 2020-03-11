<?php

namespace Drupal\job_experience\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Job Experience form.
 */
class NewForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'job_experience_new';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['start_at'] = [
      '#type' => 'date',
      '#title' => $this->t('Start at'),
      '#required' => TRUE,
    ];

    $form['end_at'] = [
      '#type' => 'date',
      '#title' => $this->t('End at'),
      '#required' => FALSE,
    ];

    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (mb_strlen($form_state->getValue('description')) < 10) {
      $form_state->setErrorByName('description', $this->t('Description should be at least 10 characters.'));
    }

    $now = time();
    $start_at = strtotime($form_state->getValue('start_at'));
    if ($now < $start_at) {
      $form_state->setErrorByName('start_at', $this->t('Start date should be in past'));
    }

    if ($form_state->getValue('end_at')){
      $end_at = strtotime($form_state->getValue('end_at'));
      if ($end_at < $start_at) {
        $form_state->setErrorByName('end_at', $this->t('End date should be after start date'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $database = \Drupal::database();

    $database->insert('job_experience')->fields([
      'uid' => \Drupal::currentUser()->id(),
      'startAt' => strtotime($form_state->getValue('start_at')),
      'endAt' => strtotime($form_state->getValue('end_at')),
      'description' => $form_state->getValue('description')
    ])->execute();

    $this->messenger()->addStatus($this->t('Job experience created'));
    $form_state->setRedirect('job_experience.index');
  }

}
