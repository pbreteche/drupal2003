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

    $build['content'] = [
      '#theme' => 'example',
      '#my_var1' => $this->t('It works!'),
    ];

    return $build;
  }

}
