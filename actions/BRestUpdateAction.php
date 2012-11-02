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
		$requestAttributes = $this->getController()->restRequest->getParams();

        // probably would need to support model and validator scenarios
        // so that only allowed attributes can be saved...
		$model = $this->getController()->getModel();

        $params = $model->getAttributes();

        $attributes = array();
		foreach ($params as $key => $value) {
			if (isset($requestAttributes[$key])) {
				$attributes[$key] = $requestAttributes[$key];
			}
		}

		$model->setAttributes($attributes);

		if ($model->save()) {
			$this->getController()->restResponse->sendResponse(200, $model->getAllAttributes());
		} else {
			$this->getController()->restResponse->sendResponse(400, array('errors' => $model->getErrors()));
		}
	}

}