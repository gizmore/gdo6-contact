<?php
namespace GDO\Contact\Method;

use GDO\Core\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;

final class Messages extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function getGDO() { return GDO_ContactMessage::table();  }
	
	public function execute()
	{
		return $this->renderNavBar('Contact')->add(parent::execute());
	}
	
	public function getHeaders()
	{
		$gdo = $this->getGDO();
		return array(
			$gdo->gdoColumn('cmsg_id'),
			$gdo->gdoColumn('cmsg_created_at'),
			$gdo->gdoColumn('cmsg_user_id'),
			$gdo->gdoColumn('cmsg_email'),
			$gdo->gdoColumn('cmsg_title'),
			GDT_Button::make('link_message'),
		);
	}
	
	public function getQuery()
	{
		return $this->getGDO()->select('*');
	}
}
