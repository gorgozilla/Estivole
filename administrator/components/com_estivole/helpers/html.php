<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/services.php';
require_once JPATH_COMPONENT . '/models/calendars.php';
require_once JPATH_COMPONENT . '/models/members.php';

class EstivoleHelpersHtml
{
	function calendarsList()
	{
		$calendarsModel = new EstivoleModelCalendars();

		$this->calendars = $calendarsModel->listItems();
		
		## Initialize array to store dropdown options ##
		$options = array();
		
		foreach($this->calendars as $calendar) :
			## Create $value ##
			$options[] = JHTML::_('select.option', $calendar->calendar_id, $calendar->name);
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, 'jform[calendar_id]', 'class="inputbox" id="calendar_id"', 'value', 'text', $default);
	}
	
	function hoursList($day, $field_name, $default)
	{
		
		$hours = array();
		for($i=0; $i<=23; $i++){
			$prefix='';
			if($i<10){
				$prefix='0';
			}
			$hours[$day.' '.$prefix.$i.':00:00'] = $prefix.$i.':00';
			$hours[$day.' '.$prefix.$i.':30:00'] = $prefix.$i.':30';
		};
		
		## Initialize array to store dropdown options ##
		$options = array();
		
		foreach($hours as $key=>$value) :
			## Create $value ##
			$options[] = JHTML::_('select.option', $key, $value);
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, $field_name, 'class="inputbox" id="'.$field_name.'"', 'value', 'text', $default);
	}
	
	function yearsList($field_name, $default)
	{
		$years = array();
		for($i=0; $i<=10; $i++){
			$date = idate('Y');;
			$years[$i] = ($date+$i);
		};
		
		## Initialize array to store dropdown options ##
		$options = array();
		
		foreach($years as $key=>$value) :
			## Create $value ##
			$options[] = JHTML::_('select.option', $value, $value);
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, $field_name, 'class="inputbox" id="'.$field_name.'"', 'value', 'text', $default);
	}
	
	function servicesList()
	{
		$serviceModel = new EstivoleModelServices();
		$this->services = $serviceModel->listItems();
		
		## Initialize array to store dropdown options ##
		$options = array();

		foreach($this->services as $service) :
			## Create $value ##
			$options[] = JHTML::_('select.option', $service->service_id, $service->service_name);
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, 'jform[service_id]', 'class="inputbox" id="service_id"', 'value', 'text', $default);
	}
	
	function datesList($calendar_id, $service_id)
	{	
		## Initialize array to store dropdown options ##
		$options = array();
		
		$daytimeModel = new EstivoleModelDaytime();
		$this->daytimes = $daytimeModel->listItems();
		
		foreach($this->daytimes as $daytime) :
			## Create $value ##
			$options[] = JHTML::_('select.option', $daytime->daytime_day, date('d-m-Y',strtotime($daytime->daytime_day)));
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, 'jform[daytime]', 'class="inputbox" id="jform_daytime"', 'value', 'text', $default);
	}
	
	function membersList()
	{
		$membersModel = new EstivoleModelMembers();

		$this->members = $membersModel->getTotalItems();
		
		## Initialize array to store dropdown options ##
		$options = array();
		
		foreach($this->members as $member) :
			$userProfilEstivole = EstivoleHelpersUser::getProfilEstivole( $member->user_id );
			## Create $value ##
			$options[] = JHTML::_('select.option', $member->member_id, $userProfilEstivole->profilestivole['firstname'].' '.$userProfilEstivole->profilestivole['lastname']);
		endforeach;
		
		## Create <select name="month" class="inputbox"></select> ##
		return JHTML::_('select.genericlist', $options, 'jform[member_id]', 'class="inputbox" id="member_id"', 'value', 'text', $default);
	}
}