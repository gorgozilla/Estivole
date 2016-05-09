<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/service.php';
require_once JPATH_COMPONENT . '/models/calendar.php';
  
class EstivoleViewDaytime extends JViewLegacy
{
	function display($tpl=null)
	{

		$app = JFactory::getApplication();
		
		$model = new EstivoleModelDaytime();
		$modelService = new EstivoleModelService();

		$this->daytimes = $model->listItems();
		
		for($i=0; $i<count($this->daytimes); $i++){
			$this->daytimes[$i]->filledQuota = count($model->getQuotasByDaytime($this->daytimes[$i]->daytime_id));
		}
		
		$this->state	= $this->get('State');
		$this->daytime	= $this->daytimes[0];
		$this->form		= $this->get('Form');
		$modelCalendar = new EstivoleModelCalendar();
		$this->calendar	= $modelCalendar->getItem($this->daytime->calendar_id);
		
		$this->addToolbar();

		//display
		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Editer un jour/horaire'));

		JToolbarHelper::apply('daytime.apply');
		JToolbarHelper::save('daytime.save');
		JToolbarHelper::cancel('calendar.edit');
	}
}