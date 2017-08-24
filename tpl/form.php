<?php
use GDO\Contact\Module_Contact;
use GDO\Form\GDO_Form;
use GDO\Template\GDO_Box;

$form instanceof GDO_Form;
$module = Module_Contact::instance();
?>
<?php 
GDO_Box::make()->title('GELLI')->html('TEST')->render()->add($form->render());
?>
