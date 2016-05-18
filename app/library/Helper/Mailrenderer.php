<?php
namespace nltool\Helper;
use Phalcon\Mvc\User\Component,
	nltool\Models\Linklookup;


/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Mailrenderhelpers
 *
 * @author Philipp-PC
 */
class Mailrenderer extends Component{
	public function writeClicktrackingLinks($body,$mailing){
		$this->mailingToRender=$mailing;
				
		preg_match_all('/(<a\s[^>]*href=\")([http|https][^\"]*)(\"[^>]*>)/siU',$body,$matches); 
		foreach($matches[2] as $key => $value ){
			$params=explode('?',$value);
			
			$url=$params[0];
			
			$time=time();
			$jumplink=new Linklookup();
			$jumplink->assign(array(
				"pid"=>0,
				"tstamp"=>$time,
				"crdate"=>$time,
				"deleted"=>0,
				"hidden"=>0,				
				"campaignuid"=>$this->mailingToRender->campaignuid,
				"mailobjectuid"=>$this->mailingToRender->mailobjectuid,
				"sendoutobjectuid"=>$this->mailingToRender->uid,
				"url"=>$url,
				"addressuid"=>0,
				"linknumber"=>$key,
				"params"=>isset($params[1]) ?: ''
			));
			$jumplink->save();
		}
		
	}
	
	public function writeDynamicContent($mailing){
		
	}
	
	private function getDynamicContent(){
		$chlead = curl_init();
		curl_setopt($chlead, CURLOPT_URL, 'https://www.tecparts.com/api/rest/article/getArticle?code=630658&lastUrl=');
		curl_setopt($chlead, CURLOPT_PUT, true);		
		curl_setopt($chlead, CURLOPT_RETURNTRANSFER, true);		
		curl_setopt($chlead, CURLOPT_SSL_VERIFYPEER, 0);
		$chleadresult = curl_exec($chlead);
		$chleadapierr = curl_errno($chlead);
		$chleaderrmsg = curl_error($chlead);		
		curl_close($chlead);
		
	}
	
	public function renderFinal($body,$addressuid,$mailinguid, $linkKeyMap){
				
		$this->currentaddressuid=$addressuid;
		$this->mailinguid=$mailinguid;
		$environment= $this->config['application']['debug'] ? 'development' : 'production';
		$this->baseUri=$this->config['application'][$environment]['staticBaseUri'];		
		$this->key=-1;
		$this->linkkeymap=$linkKeyMap;
		
		$renderedbody=preg_replace_callback('/(<a\s[^>]*href=\")([http|https][^\"]*)(\"[^>]*>)/siU', 'self::renderFinalCallback' ,$body);		
		$finalizedBody=preg_replace_callback('/<body[^>]*>/im',"self::addOpenmailerBlankImage",$renderedbody);
				
		return $finalizedBody;				
	}
	
	public function renderFinalCallback($matches){						
			$params='';
			$urlArray=explode('?',$matches[2]);
			if(count($urlArray)>1){
				$params='?'.$urlArray[1];
			}
			$this->key++;
			return $matches[1].'http://'.$this->request->getHttpHost().$this->baseUri.'linkreferer/'.$this->linkkeymap[$this->key].'/'.$this->currentaddressuid.'/'.$params.$matches[3];			
	}
	
	public function addOpenmailerBlankImage($matches){
		return $matches[0].'<img width="1" height="1" src="'.'http://'.$this->request->getHttpHost().$this->baseUri.'linkreferer/open/'.$this->mailinguid.'/'.$this->currentaddressuid.'" alt="">';
	}
	
	public function renderVars($body,$address){
		$fieldMap=array(
			'userid'=>$address->uid,
			'Userid'=>$address->uid,
			'Anrede' => $address->salutation,
			'Vorname' => $address->first_name,
			'Nachname' => $address->last_name,
			'Titel' => $address->title,
			'Unternehmen' => $address->company,
			'Email' =>  $address->email
		); //TODO komplettieren
		
		preg_match_all('/{{(.*)}}/siU', $body, $matches);
		
		foreach($matches[0] as $key => $match){
			$body=str_replace($match, $fieldMap[$matches[1][$key]], $body);
		}
		
		return $body;
	}
}

