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

final class Form extends MethodForm
{
    public function isUserRequired() { return false; }
    
	public function contactFields()
	{
		return ['cmsg_email', 'cmsg_title', 'cmsg_message'];
	}
	
	public function createForm(GDT_Form $form)
	{
		$this->title(t('ft_contact_form', [sitename()]));
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
		$this->sendMail($message);
		$this->resetForm();
		return $this->message('msg_contact_mail_sent', [sitename()])->add($this->renderPage());
	}
	
	public function sendMail(GDO_ContactMessage $message)
	{
		foreach (GDO_User::withPermission('staff') as $user)
		{
			$staffname = $user->displayName();
			$sitename = sitename();
			$email = html($message->getEmail());
			$username = $message->getUser()->displayName();
			$title = html($message->getTitle());
			$text = html($message->getMessage());
			
			$mail = Mail::botMail();
			$mail->setSubject(t('mail_subj_contact', [$sitename]));
			$mail->setReply($message->getEmail());
			$args = [$staffname, $sitename, $username, $email, $title, $text];
			$mail->setBody(t('mail_body_contact', $args));
			$mail->sendToUser($user);
		}
	}
	
}