<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class JsonExtendedResponse extends BRestResponse
{

    public $resultsNode = 'results';
    
	public function getContentType()
	{
		return "application/json";
	}

	public function setBodyParams($params = array())
	{   
        $response['time'] = date("r",time());
        $response = CMap::mergeArray($response, $params);

		$this->body = CJSON::encode($response);
		return $this;
	}
        
}


