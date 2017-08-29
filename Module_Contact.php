<?php
namespace GDO\Contact;

use GDO\Core\Module;
use GDO\Mail\GDT_Email;
use GDO\Template\GDT_Bar;
use GDO\Type\GDT_Checkbox;
use GDO\UI\GDT_Link;
use GDO\User\User;
/**
 * Contact Module.
 * Provides contact to admins, and
 * Write users a mail without spoiling their email.
 * @author gizmore
 * @license MIT
 */
final class Module_Contact extends Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() { return $this->loadLanguage('lang/contact'); }
	public function getClasses() { return ['GDO\Contact\ContactMessage']; }
	public function href_administrate_module() { return $this->getMethodHREF('Messages'); }
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('contact_captcha')->initial('1'),
			GDT_Checkbox::make('member_captcha')->initial('1'),
			GDT_Email::make('contact_mail')->initial(GWF_BOT_EMAIL)->required(),
		);
	}

	##############
	### Config ###
	##############
	public function cfgCaptchaGuest() { return $this->getConfigValue('contact_captcha', '1'); }
	public function cfgCaptchaMember() { return $this->getConfigValue('member_captcha', '0'); }
	public function cfgCaptchaEnabled() { return User::current()->isMember() ? $this->cfgCaptchaMember() : $this->cfgCaptchaGuest(); }
	
	##############
	### Navbar ###
	##############
	public function hookLeftBar(GDT_Bar $navbar)
	{
		$navbar->addField(GDT_Link::make('link_contact')->href(href('Contact', 'Form')));
	}

}
