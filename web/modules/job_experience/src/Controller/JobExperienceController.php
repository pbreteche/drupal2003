<?php

namespace Drupal\job_experience\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Job Experience routes.
 */
class JobExperienceController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
