<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/service.php';
require_once JPATH_COMPONENT . '/models/calendar.php';
require_once JPATH_COMPONENT . '/helpers/estivole.php';

class EstivoleViewDaytime extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		
		$model = new EstivoleModelDaytime();
		$modelService = new EstivoleModelService();
		$this->daytimes = $model->listItems();
		$this->daytime = $app->input->get('daytime', null);
		
		$modelCalendar = new EstivoleModelCalendar();
		$this->calendar	= $modelCalendar->getItem($this->daytime->calendar_id);
		
		for($i=0; $i<count($this->daytimes); $i++){
			$this->daytimes[$i]->filledQuota = count($model->getQuotasByDaytimeId($this->daytimes[$i]->daytime_id));
		}
		
		EstivoleHelpersEstivole::addSubmenu('daytime');
		$this->sidebar = JHtmlSidebar::render();
		
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