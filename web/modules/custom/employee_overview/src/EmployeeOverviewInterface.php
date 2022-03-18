<?php

namespace Drupal\employee_overview;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining an employee overview entity type.
 */
interface EmployeeOverviewInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the employee overview title.
   *
   * @return string
   *   Title of the employee overview.
   */
  public function getTitle();

  /**
   * Sets the employee overview title.
   *
   * @param string $title
   *   The employee overview title.
   *
   * @return \Drupal\employee_overview\EmployeeOverviewInterface
   *   The called employee overview entity.
   */
  public function setTitle($title);

  /**
   * Gets the employee overview creation timestamp.
   *
   * @return int
   *   Creation timestamp of the employee overview.
   */
  public function getCreatedTime();

  /**
   * Sets the employee overview creation timestamp.
   *
   * @param int $timestamp
   *   The employee overview creation timestamp.
   *
   * @return \Drupal\employee_overview\EmployeeOverviewInterface
   *   The called employee overview entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the employee overview status.
   *
   * @return bool
   *   TRUE if the employee overview is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the employee overview status.
   *
   * @param bool $status
   *   TRUE to enable this employee overview, FALSE to disable.
   *
   * @return \Drupal\employee_overview\EmployeeOverviewInterface
   *   The called employee overview entity.
   */
  public function setStatus($status);

}
