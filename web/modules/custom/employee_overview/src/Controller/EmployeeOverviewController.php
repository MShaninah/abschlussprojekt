<?php

namespace Drupal\employee_overview\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Employee Overview routes.
 */
class EmployeeOverviewController extends ControllerBase {

   public function currentUser() {
    $currentUserName = \Drupal::state()->get('User');
    $response_array = ['user_name' => $currentUserName];
    return new JsonResponse($response_array, 200, ['content-type": "application/hal+json'] );
  }

}
