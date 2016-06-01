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

    $config = $this->config('role_change_notify.settings');

//    $form['role_change_notify_enabled_nodetypes'] = array(
//      '#title' => $this->t('Enabled Node Types'),
//      '#description' => $this->t('Check node types enabled for Previous/Next'),
//      '#type' => 'checkboxes',
//      '#options' => node_type_get_names(),
//      '#default_value' => $config->get('role_change_notify_enabled_nodetypes'),
//    );

    return parent::buildForm($form, $form_state);


    /**
     * @FIXME lo siguiente es el ejemplo de D7 para este form:
     */
//    $roles = user_roles(TRUE);
//
//    unset($roles[DRUPAL_AUTHENTICATED_RID]);
//    if (sizeof($roles) == 0) {
//      $form['noroles'] = array(
//        '#markup' => '<p><em>' . t("No roles have been set up except Authenticated User. \nPlease set up additional roles if you want to use role notification.") . '</em></p>',
//      );
//      return $form;
//    }
//    $form['instructions'] = array(
//      '#markup' => '<p><strong>' . t('Select roles for which notification should be sent:') . '</strong></p>',
//    );
//    foreach ($roles as $roleid => $rolename) {
//      $form["role_change_notify_{$roleid}"] = array(
//        '#type' => 'checkbox',
//        '#title' => $rolename,
//        '#default_value' => variable_get("role_change_notify_{$roleid}", FALSE),
//      );
//    }
//    $form['settings_info'] = array(
//      '#markup' => '<p>' . t('E-mail content (subject and body text) can be configured on the !account_settings_link page.', array('!account_settings_link' => l(t('Account settings'), 'admin/config/people/accounts', array('fragment' => 'edit-role-change-notify')))) . '</p>',
//    );
//
//    return system_settings_form($form);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the config values.
    $this->config('role_change_notify.settings')
//      ->set('role_change_notify_enabled_nodetypes', $form_state->getValue('role_change_notify_enabled_nodetypes'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
