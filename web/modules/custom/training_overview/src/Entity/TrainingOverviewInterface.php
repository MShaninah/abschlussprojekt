<?php

namespace Drupal\training_overview\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Training overview entities.
 *
 * @ingroup training_overview
 */
interface TrainingOverviewInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Training overview name.
   *
   * @return string
   *   Name of the Training overview.
   */
  public function getName();

  /**
   * Sets the Training overview name.
   *
   * @param string $name
   *   The Training overview name.
   *
   * @return \Drupal\training_overview\Entity\TrainingOverviewInterface
   *   The called Training overview entity.
   */
  public function setName($name);

  /**
   * Gets the Training overview creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Training overview.
   */
  public function getCreatedTime();

  /**
   * Sets the Training overview creation timestamp.
   *
   * @param int $timestamp
   *   The Training overview creation timestamp.
   *
   * @return \Drupal\training_overview\Entity\TrainingOverviewInterface
   *   The called Training overview entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Training overview revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Training overview revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\training_overview\Entity\TrainingOverviewInterface
   *   The called Training overview entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Training overview revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Training overview revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\training_overview\Entity\TrainingOverviewInterface
   *   The called Training overview entity.
   */
  public function setRevisionUserId($uid);

}
