<?php
namespace GDO\Contact;

use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\DB\GDT_CreatedAt;
use GDO\DB\GDT_CreatedBy;
use GDO\Mail\GDT_Email;
use GDO\UI\GDT_Message;
use GDO\DB\GDT_String;
use GDO\User\GDO_User;

final class GDO_ContactMessage extends GDO
{
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDT_AutoInc::make('cmsg_id'),
			GDT_Email::make('cmsg_email')->label('email'),
			GDT_String::make('cmsg_title')->min(3)->max(128)->notNull()->label('title'),
			GDT_Message::make('cmsg_message')->min(2)->max(2048)->notNull()->label('message'),
			GDT_CreatedAt::make('cmsg_created_at'),
			GDT_CreatedBy::make('cmsg_user_id')->cascadeNull(),
		);
	}
	
	/**
	 * @return GDO_User
	 */
	public function getUser() { return $this->getValue('cmsg_user_id'); }
	
	public function getEmail() { return $this->getVar('cmsg_email'); }
	public function getTitle() { return $this->getVar('cmsg_title'); }
	public function getMessage() { return $this->getVar('cmsg_message'); }
	public function getCreatedAt() { return $this->getVar('cmsg_created_at'); }
	
	public function href_link_message() { return href('Contact', 'Message', '&id='.$this->getID()); }
}
