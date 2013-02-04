<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
abstract class BRestAction extends CAction
{

    protected $restController;
    protected $restRequest;
    protected $restResponse;
    protected $restParams;
    
    
    /**
     * This method can be called by the run method for list type actions
     * to initialize some common param options.
     * 
     * If called in subclass, this method should be called first before running parent::run() 
     * to initialize 
     */
    protected function initCommonListParamOptions()
    {
        
        $this->restRequest = $this->getController()->restRequest;
        $this->restRequest->setParam($this->limit, $this->restRequest->getParam($this->limit, 30));
        $this->restRequest->setParam($this->offset, $this->restRequest->getParam($this->offset, 0));
        $this->restRequest->setParam($this->order, $this->restRequest->getParam($this->order, null));
        
        

    }
    
    protected function buildListMetaResults($total,$limit,$offset)
    {
        // init results meta
        $metaParams[$this->total] = $total;
        $metaParams[$this->limit] = $limit;
        $metaParams[$this->offset] = $offset;
        
        return $metaParams;
    }
 
	public function run()
	{
        
        $this->restController = Yii::app()->getController();
        $this->restRequest = Yii::app()->getController()->restRequest;
        $this->restResponse = Yii::app()->getController()->restResponse;
        $this->restParams = Yii::app()->getController()->restRequest->getParams();
  
        
	}
    

    

}