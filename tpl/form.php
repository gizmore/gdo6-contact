<?php
use GDO\Contact\Module_Contact;
use GDO\Form\GDT_Form;
use GDO\Template\GDT_Box;

$form instanceof GDT_Form;
$module = Module_Contact::instance();
?>
<?php 
GDT_Box::make()->title('GELLI')->html('TEST')->render()->add($form->render());
?>
