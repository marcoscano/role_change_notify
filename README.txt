Description
-----------

This module enables sites to automatically send customized email
notifications to users when a new role is assigned to them.

It has a configuration page at admin/config/people/role_change_notify.

Note for users from D7:
Compared to the D7 version of this module, the integration with the module
Trigger is not yet ported.

Installation & Usage
--------------------

1. Install and enable the module as usual.

2. Configure the module under Configuration >> User interface >> Role Change Notifications
   - Enable notifications on each desired role
   - (Optionally) Customize the notification subject and text, going to the page
   admin/config/people/accounts.

4. Ensure your site's "E-mail address" setting is defined, which is
   used as the "From" header for all outgoing emails. You can find the
   setting on the Configuration >> System >> Site information page, under the
   "General settings" set of choices. If this is not set, this module will not
   be able to send notification emails.

5. You should optionally install and enable the
   - token module, http://drupal.org/project/token, which will provide many more
     tokens for your message and much help when you're using them. If you are using tokens, you can
     include in your message the custom token: [role_change_notify:role_added], in addition to all
     standard tokens from drupal core and contrib modules.
