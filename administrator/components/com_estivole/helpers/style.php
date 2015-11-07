<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

class EstivoleHelpersStyle
{
	function load()
	{
		$document = JFactory::getDocument();

		// //stylesheets
		$document->addStylesheet(JURI::base().'components/com_estivole/assets/css/bootstrap-datetimepicker.min.css');
		$document->addStyleSheet('/estivale2015/media/jui/css/bootstrap.min.css');

		//javascripts
		$document->addScript(JURI::base().'components/com_estivole/assets/js/estivole.js');
		$document->addScript(JURI::base().'components/com_estivole/assets/js/bootstrap-datetimepicker.min.js');

	}
}