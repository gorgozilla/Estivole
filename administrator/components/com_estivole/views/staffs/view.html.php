<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 

require_once JPATH_COMPONENT .'/helpers/job.php';
 
class EstivoleViewStaffs extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		
		$this->pagination	= $this->get('Pagination');
		
		$model = new EstivoleModelStaffs();
		$layout = $app->input->get('layout', 'edit');

		switch($layout) {
		  case "edit":
			$this->staff = $model->getItem();

		  default:
			$this->staffs = $model->listItems();
		  break;

		}

		EstivoleHelpersEstivole::addSubmenu('staffs');
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
		JToolbarHelper::title(JText::_('Gestion des bénévoles : Staffs'));
        JToolbarHelper::addNew('staff.add');
		JToolbarHelper::deleteList('Etes-vous sûr de vouloir supprimer le staff?', 'staffs.deleteListStaff');
    }
}