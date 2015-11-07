<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 

class EstivoleViewDefault extends JViewLegacy
{
	function display($tpl=null)
	{
		JFactory::getDocument()->setTitle('Estivale Open Air 2015 - Espace Bénévoles');
		
		//display
		return parent::display($tpl);
	}
}