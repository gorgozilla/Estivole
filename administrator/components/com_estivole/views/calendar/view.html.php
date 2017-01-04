<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/daytimes.php';
  
class EstivoleViewCalendar extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		
		$model = new EstivoleModelCalendar();
		$modelDaytime = new EstivoleModelDaytime();
		$modelDaytimes = new EstivoleModelDaytimes();
		$this->state	= $this->get('State');
		$this->calendar		= $this->get('Item');
		$this->form		= $this->get('Form');
		
		$this->daytimes = $modelDaytime->listItems();

		for($i=0; $i<count($this->daytimes); $i++){
			$this->daytimes[$i]->totalDaytimes=$modelDaytimes->getDaytimesByDaytime($this->daytimes[$i]->daytime_day);		
			$this->daytimes[$i]->filledQuota = count($modelDaytime->getQuotasByDaytimeDay($this->daytimes[$i]->daytime_day));			
		}
		
		EstivoleHelpersEstivole::addSubmenu('calendars');
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
		JToolbarHelper::title(JText::_('Gestion des bénévoles : Editer un calendrier'));
		JToolbarHelper::apply('calendar.apply');
		JToolbarHelper::save('calendar.save');
		JToolbarHelper::cancel('calendar.cancel');
	}
}