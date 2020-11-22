<?php
use GDO\Contact\GDO_ContactMessage;
use GDO\UI\GDT_Back;
use GDO\UI\GDT_Card;
/** @var $message GDO_ContactMessage **/

$user = $message->getUser();
$username = $user ? $user->displayNameLabel() : t('ghost');
$username = html("$username <{$message->getEmail()}>");

$card = GDT_Card::make()->gdo($message);
$card->creatorHeader($message->gdoColumn('cmsg_title'));

$card->content($message->gdoColumn('cmsg_message'));

$card->actions()->addField(GDT_Back::make());

echo $card->renderCell();
