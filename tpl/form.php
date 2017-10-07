<?php
use GDO\Contact\Module_Contact;
use GDO\Form\GDT_Form;
use GDO\UI\GDT_Panel;

$form instanceof GDT_Form;
$module = Module_Contact::instance();
?>
<?php 
GDT_Panel::make()->title('GELLI')->html('TEST')->render()->add($form->render());
?>
