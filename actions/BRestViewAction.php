<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestViewAction extends CAction
{

	public function run()
	{
		$model = $this->getController()->getModel();
		$this->getController()->restResponse->sendResponse(200, $model->getAttributes());
	}

}