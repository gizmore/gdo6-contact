<?php
namespace GDO\Contact;

use GDO\DB\GDO;
use GDO\DB\GDO_AutoInc;
use GDO\DB\GDO_CreatedAt;
use GDO\DB\GDO_CreatedBy;
use GDO\Mail\GDO_Email;
use GDO\Type\GDO_Message;
use GDO\Type\GDO_String;
use GDO\User\User;

final class ContactMessage extends GDO
{
	public function gdoCached() { return false; }
	
	public function gdoColumns()
	{
		return array(
			GDO_AutoInc::make('cmsg_id'),
			GDO_Email::make('cmsg_email')->notNull()->label('email'),
			GDO_String::make('cmsg_title')->min(3)->max(128)->notNull()->label('title'),
			GDO_Message::make('cmsg_message')->min(2)->max(2048)->notNull()->label('message'),
			GDO_CreatedAt::make('cmsg_created_at'),
			GDO_CreatedBy::make('cmsg_user_id')->cascadeNull(),
		);
	}
	
	/**
	 * @return User
	 */
	public function getUser() { return $this->getValue('cmsg_user_id'); }
	
	public function getEmail() { return $this->getVar('cmsg_email'); }
	public function getTitle() { return $this->getVar('cmsg_title'); }
	public function getMessage() { return $this->getVar('cmsg_message'); }
	public function getCreatedAt() { return $this->getVar('cmsg_created_at'); }
	
	public function href_link_message() { return href('Contact', 'Message', '&id='.$this->getID()); }
}
