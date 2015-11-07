<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
  
class EstivoleViewCalendar extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		
		$model = new EstivoleModelCalendar();
		$this->state	= $this->get('State');
		$this->calendar		= $this->get('Item');
		$this->form		= $this->get('Form');
		$this->_dayTimeStartList = EstivoleHelpersHtml::hoursList('0000-00-00', 'jform[daytime_hour_start]');
		$this->_dayTimeEndList = EstivoleHelpersHtml::hoursList('0000-00-00', 'jform[daytime_hour_end]');
		
		$modelDaytime = new EstivoleModelDaytime();
		$this->daytimes = $modelDaytime->listItems();

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