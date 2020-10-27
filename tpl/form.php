<?php
use GDO\Form\GDT_Form;
use GDO\UI\GDT_Panel;
/** @var $form GDT_Form **/
?>
<?php 
GDT_Panel::make()->title('GELLI')->html('TEST')->render()->add($form->render());
?>
