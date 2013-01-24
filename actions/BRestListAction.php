<?php
/**
 * @author Truong-An Thai <tthai1980@gmail.com>
 * @link http://www.bithai.me
 * @copyright Copyright (c) 2012 Truong-An Thai
 * 
 */
class BRestListAction extends BRestAction
{

    public $fields = array();
	public $filterBy = array();
	public $limit = 'limit';
	public $order = 'order';
    public $offset = 'offset';
    public $total = 'total';
    

	public function run()
	{

        // initialize common list options
        $this->initCommonListParamOptions();
        
        // call parent run
        parent::run();
        
        $requestParams = $this->restParams;
        
		$c = new CDbCriteria();

		foreach ($this->filterBy as $key => $val) {
			if (!is_null(Yii::app()->request->getParam($val)))
            {   
				$c->compare($key, Yii::app()->request->getParam($val), true);
            }
        }

		$model = $this->getController()->getModel();
     
        $c->limit = $requestParams[$this->limit];
        $c->offset = $requestParams[$this->offset];
		$c->order = $requestParams[$this->order] ? $requestParams[$this->order] : $model->getMetaData()->tableSchema->primaryKey;

		$models = $model->findAll($c);
        $total = $model->count($c);

		$result = array();
		if ($models) {
			foreach ($models as $item) {
				$result[] = $item->getAttributesForResponse();
			}
		}
        
        // init results meta
        $metaParams = $this->buildListMetaResults($total, $requestParams[$this->limit], $requestParams[$this->offset]);
        
        // init results array with a node
        $responseParams[$this->restResponse->resultsNode] = $result;
        // merge array with meta then results data
        $responseParams = array_merge($metaParams, $responseParams);
        
		$this->restResponse->sendResponse(200, $responseParams);
    }
    

}
