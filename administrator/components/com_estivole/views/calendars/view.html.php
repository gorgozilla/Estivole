<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/helpers/estivole.php';
require_once JPATH_COMPONENT .'/helpers/job.php';
require_once JPATH_COMPONENT .'/helpers/html.php';

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
        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Calendriers'));
		JToolbarHelper::addNew('calendar.add');
		JToolbarHelper::deleteList('Etes-vous sûr de vouloir supprimer le(s) calendrier(s)? Ceci supprimera également toutes les dates et tranches horaires attribuées à ce dernier. Alors?', 'calendars.deleteListCalendar');
    }
}