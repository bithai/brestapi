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

    protected $modelName = '';
    
    protected $responseClass;
    
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

		return parent::init();
	}

	
    public function setResponseClass($responseClass)
    {
        $this->responseClass = $responseClass;     
    }
    
    
	/**
	 * @return CActiveRecord
	 */
	public function getModel()
	{
		$id = Yii::app()->request->getParam('id');
		$modelName = ucfirst($this->modelName);

		if ($id) {
			$model = $modelName::model()->findByPk($id);
			if (is_null($model)) {
				$this->restResponse->sendResponse(404);
			}
		} else {
			$model = new $modelName();
		}
		return $model;
	}



}
    
 
    
    