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

    public function getRequestParams($fields = null, $type = 'REQUEST')
    {
        if ($fields === null) {
            $request = $this->getRequest()->getRequest();
            $param = $this->getRequest()->getParams();
            return array_merge($request, $param);
        }

        switch (strtoupper($type)) {
            case 'POST':
                $params = $this->getRequest()->getPost();
                break;
            case 'GET':
                $params = $this->getRequest()->getQuery();
                break;
            case 'PARAM':
                $params = $this->getRequest()->getParams();
                break;
            case 'REQUEST':
                $params = $this->getRequest()->getRequest();
                break;
            default:
                $params = [];
        }

        if (is_array($fields)) {
            $result = [];
            foreach ($fields as $field) {
                if (!isset($params[$field])) {
                    throw new Exception("'{$field}' can not be empty!", 4000);
                }
                $result[$field] = $params[$field];
            }
            return $result;
        } else {
            if (!isset($params[$fields])) {
                throw new Exception("'{$fields}' can not be empty!", 4000);
            }
            return $params[$fields];
        }
    }

    public function getPage()
    {
        return new Page($this->getRequest()->getQuery('page', 1));
    }
}
