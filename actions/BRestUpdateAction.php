<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestUpdateAction extends BRestAction
{

	public $scenario = '';

	public function run()
	{
        parent::run();
        
        // get request attributes
        $requestAttributes = Yii::app()->getController()->restRequest->getParams();
        
        // get model instance
		$model = $this->controller->getModel($this->scenario);

        // get attributes for update
		$attributesList = $model->getUpdateAttributes();

        // set attributes array with incoming request attributes
		$attributes = array();
		foreach ($attributesList as $key) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}
        

        // get response object
        $restResponse = Yii::app()->getController()->restResponse;
       
        // set the model attributes
		$model->setAttributes($attributes);
 
		if ($model->save()) {
            // if save success send response and success code
            $responseParams[$restResponse->resultsNode] = array($model->getAttributesForResponse());
			$restResponse->sendResponse(200, $responseParams);
		} else {
            // send error code
			$restResponse->sendResponse(400, array('errors' => $model->getErrors()));
		}
	}

}