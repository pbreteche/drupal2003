<?php

namespace Drupal\forecast\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Forecast settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'forecast_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['forecast.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['default_city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default city'),
      '#default_value' => $this->config('forecast.settings')->get('default_city'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('#^[A-Z]#', $form_state->getValue('default_city'))) {
      $form_state->setErrorByName('default_city', $this->t('Commencez par une majuscule'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('forecast.settings')
      ->set('default_city', $form_state->getValue('default_city'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
