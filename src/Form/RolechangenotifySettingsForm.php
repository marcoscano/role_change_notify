<?php

/**
 * @file
 * Contains \Drupal\role_change_notify\Form\RolechangenotifySettingsForm.
 */

namespace Drupal\role_change_notify\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PrevnextSettingsForm.
 *
 * @package Drupal\role_change_notify\Form
 */
class RolechangenotifySettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'role_change_notify_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['role_change_notify.settings'];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $roles = user_roles(TRUE);
    unset($roles[\Drupal\user\RoleInterface::AUTHENTICATED_ID]);

    if (empty($roles)) {
      $form['noroles'] = [
        '#markup' => '<p><em>' . t("No roles have been set up except Authenticated User. \nPlease set up additional roles if you want to use role notification.") . '</em></p>',
      ];
      return parent::buildForm($form, $form_state);
    }

    $config = $this->config('role_change_notify.settings');
    $form['instructions'] = [
      '#markup' => '<p><strong>' . $this->t('Select roles for which notification should be sent:') . '</strong></p>',
    ];
    foreach ($roles as $roleid => $roleobject) {
      $form["role_change_notify_{$roleid}"] = [
        '#type' => 'checkbox',
        '#title' => $roleobject->get('label'),
        '#default_value' => $config->get("role_change_notify_{$roleid}"),
      ];
    }
    $account_page_link = \Drupal\Core\Link::createFromRoute($this->t('Account settings'), 'entity.user.admin_form')->toString();
    $form['settings_info'] = [
      '#markup' => '<p>' . t('E-mail content (subject and body text) can be configured on the !account_settings_link page.', ['!account_settings_link' => $account_page_link]) . '</p>',
    ];

    return parent::buildForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the config values.
    $config = $this->config('role_change_notify.settings');
    foreach ($form_state->getValues() as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
