<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
abstract class BRestResponse
{
	protected $body = '';
	protected $status = 200;
    
    public $resultsNode = 'results';

	public abstract function getContentType();

	public abstract function setBodyParams($results = array());


	/**
	 * Return an instance of BRestResponse
	 * @param string $type
	 * @return BRestResponse
	 */
	public static function getRestResponse($className)
	{
		return new $className();
    }
    
    public function getBody()
	{
		return $this->body;
	}


    /**
     *
     * @param type $status
     * @param type $outputType
     * @return array 
     */
	public function getHttpStatusCodeMessage($status,$outputType='title')
	{
		$codes = Array(
            
			200 => array('title' => 'OK',               'message' => 'OK'),
            201 => array('title' => 'Created',          'message' => 'Created'),
            204 => array('title' => 'No Content',       'message' => 'No Content'),
			400 => array('title' => 'Bad Request',      'message' => 'Bad Service Request'),
			401 => array('title' => 'Unauthorized',     'message' => 'Unauthorized Service Request'),
			403 => array('title' => 'Forbidden',        'message' => 'Service Forbidden'),
			404 => array('title' => 'Not Found',        'message' => 'Service Url Not Found'),
            405 => array('title' => 'Method Not Allowed','message' => 'Method Not Allowed'),
			500 => array('title' => 'Internal Server Error',    'message' => 'The server encountered an error processing your request.'),
			501 => array('title' => 'Not Implemented',  'message' => 'The service requested method is not implemented.'),
		);
		$result = "";
		if (isset($codes[$status])) {
            if($outputType == 'title') {
                $result = $codes[$status]['title'];
            }
            else if($outputType == 'message') {
                $result = isset($codes[$status]['message']) ? $codes[$status]['message'] : $codes[$status]['title'];
            }
            else {
                $result = $codes[$status];
            }
		}
		return $result;
	}

   
    /**
     * 
     * @param type $status
     * @return type 
     */
	public function getErrorMessage($status){
		return array(
            'http_status_code' => $status,
			'title' => $this->getHttpStatusCodeMessage($status,'title'),
			'message' => $this->getHttpStatusCodeMessage($status,'message'),
		);
	}

    /**
     *
     * @return string 
     */
	public function getHeaders(){
		$headers = array();
		$status = $this->status;
        
		// set the status
		$statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->getHttpStatusCodeMessage($status,'title');
		$headers[] = $statusHeader;
        
		// and the content type
		$headers[] = 'Content-type: ' . $this->getContentType();

		return $headers;
	}
    

    
    
    
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}
    

	public function getStatus(){
		return $this->status;
	}
    
    
    /**
     * 
     * @todo Need to return meta for list type queries like: total, limit, offset, time: Thu, 01 Nov 2012 23:46:31 -0500
     * Sends the Rest response. Call this method whenever you are ready to
     * send final output to client request.
     * 
     * Typically will send the http headers with status code and body.
     * 
     * @param type $status http status code
     * @param type $bodyParams the http body content to send 
     * 
     * 
     */
	public function sendResponse($status = 200, $results = array())
	{
        
		if ($status != 200) {
			$results = CMap::mergeArray($this->getErrorMessage($status), $results);
		}
 
		$this->setStatus($status);
		$this->sendHeaders();
    
		$body = $this->setBodyParams($results)->getBody();
             
        echo $body;
		Yii::app()->end();
	}

	public function sendHeaders()
	{
		$headers = $this->getHeaders();
		foreach ($headers as $header){
			header($header);
		}
	}

}