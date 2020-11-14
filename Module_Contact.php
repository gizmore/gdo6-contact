<?php
namespace GDO\Contact;

use GDO\Core\GDO_Module;
use GDO\Mail\GDT_Email;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\UI\GDT_Page;

/**
 * Contact Module.
 * Provides contact to admins, and
 * Write users a mail without spoiling their email.
 * @author gizmore
 * @license MIT
 */
final class Module_Contact extends GDO_Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() { return $this->loadLanguage('lang/contact'); }
	public function getClasses() { return ['GDO\Contact\GDO_ContactMessage']; }
	public function href_administrate_module() { return href('Contact', 'Messages'); }
	public function getDependencies() { return ['Profile']; }
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('contact_captcha')->initial('1'),
			GDT_Checkbox::make('member_captcha')->initial('1'),
			GDT_Email::make('contact_mail')->initial(GWF_ADMIN_EMAIL)->required(),
			GDT_Email::make('contact_mail_sender')->initial(GWF_BOT_EMAIL)->notNull(),
			GDT_Email::make('contact_mail_receiver'),
		    GDT_Checkbox::make('hook_left_bar')->initial('1'),
		    GDT_Checkbox::make('hook_right_bar')->initial('0'),
		);
	}

	##############
	### Config ###
	##############
	public function cfgCaptchaGuest() { return $this->getConfigValue('contact_captcha', '1'); }
	public function cfgCaptchaMember() { return $this->getConfigValue('member_captcha', '0'); }
	public function cfgCaptchaEnabled() { return GDO_User::current()->isMember() ? $this->cfgCaptchaMember() : $this->cfgCaptchaGuest(); }
	public function cfgEmail() { return $this->getConfigVar('contact_mail'); }
	public function cfgEmailSender() { return $this->getConfigVar('contact_mail_sender'); }
	public function cfgEmailReceiver() { return $this->getConfigVar('contact_mail_receiver'); }
	public function cfgHookLeftBar() { return $this->getConfigValue('hook_left_bar'); }
	public function cfgHookRightBar() { return $this->getConfigValue('hook_right_bar'); }
	
	##############
	### Navbar ###
	##############
	public function onInitSidebar()
	{
// 	    if ($this->cfgHookLeftBar())
	    {
	        GDT_Page::$INSTANCE->leftNav->addField(GDT_Link::make('link_contact')->href(href('Contact', 'Form')));
	    }
// 	    if ($this->cfgHookRightBar())
// 	    {
// 	        if (GDO_User::current()->isStaff())
// 	        {
// 	            $navbar = GDT_Page::$INSTANCE->rightNav;
//     	        $navbar->addField(GDT_Link::make('link_contact_messages')->href(href('Contact', 'Messages')));
// 	        }
// 	    }
	}
	
}
