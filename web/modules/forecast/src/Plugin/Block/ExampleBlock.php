<?php

namespace Drupal\forecast\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\ConfigFormBaseTrait;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "forecast_display",
 *   admin_label = @Translation("Forecast display"),
 *   category = @Translation("Forecast")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $city = $this->getConfiguration()['city'];
    if (empty($city)) {
      $city = \Drupal::config('forecast.settings')->get('default_city');
    }

    $build['content'] = [
      '#markup' => $this->t('La météo de '.$city),
    ];
    return $build;
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('city'),
      '#default_value' => $this->getConfiguration()['city'],
    ];

    return $form;
  }

  public function blockValidate($form, FormStateInterface $form_state) {
    if (!preg_match('#^[A-Z]#', $form_state->getValue('city'))) {
      $form_state->setErrorByName('city', $this->t('Commencez par une majuscule'));
    }
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue(
      'city',
      $form_state->getValue('city')
    );
  }
}
