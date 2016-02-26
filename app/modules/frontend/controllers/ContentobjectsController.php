<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
use Phalcon\Tag,
	nltool\Models\Templateobjects as Templateobjects,	
	nltool\Models\Contentobjects as Contentobjects,
	Phalcon\Mvc\View\Engine\Volt\Compiler as Compiler;

/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class ContentobjectsController extends ControllerBase
{

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function indexAction()
	{
		
	}
	
	public function createAction(){
		if($this->request->isPost()){
			
		}
	}
	
	public function deleteAction(){
		if($this->request->isPost()){
			$contentobjectRecord=  Contentobjects::findFirst(array(
				"conditions" => "deleted = 0 AND hidden =0 AND templateposition = ?1 AND positionsorting = ?2 AND mailobjectuid = ?3",
							"bind" => array(
								1 => $_POST['templateposition'],
								2 => $_POST['positionsorting'],
								3 => $_POST['mailobjectUid']
								
								)
			));
			
			if($contentobjectRecord){
				
				$contentobjectRecord->deleted=1;
				$contentobjectRecord->hidden=1;
				$contentobjectRecord->tstamp=time();
				$contentobjectRecord->update();
				
				$this->checkAndUpdateRecords();
				die('1');
			}		
			
			
			
		}
		
	}
	
	private function checkAndUpdateRecords(){
		
		$contentRecordsOnPosition= Contentobjects::findFirst(array(
				"conditions" => "deleted = 0 AND hidden =0 AND templateposition = ?1 AND mailobjectuid = ?2",
							"bind" => array(
								1 => $_POST['templateposition'],	
								2 => $_POST['mailobjectUid']
								
								)
			));
		
		if(!$contentRecordsOnPosition){
			$followingTemplatePositionRecords=Contentobjects::find(array(
				"conditions" => "deleted = 0 AND hidden =0 AND templateposition > ?1 AND mailobjectuid = ?2",
							"bind" => array(
								1 => $_POST['templateposition'],	
								2 => $_POST['mailobjectUid']
								
								)
			));
			if($followingTemplatePositionRecords){
				foreach($followingTemplatePositionRecords as $record){
					
					$record->templateposition=$record->templateposition-1;
					$record->update();
				}
			}
		}
	}

}