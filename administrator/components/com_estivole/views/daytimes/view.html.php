<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/helpers/estivole.php';
 
class EstivoleViewDaytimes extends JViewLegacy
{
	function display($tpl=null)
	{
		$app = JFactory::getApplication();
		$this->state	= $this->get('State');
		$this->pagination	= $this->get('Pagination');
		$this->searchterms	= $this->state->get('filter.search');

		//retrieve task list from model
		$model = new EstivoleModelDaytimes();
		$this->member_daytimes = $model->listItems();
		
		EstivoleHelpersEstivole::addSubmenu('daytimes');
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

		JToolbarHelper::title(JText::_('Gestion des bénévoles : Inscriptions'));
               
        // if ($canDo->get('core.admin'))
        // {
            JToolbarHelper::addNew('daytime.add');
        // }
    }
}