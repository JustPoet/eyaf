<?php
use Yaf\Plugin_Abstract;
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use Security\CSRF;
use Security\Waf;

/**
 * Class SecurityPlugin
 *
 * 安全插件
 */
class SecurityPlugin extends Plugin_Abstract
{
    /**
     * 不做CSRF校验的路径
     * @var array
     */
    protected $expect = [];

    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {
        if (!in_array(strtolower($request->getRequestUri()), $this->expect)) {
            CSRF::init();
        }
    }
}
