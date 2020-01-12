<?php
namespace GDO\Contact\Method;

use GDO\Captcha\GDT_Captcha;
use GDO\Contact\GDO_ContactMessage;
use GDO\Contact\Module_Contact;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\Core\GDT_Response;
use GDO\UI\GDT_Panel;
use GDO\Profile\GDT_ProfileLink;
use GDO\UI\GDT_Link;

/**
 * Contact form.
 * Sends mail to staff or single recipient in module config.
 * 
 * @author gizmore
 * @since 3.00
 * @version 6.10
 */
final class Form extends MethodForm
{
	public function isUserRequired() { return false; }
	
	public function contactFields()
	{
		return ['cmsg_email', 'cmsg_title', 'cmsg_message'];
	}
	
	public function execute()
	{
		return GDT_Response::makeWith($this->getInfoPanel())->add(parent::execute());
	}
	
	public function getInfoPanel()
	{
		$names = [];
		foreach (GDO_User::admins() as $admin)
		{
			$names[] = GDT_ProfileLink::make()->forUser($admin)->withNickname()->renderCell();
		}
		$names = implode(',', $names);
		$email = Module_Contact::instance()->cfgEmail();
		$email = GDT_Link::make()->href('mailto:'.$email)->rawLabel($email)->renderCell();
		return GDT_Panel::withHTML(t('contact_info', [$names, $email]));
	}
	
	public function createForm(GDT_Form $form)
	{
		$form->addFields(GDO_ContactMessage::table()->getGDOColumns($this->contactFields()));
		$form->getField('cmsg_email')->initial(GDO_User::current()->getMail());
		if (Module_Contact::instance()->cfgCaptchaEnabled())
		{
			$form->addField(GDT_Captcha::make());
		}
		$form->addField(GDT_Submit::make()->label('btn_send'));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		$message = GDO_ContactMessage::blank($form->getFormData())->insert();
		$this->sendMails($message);
		$this->resetForm();
		return $this->message('msg_contact_mail_sent', [sitename()])->add($this->renderPage());
	}
	
	############
	### Mail ###
	############
	public function sendMails(GDO_ContactMessage $message)
	{
		if ($to = Module_Contact::instance()->cfgEmailReceiver())
		{
			$this->sendSingleMail($to, $message);
		}
		else
		{
			foreach (GDO_User::withPermission('staff') as $user)
			{
				$this->sendMail($user, $message);
			}
		}
	}
	
	private function sendSingleMail($to, GDO_ContactMessage $message)
	{
		$user = GDO_User::blank(array(
			'user_name' => tiso(GWF_LANGUAGE, 'contact_mail_receiver_name', [sitename()]),
			'user_language' => GWF_LANGUAGE,
			'user_email' => $to,
		));
		return $this->sendMail($user, $message);
	}
	
	private function sendMail(GDO_User $user, GDO_ContactMessage $message)
	{
		$module = Module_Contact::instance();

		$sitename = sitename();
		$staffname = $user->displayNameLabel();
		$email = html($message->getEmail());
		$username = $message->getUser()->displayNameLabel();
		$title = html($message->getTitle());
		$text = html($message->getMessage());

		$mail = new Mail();
		$mail->setSender($module->cfgEmailSender());
		$mail->setSenderName(tusr($user, 'contact_mail_sender_name', [$sitename]));
		$mail->setSubject(tusr($user, 'mail_subj_contact', [$sitename]));
		$mail->setReply($message->getEmail());
		$args = [$staffname, $sitename, $username, $email, $title, $text];
		$mail->setBody(tusr($user, 'mail_body_contact', $args));
		$mail->sendToUser($user);
	}

}
