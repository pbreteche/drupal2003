<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for 01 Hello World! routes.
 */
class HelloWorldController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['header'] = [
      '#theme' => 'example',
      '#my_var1' => $this->t('It works in header!'),
    ];

    $build['content'] = [
      '#theme' => 'example',
      '#my_var1' => $this->t('It works!'),
    ];

    $build['footer'] = [
      '#theme' => 'example',
      '#my_var1' => [
        '#theme' => 'item_list',
        '#items' => ['pomme', 'poire', 'cerise'],
      ]
    ];

    return $build;
  }

  public function week() {
    $nextDay = new \DateTimeImmutable('tomorrow');
    $lastDay = new \DateTime('next sunday');
    $weekDays = [];

    while ($nextDay < $lastDay) {
      $weekDays[] = $nextDay;
      $nextDay = $nextDay->modify('next day');
    }

    return [
      '#theme' => 'week',
      '#week_days' => $weekDays,
    ];
  }
}
