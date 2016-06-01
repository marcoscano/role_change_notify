<?php

/**
 * @file
 * Contains \Drupal\role_change_notify\UserUpdateSubscriber.
 */

namespace Drupal\role_change_notify\EventSubscriber;

use Drupal\Core\Mail\MailManager;
use Drupal\role_change_notify\Event\RoleChangeNotifyEvents;
use Drupal\role_change_notify\Event\RoleChangeNotifyUserUpdateEvent;
use Drupal\user\Entity\Role;
use Egulias\EmailValidator\EmailValidator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Class UserUpdateSubscriber.
 *
 * @package Drupal\role_change_notify
 */
class UserUpdateSubscriber implements EventSubscriberInterface {

  protected $config;
  protected $email_validator;
  protected $logger;
  protected $mail_manager;

  /**
   * Constructs a \Drupal\role_change_notify\UserUpdateSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The factory for configuration objects.
   * @param \Egulias\EmailValidator\EmailValidator $email_validator
   *   The email validator service.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger
   *   The logger service.
   * @param \Drupal\Core\Mail\MailManager $mail_manager
   */
  public function __construct(ConfigFactory $config_factory, EmailValidator $email_validator, LoggerChannelFactoryInterface $logger, MailManager $mail_manager) {
    $this->config = $config_factory;
    $this->email_validator = $email_validator;
    $this->logger = $logger;
    $this->mail_manager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('email.validator'),
      $container->get('logger.factory'),
      $container->get('plugin.manager.mail')
    );
  }


  /**
   * @inheritdoc
   */
  public static function getSubscribedEvents() {
    return [RoleChangeNotifyEvents::USERUPDATE => 'onUserUpdateEvent'];
  }

  /**
   * An user update event has been triggered, act on it.
   *
   * @param \Drupal\role_change_notify\Event\RoleChangeNotifyUserUpdateEvent $event
   */
  public function onUserUpdateEvent(RoleChangeNotifyUserUpdateEvent $event) {

    /**
     * @var \Drupal\user\Entity\User $user
     */
    $user = $event->getUser();

    $new_roles = $user->getRoles();
    $original_roles = $user->original->getRoles();

    if (count($new_roles) > count($original_roles)) {
      // A new role was added.
      $added_roles = array_diff($new_roles, $original_roles);
      $config = $this->config->get('role_change_notify.settings');
      foreach ($added_roles as $roleid) {
        $needs_notification = $config->get("role_change_notify_{$roleid}");
        if ($needs_notification) {
          // @TODO Refactor this to send one email with multiple roles, instead
          // of sending one email per role added, in case it is multiple.
          $role = Role::load($roleid);
          $sent = $this->notifyUser($user, $role->get('label'));
          if ($sent) {
            drupal_set_message(t("User <b>%user</b> notified of added role <b>%role</b>", ['%user' => $user->getUsername(), '%role' => $roleid]));
          }
          else {
            drupal_set_message(t('It was impossible to send the notification of a new role added, please check the error logs for more information.'), 'warning');
          }
        }
      }

    }
  }

  /**
   * Notify the user that one or more roles were added.
   *
   * @param \Drupal\user\Entity\User $user
   *   The fully-loaded user object.
   * @param string $rolename
   *   The role human-readable name.
   *
   * @return bool
   *   TRUE if the email went out correctly, FALSE otherwise.
   */
  private function notifyUser(\Drupal\user\Entity\User $user, $rolename) {
    drupal_set_message('emailing user...');
    $system_config = $this->config->get('system.site');
    $site_email = $system_config->get('mail');
    if (!$this->email_validator->isValid($user->getEmail())) {
      $this->logger->get('role change notify')->log('warning', t("Could not notify the user of the role addition. The email: <b>@email</b> is not valid.", ['@email' => $user->getEmail()]));
      return FALSE;
    }
    elseif (!$this->email_validator->isValid($site_email)) {
      $this->logger->get('role change notify')->log('warning', t("Could not notify the user of the role addition. The site main email: <b>@email</b> is not valid.", ['@email' => $site_email]));
      return FALSE;
    }
    else {
      // Try to send the email.
      /**
       * @TODO @FIXME continue from here.
       */
      $module = '';
      $key = '';
      $to = '';
      $langcode = '';
      $params = [];
      $message = $this->mail_manager->mail($module, $key, $to, $langcode, $params);
      if (!$message['result']) {
        // Email failed. No need to log because the mailmanager already did so.
        return FALSE;
      }
      return TRUE;
    }
//    if (valid_email_address($account->mail) && valid_email_address($from)) {
//      if (module_exists('profile')) {
//        // @todo: remove this, seems unused
//        profile_load_profile($account);
//      }
//      // @todo: helper function.
//      $subject = token_replace(variable_get('role_change_notify_role_added_subject', RCN_SUBJECT_DEFAULT), array('user' => $account));
//      $body = token_replace(variable_get('role_change_notify_role_added_body', RCN_BODY_DEFAULT), array('user' => $account));
//      $language = user_preferred_language($account);
//      $context['from'] = $from;
//      $context['subject'] = $subject;
//      $context['body'] = $body;
//      $context['headers'] = $headers;
//      $params = array('context' => $context);
//      drupal_mail('role_change_notify', 'role_added', $account->mail, $language, $params);
//      drupal_set_message(t("User %user notified of added role %role", array('%user' => $account->name, '%role' => $role)));
//    }

  }

}
