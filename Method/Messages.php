<?php
namespace GDO\Contact\Method;

use GDO\Core\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;

/**
 * List contact messages for staff members.
 * @author gizmore
 */
final class Messages extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	public function gdoTable()
	{
	    return GDO_ContactMessage::table();
	}
	
	public function getQuery()
	{
	    return $this->gdoTable()->select();
	}
	
	public function gdoHeaders()
	{
		$gdo = $this->gdoTable();
		return [
			$gdo->gdoColumn('cmsg_id'),
			$gdo->gdoColumn('cmsg_created_at'),
			$gdo->gdoColumn('cmsg_user_id'),
			$gdo->gdoColumn('cmsg_email'),
			$gdo->gdoColumn('cmsg_title'),
			GDT_Button::make('link_message'),
		];
	}
	
}
