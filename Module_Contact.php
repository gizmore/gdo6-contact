<?php
namespace GDO\Contact;

use GDO\Core\Module;
use GDO\Mail\GDO_Email;
use GDO\Template\GDO_Bar;
use GDO\Type\GDO_Checkbox;
use GDO\UI\GDO_Link;
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
			GDO_Checkbox::make('contact_captcha')->initial('1'),
			GDO_Checkbox::make('member_captcha')->initial('1'),
			GDO_Email::make('contact_mail')->initial(GWF_BOT_EMAIL)->required(),
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
	public function hookLeftBar(GDO_Bar $navbar)
	{
		$navbar->addField(GDO_Link::make('link_contact')->href(href('Contact', 'Form')));
	}

}
