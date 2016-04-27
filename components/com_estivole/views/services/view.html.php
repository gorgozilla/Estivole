<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/services.php';
 
class EstivoleViewServices extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$model = new EstivoleModelServices();
		$this->services = $model->listItems(true);

		//display
		return parent::display($tpl);
	}
}