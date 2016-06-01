<?php

/**
 * @file
 * Contains \Drupal\role_change_notify\Event\RoleChangeNotifyUserUpdateEvent.
 */

namespace Drupal\role_change_notify\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Wraps a user-update event for event listeners.
 */
class RoleChangeNotifyUserUpdateEvent extends Event {

  /**
   * User entity.
   *
   * @var \Drupal\Core\Entity\Entity
   */
  protected $user;

  /**
   * Constructs an import event object.
   *
   * @param \Drupal\Core\Entity\Entity $user
   *   The user entity.
   */
  public function __construct(\Drupal\Core\Entity\Entity $user) {
    $this->user = $user;
  }

  /**
   * Gets the user entity.
   *
   * @return \Drupal\Core\Entity\Entity
   *   The user entity involved.
   */
  public function getUser() {
    return $this->user;
  }

}
