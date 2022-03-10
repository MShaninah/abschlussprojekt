<?php

namespace Drupal\training_overview;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Training overview entity.
 *
 * @see \Drupal\training_overview\Entity\TrainingOverview.
 */
class TrainingOverviewAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\training_overview\Entity\TrainingOverviewInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished training overview entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published training overview entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit training overview entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete training overview entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add training overview entities');
  }


}
