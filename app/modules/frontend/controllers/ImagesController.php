<?php
namespace nltool\Modules\Modules\Frontend\Controllers;


/**
 * Class IndexController
 *
 * @package baywa-nltool\Controllers
 */
class ImagesController extends ControllerBase
{

	public function indexAction(){
		$this->view->setTemplateAfter('blank');
	}
	
	/**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function createAction()
	{
		$this->view->setTemplateAfter('blank');
		$this->host='http://'.$this->request->getServer('HTTP_HOST');
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$this->baseUri=$this->config['application'][$environment]['staticBaseUri'];
			
	foreach ($this->request->getUploadedFiles() as $file){
						$nameArray=explode('.',$file->getName());
						$filetype=$nameArray[(count($nameArray)-1)];
						$filename=$nameArray[0].'_'.time().'.'.$filetype;
						$file->moveTo('../public/images/media/'.$filename);
						
						
              }
			     
		$this->view->setVar('filename','http://'.$_SERVER["SERVER_NAME"].$this->baseUri.'public/images/media/'.$filename);
	}
}