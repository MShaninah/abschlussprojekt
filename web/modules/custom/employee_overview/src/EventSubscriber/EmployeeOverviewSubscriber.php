<?php

namespace Drupal\employee_overview\EventSubscriber;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\employee_overview\Event\EmployeeOverviewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Employee Overview event subscriber.
 */
class EmployeeOverviewSubscriber implements EventSubscriberInterface {
  use StringTranslationTrait;
  /**
   * The messenger.
   *
   * @var MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs event subscriber.
   *
   * @param MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * Subscribe to the user login event dispatched.
   *
   * @param EmployeeOverviewEvent $event
   *   Response event.
   */

  public function onUserLogin(EmployeeOverviewEvent $event) {
    $currentUserName = $event->account->getAccountName();
    $command = escapeshellcmd('python3 ./drupal.py');
    $output = shell_exec($command);
    $response_array = ['user_name' => $currentUserName];
    \Drupal::state()->set('User', $currentUserName);

    $this->messenger
      ->addStatus($this->t('<strong>Hey there</strong>: %name.',
        [
          '%name' => $currentUserName,
        ]
      ));
  }


  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      EmployeeOverviewEvent::EVENT_NAME => ['onUserLogin']
    ];
  }

}
