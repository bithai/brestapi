<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestDeleteAction extends CAction
{
	public function run()
	{
        
		$model = $this->getController()->getModel();
		if ($model->delete()) {
            error_log('delete success');
			$this->getController()->restResponse->sendResponse(200);
		} else {
            error_log('delete not success');
			$this->getController()->restResponse->sendResponse(400);
		}
	}

}