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
    public function routerStartup(Request_Abstract $request, Response_Abstract $response)
    {
        CSRF::init();
        $waf = new Waf();
        $waf->filter();
    }
}
