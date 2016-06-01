<?php

/**
 * Class TestCase
 *
 * 测试基类
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * yaf运行实例
     *
     * @var Yaf_Application
     */
    protected $application = null;

    /**
     * 构造方法，调用application实例化方法
     */
    public function __construct()
    {
        $this->application = $this->getApplication();
        parent::__construct();
    }

    /**
     * 设置application
     */
    public function setApplication()
    {
        $application = new Yaf\Application(APPLICATION_PATH . "/conf/application.ini");
        $application->bootstrap();
        Yaf\Registry::set('application', $application);

        return $application;
    }

    /**
     * 获取application
     *
     * @return Yaf_Application
     */
    public function getApplication()
    {
        $application = Yaf\Registry::get('application');
        if (!$application) {
            $application = $this->setApplication();
        }

        return $application;
    }

}
