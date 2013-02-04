<?php
 /*
 * Client For Testing REST API
 */
class BRestClient
{

	protected $apiKey = null;
	protected $apiUrl = null;
    protected $apiVersion = '0.2';
    protected $includeHeader = false;
    protected $useJsonHeader = false;

	public function __construct($apiUrl, $apiKey)
	{
		$this->apiUrl = $apiUrl;
		$this->apiKey = $apiKey;
	}
    
    public function setApiUrl($apiUrl) 
    {
        $this->apiUrl = $apiUrl;
    }
    
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }
    
    public function useJsonHeader($shouldUse=false)
    {
        $this->useJsonHeader = $shouldUse;
    }

	public function get($url, $params)
	{
		return $this->httpRequest($url, $params, 'GET');
	}

	public function post($url, $params)
	{
		return $this->httpRequest($url, $params, 'POST');
	}

	public function delete($url, $params)
	{
		return $this->httpRequest($url, $params, 'DELETE');
	}

	public function put($url, $params)
	{
		return $this->httpRequest($url, $params, 'PUT');
	}

	protected function _convertParams($params)
	{
		return $result = http_build_query($params);
	}

	public function getHttpStatusCode()
	{
		return $this->httpCode;
	}

	public function httpRequest($url, $postdata = array(), $method = "GET")
	{
        // clean up post fields if its an array
        if(is_array($postdata)) {
            foreach ($postdata as $key => $value) {
                if (is_null($value)) {
                    unset($postdata[$key]);
                }
            }
            
            // convert it to query string
            $postdata = http_build_query($postdata);
        
		}
        
	
        // The base url, with api version and consumerKey
		$url = $this->apiUrl .'/' . $this->apiVersion . $url . '?consumer_key='.$this->apiKey;



        // build curl
		$ch = curl_init();

        // array for headers
        $httpHeaders = array();
        
        // set json as content-type for header in request
        if($this->useJsonHeader) {
            $this->includeHeader = true;
            array_push($httpHeaders, 'Content-Type: application/json; charset=utf-8');
        }
        
        // set the headers
        if(count($httpHeaders) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
        }
        

        curl_setopt($ch, CURLOPT_HEADER, false);   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        
		switch ($method) {
            
			case 'GET':
				$url .= "&" . $postdata;
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				if (!empty($postdata)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				}
				break;
			case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postdata)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				}
				break;
			case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				if (!empty($postdata)) {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				}
				break;
		}
        
          error_log("Api request endpoint = ".$url);
          error_log(print_r($postdata, true));
        
        // set the curl url
		curl_setopt($ch, CURLOPT_URL, $url);

        // the raw response
		$response = curl_exec($ch);
        
     
        // set the response status code
		$this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
   
        
		$responseArr = json_decode($response);

        return $responseArr;
        
	}

}