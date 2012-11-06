<?php
class SimpleKeySecretValidator extends BRestRequestValidator
{
    public function run() 
    {
        error_log("SimpleKeySecretValidator->run()");
        //$restResponse = Yii::app()->getController()->restResponse;
        //$restResponse->sendResponse(401);
        
        return true;
    }
}
?>
