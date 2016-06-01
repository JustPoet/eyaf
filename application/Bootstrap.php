<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Yaf\Bootstrap_Abstract;
use Yaf\Application;
use Yaf\Registry;
use Yaf\Loader;
use Yaf\Dispatcher;

/**
 * @name Bootstrap
 * @author zhengzean
 * @desc   所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see    http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Bootstrap_Abstract
{
    protected $config;

    public function _initConfig()
    {
        Loader::import(APPLICATION_PATH . '/conf/defines.inc.php');
        $this->config = Application::app()->getConfig();
        Registry::set('config', $this->config);
    }

    public function _initPlugin(Dispatcher $dispatcher)
    {
        $securityPlugin = new SecurityPlugin();
        $dispatcher->registerPlugin($securityPlugin);
    }

    public function _initRoute(Dispatcher $dispatcher)
    {
    }

    public function _initDatabase()
    {
        //初始化eloquent
        $capsule = new Capsule;
        $capsule->addConnection($this->config->database->toArray());
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public function _initView(Dispatcher $dispatcher)
    {
        //初始化twig
        $twig = new TwigAdapter(APPLICATION_PATH . '/application/views', $this->config->twig->toArray());
        $dispatcher->setView($twig);
    }

    public function _initAutoload()
    {
        //添加Service目录对应的命名空间
        Loader::getInstance(rtrim(APP_PATH, '/'))->registerLocalNamespace('Service');
    }
}
