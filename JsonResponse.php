<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class JsonResponse extends BRestResponse
{
    
    protected $resultsKey = 'results';
    
	public function getContentType()
	{
		return "application/json";
	}

	public function setBodyParams($resultParams = array(), $metaParams = array())
	{
        $response = array();
        
        // merge in the metaParams
        $response = array_merge($response, $metaParams);
   
        // set the actual results params
        $response[$this->resultsKey] = $resultParams;
        
		$this->body = CJSON::encode($response);
		return $this;
	}
}





