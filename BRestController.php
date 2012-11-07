<?php

/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
Yii::import("ext.".basename(__DIR__).'.actions.*');
abstract class BRestController extends CExtController
{ 
    public $restRequest;
    public $restResponse;
    public $restRequestValidator;

    protected $modelName = '';
    
    protected $responseClass;
    protected $restRequestValidatorClass;
    
    public function init()
    {
		$this->modelName = ucfirst($this->modelName);

        // may want to abstract out the BRestRequest to handle OAuthRequest in future
        $this->restRequest = new BRestRequest();
        $this->restRequest->initRequestParams();

        // by default, we'll load the Response class based on the format
        // so if response format is 'json', then response class is "JsonResponse"
        if(!$this->responseClass) {
            $this->responseClass = ucfirst($this->restRequest->getFormat()).'Response';
        }  
		$this->restResponse = BRestResponse::getRestResponse($this->responseClass);
        
                
    
        // initialize restRequestValidator class
        if(!$this->restRequestValidatorClass) {
            $this->restRequestValidatorClass = "SimpleKeySecretValidator";
        } 
        $this->restRequestValidator = BRestRequest::getRequestValidator($this->restRequestValidatorClass);
        $this->restRequestValidator->run();

		return parent::init();
	}

	
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;     
    }
    
    public function setRequestValidatorClass($validatorClass)
    {
        $this->restRequestValidatorClass = $validatorClass;
    }
    
    
	    /**
     * Returns the model defined in controller resource
     * @param type $scenario
     * @return CActiveRecord model
     */
    public function getModel($scenario = '')
	{
		$id = Yii::app()->request->getParam('id');
		$modelName = ucfirst($this->modelName);

        // if model property is not set or class doesn't exists
		if (empty($this->modelName) || !class_exists($modelName)) {
			$this->sendResponse(500);
		}

		if ($id) {
			$model = $modelName::model()->findByPk($id);
			if (!$model) {
		        $this->restResponse->sendResponse(404);
			}
		} else {
			$model = new $modelName();
		}
		if ($scenario && $model)
			$model->setScenario($scenario);
        
		return $model;
	}




}
    
 
    
    