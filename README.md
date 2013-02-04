brestapi
========

Yii style Restful Extension for building REST API Service

1. Drop the BRestAPI folder into your yii extensions path

2. Add to import in configuration file.

   // autoloading model and component classes
	'import'=>array(
		//.....
        'ext.brestapi.*',
	),

3. Add to your URL Manager configuration the default rest urls routing

   'urlManager'=>array(
       'urlFormat'=>'path',
	   'showScriptName' => false,
            'rules'=>array(

                //rest url patterns
                array('api/<resource>/list', 'pattern'=>'api/<version>/<resource:\w+>', 'verb'=>'GET'),
                array('api/<resource>/view', 'pattern'=>'api/<version>/<resource:\w+>/<id:\d+>', 'verb'=>'GET'),
                array('api/<resource>/create', 'pattern'=>'api/<version>/<resource:\w+>', 'verb'=>'POST'),
                array('api/<resource>/delete', 'pattern'=>'api/<version>/<resource:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                array('api/<resource>/update', 'pattern'=>'api/<version>/<resource:\w+>/<id:\d+>', 'verb'=>'PUT'),
           
            // ....
             )
     )


4. It is suggested you create a separate module for your api code.  You can use Gii to generate a module called 'api' so that
it setups the modules/api folder and ApiModule.php, controllers and views folders for you.

5. The quickest way to see this work is to create a simple controller representing a resource.

For example, if you have a data model class called "Book" and you want to expose the default resource actions for it, do something like this:

class BooksController extends BRestController
{
    
    protected $modelName = "book";
    
    public function init()
    {
        parent::init();
    }
    
    public function actions() //determine which of the standard actions will support the controller
    {
        return array(
            'list' => array( //use for get list of objects
                'class' => 'BRestListAction',
                'filterBy' => array( //this param user in `where` expression when forming an db query
                    'level' => 'level', // 'name_in_table' => 'request_param_name'
                    'title' => 'title',
                ),
                'limit' => 'limit', //request parameter name, which will contain limit of object
                'order' => 'order', //request parameter name, which will contain ordering for sort
            ),
            'view' => 'BRestViewAction',
            'create' => 'BRestCreateAction', //provide 'scenario' param
            'update' => array(
                'class' => 'BRestUpdateAction',
                'scenario' => 'update', //as well as in BRestCreateAction optional param
                ),
            'delete' => 'BRestDeleteAction',
            );
    }
}


Then you can test this by doing a GET request at yoursite.com/api/books   







