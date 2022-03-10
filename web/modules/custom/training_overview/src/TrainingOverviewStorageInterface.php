<?php

namespace Drupal\training_overview;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface TrainingOverviewStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Training overview revision IDs for a specific Training overview.
   *
   * @param \Drupal\training_overview\Entity\TrainingOverviewInterface $entity
   *   The Training overview entity.
   *
   * @return int[]
   *   Training overview revision IDs (in ascending order).
   */
  public function revisionIds(TrainingOverviewInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Training overview author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Training overview revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
