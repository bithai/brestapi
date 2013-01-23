<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestViewAction extends BRestAction
{

	public function run()
	{
        parent::run();
        
		$model = $this->getController()->getModel();
        $responseParams[$this->restResponse->resultsNode] = array($model->getAttributesForResponse());
		$this->restResponse->sendResponse(200, $responseParams);
	}

}