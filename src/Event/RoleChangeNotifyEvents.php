<?php

/**
 * @file
 * Contains \Drupal\role_change_notify\Event\RoleChangeNotifyEvents.
 */

namespace Drupal\role_change_notify\Event;

/**
 * Defines events for the module role_change_notify.
 */
final class RoleChangeNotifyEvents {

  /**
   * Name of the event fired when updating a user entity.
   *
   * @Event
   *
   * @see \Drupal\role_change_notify\Event\RoleChangeNotifyUserUpdateEvent
   *
   * @var string
   */
  const USERUPDATE = 'role_change_notify.user_updated';

}
