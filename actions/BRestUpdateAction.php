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
        
        $requestAttributes = Yii::app()->getController()->restRequest->getParams();
		$model = $this->controller->getModel($this->scenario);

		$attributesList = $model->getUpdateAttributes();

		$attributes = array();
		foreach ($attributesList as $key) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}
        

        $restResponse = Yii::app()->getController()->restResponse;
        $responseParams[$restResponse->resultsNode] = array($model->getAttributesForResponse());
        
        
		$model->setAttributes($attributes);
 
		if ($model->save()) {
			$restResponse->sendResponse(200, $responseParams);
		} else {
			$restResponse->sendResponse(400, array('errors' => $model->getErrors()));
		}
	}

}