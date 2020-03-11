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
  public function index() {
    $user = \Drupal::currentUser();
    $database = \Drupal::database();

    $query = $database->query('SELECT id, uid, startAt, endAt, description FROM {job_experience} WHERE uid = :uid', [
      ':uid' => $user->id(),
    ]);

    $experiences = $query->fetchAll();

    $build['content'] = [
      '#theme' => 'index',
      '#experiences' => $experiences,
    ];

    return $build;
  }

  public function new() {

  }

}
