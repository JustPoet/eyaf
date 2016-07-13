<?php
use Yaf\Controller_Abstract;

/**
 * 控制器抽象类
 */
abstract class AbstractCtlr extends Controller_Abstract
{
    /**
     * json 输出
     *
     * @param $out
     * @return string | NULL
     */
    public function out($out)
    {
        $response = $this->getResponse();
        $outBody = json_encode($out);
        $response->setHeader('Content-type', 'application/json;charset=utf8');
        $response->setBody($outBody);
        $response->response();
    }
}
