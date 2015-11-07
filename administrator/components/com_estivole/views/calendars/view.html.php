<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/helpers/estivole.php';

class EstivoleViewCalendars extends JViewLegacy
{
	function display($tpl=null)
	{

		$app = JFactory::getApplication();
		$model = new EstivoleModelCalendars();
		$layout = $app->input->get('layout', 'default');
		$this->calendars = $model->listItems();
		
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
        // $canDo  = EstivoleHelpersEstivole::getActions();

        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Calendriers'));
               
        // if ($canDo->get('core.admin'))
        // {
            JToolbarHelper::addNew('calendar.add');
        // }
    }
}