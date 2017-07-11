<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
require_once JPATH_COMPONENT . '/models/daytime.php';
require_once JPATH_COMPONENT . '/models/calendar.php';
require_once JPATH_COMPONENT . '/models/services.php';
require_once JPATH_COMPONENT . '/helpers/user.php';
/** Include PHPExcel */
require_once JPATH_COMPONENT .'/lib/PHPExcel.php';

class EstivoleControllerDaytime extends JControllerForm
{
	public $formData = null;
	public $model = null;
	
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		// Required objects 
		$input = JFactory::getApplication()->input; 
		$modelName  = $app->input->get('model', 'Daytime');

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListDaytime'){
			$this->deleteListDaytime();
		}elseif($task=='exportDaytime'){
			$this->exportDaytime();
		}elseif($task=='changeStatusDaytime'){
			$member_daytime_id = $input->get('member_daytime_id'); 
			$status_id = $input->get('status_id'); 
			$this->changeStatusDaytime($member_daytime_id, $status_id);
			if($status_id==1){
				$app->enqueueMessage('Disponibilité confirmée & validée, le bénévole ne peut plus supprimer cette tranche horaire!');
			}else{
				$app->enqueueMessage('Disponibilité à nouveau en attente, le bénévole peut supprimer la tranche horaire.');
			}
		}elseif($task=='getDaytimesByService'){
			$this->getDaytimesByService($this->calendar_id, $this->service_id);
		}
			
		 //Redirect on referer page
		$app->redirect($_SERVER['HTTP_REFERER']);
	}

	public function changeStatusDaytime($member_daytime_id, $status_id)
	{
		$app      = JFactory::getApplication();
		$this->model = new EstivoleModelDaytime();
		$member_daytime = $this->model->getMemberDaytime($member_daytime_id);
		$return = array("success"=>false);

		if($this->model->changeStatusDaytime($member_daytime_id, $status_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			EstivoleHelpersMail::confirmMemberDaytime($member_daytime->member_id, $member_daytime->service_id, $member_daytime->daytime_id);
			EstivoleHelpersMail::confirmResponsableDaytime($member_daytime->service_id, $member_daytime->daytime_id, $member_daytime->member_id);
		}
	}
	
	public function exportDaytime()
	{
		$app      = JFactory::getApplication();
		$modelCalendar = new EstivoleModelCalendar();
		$modelDaytime = new EstivoleModelDaytime();
		$modelServices = new EstivoleModelServices();

		$daytimeid  = $app->input->get('daytime_id');
		$this->daytime = $modelDaytime->getDaytime($daytimeid);
		$calendar = $modelCalendar->getItem($this->daytime->calendar_id);
		$this->services = $modelServices->getServicesByDaytime($this->daytime->daytime_day);

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Estivale Open Air")
									 ->setLastModifiedBy("Estivole")
									 ->setTitle("Export Estivole")
									 ->setSubject("Export des tranches horaires Estivole");
		
		// // Add data
		for ($i = 0; $i < count($this->services); $i++) {	
			$cellCounter=4;	
				$objPHPExcel->createSheet($i); //Setting index when creating
			// Add some data
			$objPHPExcel->setActiveSheetIndex($i);
			$objPHPExcel->getActiveSheet()
						->setTitle(str_replace('/', '-', $this->services[$i]->service_name))
						->setCellValue("A1", "Plan de travail Estivale Open Air - Calendrier ".$calendar->name)
						->setCellValue("A2", date('d-m-Y', strtotime($this->daytime->daytime_day)))
						->setCellValue("A".$cellCounter, $this->services[$i]->service_name);
						
			$this->daytimes = $modelDaytime->listItemsForExport($this->services[$i]->service_id);
			
			foreach($this->daytimes as $daytime){
				$userId = $daytime->user_id; 
				$userProfileEstivole = EstivoleHelpersUser::getProfileEstivole($userId);
				$userProfile = JUserHelper::getProfile( $userId );
				$user = JFactory::getUser($userId);
				
				$objPHPExcel->getActiveSheet()
							->setCellValue("A".($cellCounter+1), $userProfileEstivole->profilestivole['lastname'].' '.$userProfileEstivole->profilestivole['firstname'])
							->setCellValueExplicit("B".($cellCounter+1), $userProfile->profile['phone'], PHPExcel_Cell_DataType::TYPE_STRING)
							->setCellValue("C".($cellCounter+1), $user->email)
							->setCellValue("D".($cellCounter+1), date('H:i', strtotime($daytime->daytime_hour_start)))
							->setCellValue("E".($cellCounter+1), date('H:i', strtotime($daytime->daytime_hour_end)));
				
				$cellCounter++;
			}
		}
		// Redirect output to a client’s web browser (Excel2007)
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=\"estivole_".$this->daytime->daytime_day.".xlsx\"");
		header("Cache-Control: max-age=0");
		// If you"re serving to IE 9, then the following may be needed
		header("Cache-Control: max-age=1");
		// If you"re serving to IE over SSL, then the following may be needed
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
		header ("Cache-Control: cache, must-revalidate"); // HTTP/1.1
		header ("Pragma: public"); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
		$objWriter->save("php://output");
		exit;
	}
	
	public function getDaytimesByService($calendar_id, $service_id)
	{
		$modeldaytime = new EstivoleModelDaytime();
		$this->daytimes = $modeldaytime->listitems();
		print json_encode($this->daytimes);
		exit;
	}
	
	/**
	* Delete a member daytime
	* @param int      ID of the member to delete
	* @return boolean True if successfully deleted
	*/
	public function deleteListDaytime()
	{
		$app      = JFactory::getApplication();
		$daytime_id  = $app->input->get('daytime_id');
		$return = array("success"=>false);
		
		$modelDaytime = new EstivoleModelDaytime();

		$memberDaytimes = $modelDaytime->getDaytimeDaytimes($daytime_id);

		foreach($memberDaytimes as $memberDaytime){
			$daytime = JTable::getInstance('MemberDaytime','Table');
			$daytime->load($memberDaytime->member_daytime_id);

			if (!$daytime->delete()) 
			{
				return false;
			}
		}

		if($modelDaytime->deleteDaytime($daytime_id)){
			$return['success'] = true;
			$return['msg'] = 'Yes';
			$app->enqueueMessage('Date supprimée avec succès!');
		}else{
			$app->enqueueMessage('Erreur!');
		}
		$app->redirect( $_SERVER['HTTP_REFERER']);
	}
}