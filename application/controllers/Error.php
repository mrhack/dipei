<?php
/**
 * @name ErrorController
 * @desc 错误控制器, 在发生未捕获的异常时刻被调用
 * @see http://www.php.net/manual/en/yaf-dispatcher.catchexception.php
 * @author wangfeng
 */
class ErrorController extends BaseController {

	//从2.1开始, errorAction支持直接通过参数获取异常
    /**
     * @param $exception Exception
     * @return bool
     */
    public function errorAction($exception) {
		//1. assign to view engine
//        var_dump($exception);
        if($exception instanceof AppException){
            $this->render_ajax($exception->getCode(), $exception->getMessage());
            return false;
        }else{
            $this->getLogger()->warn($exception->getMessage().":\n".$exception->getTraceAsString());
            $this->getView()->assign("exception", $exception);
        }
		//5. render by Yaf
	}
}
