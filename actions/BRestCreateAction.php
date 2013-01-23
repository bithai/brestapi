<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestCreateAction extends BRestAction
{
	public $scenario = '';

	public function run()
	{
        parent::run();
        
        $requestAttributes = $this->restParams;
		$model = $this->controller->getModel($this->scenario);

		$attributesList = $model->getCreateAttributes();

		$attributes = array();
		foreach ($attributesList as $key) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}
        
		$model->setAttributes($attributes);

		if ($model->save()) {
            $responseParams[$this->restResponse->resultsNode] = array($model->getAttributesForResponse());
            $this->restResponse->sendResponse(200, $responseParams);
		} else {
			$this->restResponse->sendResponse(400, array('errors' => $model->getErrors()));
		}
	}

}