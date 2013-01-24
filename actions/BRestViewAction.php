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
        
        
        $criteria = new CDbCriteria;
        
        $options = array();

        if(isset($this->restParams['include'])) {
            $includeArr = explode(",",$this->restParams['include']);
            
            $withArr = array();
            if(in_array('organization', $includeArr)) {
                $withArr[] = 'organization';
            }
            
            // As of right now we don't have 'registration' relation defined on opportunity yet
            //if(in_array('registration', $includeArr)) {
                //$withArr[] = 'registration';
            //}
            
            // set the CDbCriteria with property accordingly
            $criteria->with = $withArr;
            $options['includeArr'] = $includeArr;
           
        }
        
		$model = $this->getController()->getModel('', $criteria);
        $responseParams[$this->restResponse->resultsNode] = array($model->getAttributesForResponse($options));
		$this->restResponse->sendResponse(200, $responseParams);
	}

}