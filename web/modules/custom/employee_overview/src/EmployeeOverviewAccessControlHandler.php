<?php

namespace Drupal\employee_overview;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the employee overview entity type.
 */
class EmployeeOverviewAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view employee overview');

      case 'add':
        return AccessResult::allowedIfHasPermissions($account, ['add employee overview', 'administer employee overview'], 'OR');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['edit employee overview', 'administer employee overview'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete employee overview', 'administer employee overview'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create employee overview', 'administer employee overview'], 'OR');
  }

}
