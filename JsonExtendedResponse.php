<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class JsonExtendedResponse extends BRestResponse
{

    protected $resultsKey = 'results';
    
	public function getContentType()
	{
		return "application/json";
	}

	public function setBodyParams($params = array(), $metaParams = array())
	{
        $response = array();
        // return the time that this data is generated for future scenario
        // where we support caching.
        $response['time'] = date("r",time());
        
        // we want to return the results always as an array even if its 
        // for a single record.  This seems more consistent for clients
        if(!$this->isMulti($params)) {
            $params = array($params);
        }
      
        // merge in the metaParams
        $response = array_merge($response, $metaParams);
   
        // set the actual results params
        $response[$this->resultsKey] = $params;   
       
		$this->body = CJSON::encode($response);
		return $this;
	}
    
    /**
     * Checks if an array is a multi dimensional array
     * @param type $array
     * @return type 
     */
    public function isMulti($array) 
    {
        return (count($array) != count($array, 1));
    }
        
}


