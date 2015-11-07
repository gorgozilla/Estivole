<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 

require_once JPATH_COMPONENT .'/helpers/job.php';
 
class EstivoleViewServices extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		
		$this->pagination	= $this->get('Pagination');
		
		$model = new EstivoleModelServices();
		$layout = $app->input->get('layout', 'edit');

		switch($layout) {
		  case "edit":
			$this->service = $model->getItem();

		  default:
			$this->services = $model->listItems();
		  break;

		}

		EstivoleHelpersEstivole::addSubmenu('services');
		$this->sidebar = JHtmlSidebar::render();

		$this->addToolbar();

		//display
		return parent::display($tpl);
	}

    /**
     * Add the page title and toolbar.
     */
    protected function addToolbar()
    {
        // Get the toolbar object instance
        $bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('Gestion des bénévoles : Secteurs'));
        JToolbarHelper::addNew('service.add');
		JToolbarHelper::deleteList('Etes-vous sûr de vouloir supprimer le secteur? Ceci supprimera également toutes les tranches horaires alloues à ce dernier. Alors?', 'services.deleteListService');
    }
}