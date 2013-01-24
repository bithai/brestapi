<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestModelBehavior extends CActiveRecordBehavior
{

    protected $ownerModelAPIResponseMethod = 'getAPIResponseData';
    
    /**
     * Returns the final attributes array that is sent back to a client
     * 
     * In the real world we typically:
     *
     *    1) Don't want to return every field straight from the default model fields from database
     *    2) We want to modify/add additional fields or modify existing field values
     *       before response is sent to client
     * 
     * Requirements:  
     *    By default, this method will just return all of the owner (model) attributes
     *    To customize the attributes you must implement a public method in your model
     *    defined by the protected $ownerModelAPIResponseMethod property in this class.
     * 
     *    Example:
     * 
     *    public function getAPIResponseData($params=array()) 
     *    {
     * 
     *        $attributeNames = array('id', 'first_name', 'last_name', 'about');
     * 
     *        // get the attribute name and values from this model
     *        $attributes = $this->getAttributes($attributeNames);
     *    
     *   
     *        // modify/add additional attributes here (optional)
     *        $attributes['about'] = trim($this->about);
     *        $attributes['full_name'] = $this->first_name.' '.$this->last_name;
     *        $attributes['images'] = $this->getThumbUrlArray();
     *   
     *    
     *        return $attributes;
     * 
     *    }
     *    
     * 
     * @return array()
     */
    public function getAttributesForResponse($options = array())
    {
        $owner = $this->getOwner();
        $params = Yii::app()->getController()->restRequest->getParams();
        
        $params = CMap::mergeArray($params,$options);
        
        if(method_exists($owner, $this->ownerModelAPIResponseMethod)) {
            return $owner->getAPIResponseData($params);
        }
        else {
            return $owner->getAttributes();
        }
        
    }
    

    /**
     * Returns the attributes that are safe for object create
     * @return array
     */
	public function getCreateAttributes()
	{
		return $this->_getAttributesByScenario('insert');
	}

    
    /**
     * Returns the attributes that are safe for object update
     * @return array
     */
	public function getUpdateAttributes()
	{
		return $this->_getAttributesByScenario('update');
	}

    
    /**
     * Returns the attributes based on scenario
     * @param type $scenario
     * @return array
     */
	private function _getAttributesByScenario($scenario){
		$owner = $this->getOwner();
		$owner->setScenario($scenario);
		$validators = $owner->getValidators();
		$attributes = array();

		foreach ($validators as $validator){
			$attributes = CMap::mergeArray($attributes, $validator->attributes);
		}
		return $attributes;
	}

}
?>