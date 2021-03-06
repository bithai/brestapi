<?php
/**
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestRequest
{

	private $_params = array();
    

	private $_format = 'json';
	private $_formatAttributeName = 'format';
    private $_supportedFormats = array('json');

    /**
     * initialize the incoming params
     */
    public function initRequestParams()
    {
        
                
        $this->setFormat();
        $this->parseJsonParams();
        $this->initRestParams();

	}
    
    /**
	 * Return an instance of BRestResponse
	 * @param string $type
	 * @return BRestResponse
	 */
	public static function getRequestValidator($className)
	{
		return new $className();
    }
    
    public function getParams() 
    {
        return $this->_params;
    }
    
    public function setFormat($format = null)
	{
		if ($format && in_array($format, $this->_supportedFormats)) {
			$this->_format = $format;
		}
		if (!$this->_format) {
			//get format from one of requests type
			$format = Yii::app()->request->getParam($this->_formatAttributeName);
			$format = (empty($format)) ? Yii::app()->request->getPut($this->_formatAttributeName) : $format;
			$format = (empty($format)) ? Yii::app()->request->getDelete($this->_formatAttributeName) : $format;
			$this->_format = $format;
		}
	}

	public function getFormat(){
		return $this->_format;
	}
    
    
    public function initRestParams()
    {
        $_params = $_REQUEST;
        if(Yii::app()->request->getIsPutRequest() || Yii::app()->request->getIsDeleteRequest()) {
           
            $_params = array_merge($_params, $this->getRestParams());
        }
        
        $this->_params = array_merge($this->_params, $_params);
    }
    
    /**
    * Returns the PUT or DELETE request parameters.
    * @return array the request parameters
    * This was borrowed from the protected method from Yii's CHttpRequest
    */
    public function getRestParams()
    {
        $result=array();
        if(function_exists('mb_parse_str'))
            mb_parse_str(file_get_contents('php://input'), $result);
        else
            parse_str(file_get_contents('php://input'), $result);
        return $result;
    }
    
    
    
    /**
     * This supports getting body input where content type is "Content-Type: application/json"
     * @return type 
     */
	public function parseJsonParams(){
        
        // if content type is not set, just return
		if(!isset($_SERVER['CONTENT_TYPE'])){
			return $this->_params;
		}
		
		$contentType = strtok($_SERVER['CONTENT_TYPE'], ';');
		if($contentType == 'application/json'){
			$requestBody = file_get_contents("php://input");
			$this->_params = array_merge((array)json_decode($requestBody, true), $this->_params);
		}
		return $this->_params;
	}

    /**
     * Set param
     * @param type $key
     * @param type $value 
     */
    public function setParam($key, $value)
    {
        $this->_params[$key] = $value;
    }
    
	/**
     * Get param
     * @param type $name
     * @param type $defaultValue
     * @return type 
     */
	public function getParam($key,$defaultValue=null)
	{
        $param = isset($this->_params[$key]) ? $this->_params[$key] : null;
		return $param ? $param : $defaultValue;
	}



}
