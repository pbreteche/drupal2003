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

  const BASE_CITY_API = 'https://geo.api.gouv.fr/communes?fields=nom&format=json&nom=';

  /**
   * {@inheritdoc}
   */
  public function build() {
    $city = $this->getConfiguration()['city'];
    if (empty($city)) {
      $city = \Drupal::config('forecast.settings')->get('default_city');
    }

    $build['content'] = [
      '#markup' => $this->t('La météo de '.htmlspecialchars($city)),
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

    $client = \Drupal::httpClient();

    $response = $client->get(self::BASE_CITY_API.urlencode($form_state->getValue('city')));

    $cities = json_decode($response->getBody());

    $found = [];

    foreach($cities as $city) {
      if (1 == $city->_score) {
        $found[] = $city;
      }
    }

    if(empty($found)) {
      $form_state->setErrorByName('city', $this->t('No city found with this name'));
    }

    if(1 === count($found)) {
      $form_state->setValue('city', $found[0]->nom);
    }

    if(1 < count($found)) {
      \Drupal::logger('forecast')->warning('Multiple cities with same name: '.$form_state->getValue('city'));
      \Drupal::messenger()->addMessage('Multiple cities with same name', 'warning');
    }
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue(
      'city',
      $form_state->getValue('city')
    );
  }
}
