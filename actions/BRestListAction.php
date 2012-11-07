<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestListAction extends CAction
{

    public $fields = array();
	public $filterBy = array();
	public $limit = 'limit';
	public $order = 'order';
    public $offset = 'offset';
    
    


    /**
     * This method can be called by the run method for list type actions
     * to initialize some common param options
     */
    public function initCommonListParamOptions()
    {
        
        $restRequest = $this->getController()->restRequest;
        $restRequest->setParam($this->limit, $restRequest->getParam($this->limit, 30));
        $restRequest->setParam($this->offset, $restRequest->getParam($this->offset, 0));
        $restRequest->setParam($this->order, $restRequest->getParam($this->order, null));

    }
    
	public function run()
	{

        // initialize options
        $this->initCommonListParamOptions();
        
        $requestParams = $this->getController()->restRequest->getParams();
        
		$c = new CDbCriteria();

		foreach ($this->filterBy as $key => $val) {
			if (!is_null(Yii::app()->request->getParam($val)))
				$c->compare($key, Yii::app()->request->getParam($val));
		}


		$model = $this->getController()->getModel();
     
        $c->limit = $requestParams['limit'];
        $c->offset = $requestParams['offset'];
		$c->order = $requestParams['order'] ? $requestParams['order'] : $model->getMetaData()->tableSchema->primaryKey;

		$models = $model->findAll($c);
        $total = $model->count($c);

		$result = array();
		if ($models) {
			foreach ($models as $item) {
				$result[] = $item->getAttributesForResponse();
			}
		}
        
        $metaParams['total'] = $total;
        $metaParams['limit'] = $requestParams['limit'];
        $metaParams['offset'] = $requestParams['offset'];
        
		$this->getController()->restResponse->sendResponse(200, $result, $metaParams);
    }
    

}
