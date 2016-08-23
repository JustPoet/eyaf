<?php
use Illuminate\Database\Capsule\Manager as Capsule;
use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Loader;
use Yaf\Registry;

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

    public function _initConfig(Dispatcher $dispatcher)
    {
        Loader::import(APPLICATION_PATH . '/conf/defines.inc.php');
        $this->config = Application::app()->getConfig();
        Registry::set('config', $this->config);
        define('REQUEST_METHOD', strtoupper($dispatcher->getRequest()->getMethod()));
    }

    public function _initPlugin(Dispatcher $dispatcher)
    {
        $securityPlugin = new SecurityPlugin();
        $dispatcher->registerPlugin($securityPlugin);

        if (ini_get('yaf.environ') != 'online') {
            $queryLogPlugin = new QuerylogPlugin();
            $dispatcher->registerPlugin($queryLogPlugin);
        }
    }

    public function _initRoute(Dispatcher $dispatcher)
    {
    }

    public function _initDatabase()
    {
        $capsule = new Capsule;
        $capsule->addConnection($this->config->database->toArray());
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        if (ini_get('yaf.environ') != 'online') {
            $capsule->getConnection()->enableQueryLog();
        }
    }

    public function _initView(Dispatcher $dispatcher)
    {
        if (REQUEST_METHOD !== 'CLI') {
            $modules_names = explode(',', $this->config->application->modules);
            $paths = [APPLICATION_PATH . '/application/views'];
            array_walk($modules_names, function ($v) use (&$paths) {
                if (is_dir(APPLICATION_PATH . '/application/modules/' . $v . '/views')) {
                    array_push($paths, APPLICATION_PATH . '/application/modules/' . $v . '/views');
                }
            });
            $dispatcher->setView(new TwigAdapter($paths, $this->config->twig->toArray()));
        }
    }

    public function _initAutoload()
    {
        Loader::getInstance(rtrim(APP_PATH, '/'))->registerLocalNamespace('Service');
    }
}
