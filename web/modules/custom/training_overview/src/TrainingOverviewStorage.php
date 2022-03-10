<?php

namespace Drupal\training_overview;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\training_overview\Entity\TrainingOverviewInterface;

/**
 * Defines the storage handler class for Training overview entities.
 *
 * This extends the base storage class, adding required special handling for
 * Training overview entities.
 *
 * @ingroup training_overview
 */
class TrainingOverviewStorage extends SqlContentEntityStorage implements TrainingOverviewStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(TrainingOverviewInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {training_overview_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {training_overview_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
