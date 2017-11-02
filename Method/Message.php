<?php
namespace GDO\Contact\Method;

use GDO\Core\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Core\Method;
use GDO\Util\Common;

final class Message extends Method
{
	use MethodAdmin;
	
	public function getPermission() { return 'staff'; }
	
	private $message;
	
	public function init()
	{
		$this->message = GDO_ContactMessage::table()->find(Common::getRequestString('id'));
	}
	
	public function execute()
	{
		return $this->renderNavBar('Contact')->add($this->templateMessage($this->message));
	}
	
	public function templateMessage(GDO_ContactMessage $message)
	{
		return $this->templatePHP('message.php', ['message' => $message]);
	}
	
}
