<?php defined( '_JEXEC' ) or die( 'Restricted access' ); 
 require_once JPATH_COMPONENT . '/models/service.php';
 require_once JPATH_COMPONENT . '/models/daytime.php';
 require_once JPATH_COMPONENT . '/models/members.php';
 /** Include PHPExcel */
require_once JPATH_COMPONENT .'/lib/PHPExcel.php';
 
class EstivoleControllerMembers extends JControllerAdmin
{
	public $formData = null;
	public $model = null;
	public $search_text = null;
	
	public function execute($task=null)
	{
		$app      = JFactory::getApplication();
		$modelName  = $app->input->get('model', 'Member');

		// Required objects 
		$input = JFactory::getApplication()->input; 
		// Get the form data 
		$this->formData = new JRegistry($input->get('jform','','array')); 

		//Get model class
		$this->model = $this->getModel($modelName);

		if($task=='deleteListMember'){
			$this->deleteListMember();
		}else if($task=='exportMembersShirts'){
			$this->exportMembersShirts();
		}else{
			$this->display();
		}
	}
	
	public function exportMembersShirts()
	{
		$app      = JFactory::getApplication();
		$service_id = $app->input->get('service_id', null);
		
		$model = new EstivoleModelMembers();
		$modelDaytime = new EstivoleModelDaytime();
		$modelService = new EstivoleModelService();
		
		$service=$modelService->getItem($service_id);
		$service_name=$service->service_name!=''?$service->service_name:'Tous les membres';

		$this->members = $model->getTotalItemsForExport();
		for($i=0; $i<count($this->members); $i++){
			$this->members[$i]->member_daytimes = $modelDaytime->getMemberDaytimes($this->members[$i]->member_id, $this->calendars[0]->calendar_id);
		}

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Estivale Open Air")
									 ->setLastModifiedBy("Estivole")
									 ->setTitle("Export Estivole")
									 ->setSubject("Export des bénévoles Estivole");

		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A1", "Plan de travail Estivale Open Air - Bénévoles");
					
		// $this->state	= $this->get('State');
		// $this->filter_services	= $this->state->get('filter.services_members');
					
		$objPHPExcel->getActiveSheet()
					->setCellValue("A2", "Export membres \"".$service_name."\"");

		// // Miscellaneous glyphs, UTF-8
		$objPHPExcel->getActiveSheet()
					->setCellValue("A4", "Nom")
					->setCellValue("B4", "Email")
					->setCellValue("C4", "Téléphone")
					->setCellValue("D4", "Nbre t-shirts")
					->setCellValue("E4", "Taille t-shirts")
					->setCellValue("F4", "Camping");

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle("Export");
		
		$cellCounter=5;
		// Add data
		for ($i = 0; $i < count($this->members); $i++) {			
			$userId = $this->members[$i]->user_id; 
			$userProfileEstivole = EstivoleHelpersUser::getProfileEstivole($userId);
			$userProfile = JUserHelper::getProfile( $userId );
			$user = JFactory::getUser($userId);
			$campingValue = $userProfileEstivole->profilestivole['campingPlace']!=null ? 'X' : '';
			$objPHPExcel->getActiveSheet()
						->setCellValue("A".($cellCounter+1), $userProfileEstivole->profilestivole['lastname'].' '.$userProfileEstivole->profilestivole['firstname'])
						->setCellValue("B".($cellCounter+1), $user->email)
						->setCellValueExplicit("C".($cellCounter+1), $userProfile->profile['phone'], PHPExcel_Cell_DataType::TYPE_STRING)
						->setCellValue("D".($cellCounter+1), round(count($this->members[$i]->member_daytimes)/2))
						->setCellValue("E".($cellCounter+1), $userProfileEstivole->profilestivole['tshirtsize'])
						->setCellValue("F".($cellCounter+1), $campingValue);
						
			$cellCounter++;
		}
		// Redirect output to a client’s web browser (Excel2007)
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=\"01simple.xlsx\"");
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
	
	public function deleteListMember()
	{
		$app      = JFactory::getApplication();
		$member_id  = $app->input->get('member_id');
		$return = array("success"=>false);
        $ids    = JRequest::getVar('cid', array(), '', 'array');
		
        if (empty($ids)) {
            JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
        }
        else {
			foreach($ids as $id){
				$this->model->deleteMember($id);
			}
			$app->redirect( $_SERVER['HTTP_REFERER']);
        }
	}
}