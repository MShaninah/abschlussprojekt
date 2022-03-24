<?php

namespace Drupal\employee_overview\EventSubscriber;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\employee_overview\Event\EmployeeOverviewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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
  protected $messenger;

  /**
   * @var DateFormatterInterface
   */
  private $date_formatter;

  /**
   * Constructs event subscriber.
   *
   * @param MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(MessengerInterface $messenger, DateFormatterInterface $date_formatter) {
    $this->messenger = $messenger;
    $this->date_formatter = $date_formatter;
  }

  /**
   * Kernel request event handler.
   *
   * @param GetResponseEvent $event
   *   Response event.
   */
  public function onKernelRequest(GetResponseEvent $event) {
    $this->messenger->addStatus(__FUNCTION__);
  }

  /**
   * Kernel response event handler.
   *
   * @param FilterResponseEvent $event
   *   Response event.
   */
  public function onKernelResponse(FilterResponseEvent $event) {
    $this->messenger->addStatus(__FUNCTION__);
  }

  /**
   * Subscribe to the user login event dispatched.
   *
   * @param EmployeeOverviewEvent $event
   *   Response event.
   */

  public function onUserLogin(EmployeeOverviewEvent $event) {
    $last_logged_in = $this->date_formatter->format($event->account->getLastLoginTime(), 'short');
    $currentUserName = $event->account->getAccountName();
    $command = escapeshellcmd('python3 ./drupal.py');
    $output = shell_exec($command);
    $response_array = ['user_name' => $currentUserName];
    \Drupal::state()->set('User', $currentUserName);
    if (empty($last_logged_in)) {
      $last_logged_in = 'Never';
    }

    $this->messenger
      ->addStatus($this->t('<strong>Hey there</strong>: %name.',
        [
          '%name' => $currentUserName,
        ]
      ))
      ->addStatus($this->t('<strong>You last logged in</strong>: %last_logged_in',
        [
          '%last_logged_in' => $last_logged_in
        ]
      ));
  }


  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['onKernelRequest'],
      KernelEvents::RESPONSE => ['onKernelResponse'],
      EmployeeOverviewEvent::EVENT_NAME => ['onUserLogin']
    ];
  }

}
