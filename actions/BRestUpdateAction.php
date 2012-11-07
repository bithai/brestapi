<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestUpdateAction extends CAction
{

	public $scenario = '';

	public function run()
	{
        
        $requestAttributes = Yii::app()->getController()->restRequest->getParams();
		$model = $this->controller->getModel($this->scenario);

		$attributesList = $model->getUpdateAttributes();

		$attributes = array();
		foreach ($attributesList as $key) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}
        

		$model->setAttributes($attributes);
 
		if ($model->save()) {
			$this->getController()->restResponse->sendResponse(200, $model->getAttributesForResponse());
		} else {
			$this->getController()->restResponse->sendResponse(400, array('errors' => $model->getErrors()));
		}
	}

}