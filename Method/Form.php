<?php
namespace GDO\Contact\Method;

use GDO\Captcha\GDT_Captcha;
use GDO\Contact\ContactMessage;
use GDO\Contact\Module_Contact;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Mail\Mail;
use GDO\User\User;

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
		$form->addFields(ContactMessage::table()->getGDOColumns($this->contactFields()));
		$form->getField('cmsg_email')->initial(User::current()->getMail());
		if (Module_Contact::instance()->cfgCaptchaEnabled())
		{
			$form->addField(GDT_Captcha::make());
		}
		$form->addField(GDT_Submit::make()->label('btn_send'));
		$form->addField(GDT_AntiCSRF::make());
	}
	
	public function formValidated(GDT_Form $form)
	{
		$message = ContactMessage::blank($form->getFormData())->insert();
		$this->sendMail($message);
		$this->resetForm();
		return \GDO\Template\Message::message('msg_contact_mail_sent', [sitename()])->add($this->renderPage());
	}
	
	public function sendMail(ContactMessage $message)
	{
		foreach (User::withPermission('staff') as $user)
		{
			$staffname = $user->displayName();
			$sitename = sitename();
			$email = htmlspecialchars($message->getEmail());
			$username = $message->getUser()->displayName();
			$title = htmlspecialchars($message->getTitle());
			$text = htmlspecialchars($message->getMessage());
			
			$mail = new Mail();
			$mail->setSender(GWF_BOT_EMAIL);
			$mail->setSenderName(GWF_BOT_EMAIL);
			$mail->setSubject(t('mail_subj_contact', [$sitename]));
			$mail->setReply($message->getEmail());
			$args = [$staffname, $sitename, $username, $email, $title, $text];
			$mail->setBody(t('mail_body_contact', $args));
			$mail->sendToUser($user);
		}
	}
	
}