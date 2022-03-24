<?php
namespace Drupal\employee_overview\Event;

use Drupal\user\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Event that is fired when a user logs in.
 */
class EmployeeOverviewEvent extends Event
{
  const EVENT_NAME = 'custom_events_user_login';

  /**
   * The user account.
   *
   * @var UserInterface
   */
  public $account;

  /**
   * Constructs the object.
   *
   * @param UserInterface $account
   *   The account of the user logged in.
   */
  public function __construct(UserInterface $account) {
    $this->account = $account;
  }
}
