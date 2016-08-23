<?php
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * @name   QuerylogPlugin
 * @desc   Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see    http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author zhengzean
 */
class QuerylogPlugin extends Yaf\Plugin_Abstract
{
    public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response)
    {
        $queryLogs = Capsule::getQueryLog();
        foreach ($queryLogs as $key => $log) {
            @header('sql' . $key . ':' . json_encode($log, JSON_UNESCAPED_UNICODE));
        }
    }
}
