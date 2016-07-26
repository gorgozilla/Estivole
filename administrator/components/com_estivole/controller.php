<?php
require_once JPATH_COMPONENT . '/models/daytime.php';
/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Weblinks Weblink Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 * @since       1.5
 */
class EstivoleController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 * @since   1.5
	 */
	public $daytime_id = null;
	
	public function display($cachable = false, $urlparams = false)
	{	
		// Get the document object.
		$document = JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->get('view', 'members');
		$vFormat = $document->getType();
		$lName   = $this->input->get('layout', 'default', 'string');

		// Get and render the view.
		if ($view = $this->getView($vName, $vFormat))
		{
			// Get the model for the view.
			$model = $this->getModel($vName);

			// Push the model into the view (as default).
			$view->setModel($model, true);
			$view->setLayout($lName);


			// Push document object into the view.
			$view->document = $document;

			$view->display();
		}

		return $this;
	}
	
  public function getCalendarDates()
  {
		$return = array("success"=>false);

		// Get the model for the view.
		$modelDaytime = $this->getModel('daytime');
		$this->daytimes = $modelDaytime->listItems();

  		$return['success'] = true;
  		$return['msg'] = 'Yes';
		$return['calendar_dates'] = $this->daytimes;

		echo json_encode($return);
  }
  
	public function getDaytime()
	{
		// Set the default view name and format from the Request.
		$daytime_id   = $this->input->get('daytime_id');
		$calendar_id   = $this->input->get('calendar_id');
		$daytime_day   = $this->input->get('daytime_day');
		$model = $this->getModel('daytime');
		$daytime = $model->getDaytime($daytime_id);
		$daytime->calendar_id=$calendar_id;
		$daytime->daytime_day=$daytime_day;
		echo json_encode($daytime);
		exit;
	}
}
