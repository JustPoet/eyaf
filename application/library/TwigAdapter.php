<?php
use Yaf\View_Interface;

/**
 * Class TwigAdapter
 *
 * Twig模板适配
 */
class TwigAdapter implements View_Interface
{
    /** @var Twig_Loader_Filesystem */
    protected $loader;
    /** @var Twig_Environment */
    protected $twig;
    protected $variables = [];

    /**
     * @param string $template_dir
     * @param array  $options
     */
    public function __construct($template_dir, array $options = [])
    {
        $this->loader = new Twig_Loader_Filesystem($template_dir);
        $this->twig = new Twig_Environment($this->loader, $options);
    }

    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Assign values to View engine, then the value can access directly by name in template.
     *
     * @link http://www.php.net/manual/en/yaf-view-interface.assign.php
     *
     * @param string|array $name
     * @param mixed        $value
     *
     * @return bool
     */
    function assign($name, $value = null)
    {
        $this->variables['server'] = $_SERVER;
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->variables[$k] = $v;
            }
        } else {
            $this->variables[$name] = $value;
        }
    }

    /**
     * Render a template and output the result immediately.
     *
     * @link http://www.php.net/manual/en/yaf-view-interface.display.php
     *
     * @param string $tpl
     * @param array  $tpl_vars
     *
     * @return bool
     */
    function display($tpl, $tpl_vars = null)
    {
        echo $this->render($tpl, $tpl_vars);
    }

    /**
     * @link http://www.php.net/manual/en/yaf-view-interface.getscriptpath.php
     *
     * @return string
     */
    function getScriptPath()
    {
        $paths = $this->loader->getPaths();

        return reset($paths);
    }

    /**
     * Render a template and return the result.
     *
     * @link http://www.php.net/manual/en/yaf-view-interface.render.php
     *
     * @param string $tpl
     * @param array  $tpl_vars
     *
     * @return string
     */
    function render($tpl, $tpl_vars = null)
    {
        if (is_array($tpl_vars)) {
            $this->variables = array_merge($this->variables, $tpl_vars);
        }

        return $this->twig->loadTemplate($tpl)->render($this->variables);
    }

    /**
     * Set the templates base directory, this is usually called by Yaf_Dispatcher
     *
     * @link http://www.php.net/manual/en/yaf-view-interface.setscriptpath.php
     *
     * @param string $template_dir An absolute path to the template directory, by default, Yaf_Dispatcher use
     *                             application.directory . "/views" as this parameter.
     */
    function setScriptPath($template_dir)
    {
        $this->loader->setPaths($template_dir);
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->variables[$name];
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

}