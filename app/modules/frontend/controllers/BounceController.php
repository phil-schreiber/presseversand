<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
require_once '../app/library/Mailreader/Mailreader.php';
use nltool\Models\Addresses as Addresses;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BounceController extends ControllerBase{
    public function indexAction(){
        
    }
    
    public function readAction(){
        $environment= $this->config['application']['debug'] ? 'development' : 'production';
			$baseUri=$this->config['application'][$environment]['staticBaseUri'];
        if($this->request->isPost()){
            
            $mailreader=new \Mailreader(
                    $this->request->getPost('server'),
                    $this->request->getPost('user'),
                    $this->request->getPost('pass'),
                    $this->request->getPost('port')
            );
            $bounceArray=$mailreader->processMails();
            $csv='Email;Status;Action;Response'.PHP_EOL;
            foreach($bounceArray as $email => $status){
                $entries=Addresses::findByEmail($email);
                
                foreach($entries as $entry){
                $entry->deleted=1;
                $entry->update();
                }
                $csv.=$email.';'.$status['status'].';'.$status['action'].';'.$status['response'].PHP_EOL;
            }
            $time=time();
            $filename='bounces-'.$time.'.csv';
            file_put_contents('../public/media/'.$filename,$csv);	
            //$this->view->setVar('downloadlink','<a href="" target="_blank">Download Bounce-Adressen</a>')
            $this->response->redirect($this->request->getScheme().'://'.$this->request->getHttpHost().$baseUri.'public/media/'.$filename, true);                            
            $this->view->disable();
        }
    }
}