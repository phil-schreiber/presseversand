<?php
namespace nltool\Modules\Modules\Frontend\Controllers;
require_once '../app/library/Mailreader/Mailreader.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BounceController extends Controllerbase{
    public function indexAction(){
        
    }
    
    public function readAction(){
        if($this->request->isPost()){
            
            $mailreader=new \Mailreader(
                    $this->request->getPost('server'),
                    $this->request->getPost('user'),
                    $this->request->getPost('pass'),
                    $this->request->getPost('port')
            );
            var_dump($mailreader->getInbox());
        }
    }
}