<?php
use GDO\Contact\ContactMessage;
use GDO\Core\Application;
use GDO\UI\GDO_Back;

$message instanceof ContactMessage;
$user = $message->getUser();
$username = $user ? $user->displayName() : t('guest');
$username = html("$username <{$message->getEmail()}>");
?>
<md-card>
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline"><?= t('card_title_contact_message', [sitename()]); ?></span>
      <span class="md-subhead"><?= t($message->getCreatedAt()); ?></span>
    </md-card-title-text>
  </md-card-title>
  <md-card-content layout="column" layout-align="space-between">
    <div><?= t('msg_by', [$username]); ?></div>
    <div><?= t('msg_title', [html($message->getTitle())]); ?></div>
    <hr/>
    <div><?= html($message->getMessage()); ?></div>
  </md-card-content>
  <md-card-actions layout="row" layout-align="end center">
    <?= GDO_Back::make()->renderCell(); ?>
  </md-card-actions>
</md-card>
