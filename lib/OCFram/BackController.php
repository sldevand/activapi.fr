<?php

namespace OCFram;

/**
 * Class BackController
 * @package OCFram
 */
abstract class BackController extends ApplicationComponent
{
    /**
     * @var string
     */
    protected $action = '';
    /**
     * @var string
     */
    protected $module = '';
    /**
     * @var null|Page
     */
    protected $page = null;
    /**
     * @var string
     */
    protected $view = '';
    /**
     * @var null|Managers
     */
    protected $managers = null;
    /**
     * @var null|Cache
     */
    protected $cache = null;

    /**
     * BackController constructor.
     * @param Application $app
     * @param $module
     * @param $action
     */
    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->managers = new Managers('PDO', PDOFactory::getSqliteConnexion());
        $this->page = new Page($app);

        $this->setModule($module);
        $this->setAction($action);
        $this->setView($action);
        $this->cache = new Cache($this->app());
    }

    /**
     * @param $module
     */
    public function setModule($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
        }

        $this->module = $module;
    }

    /**
     * @param $action
     */
    public function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }

    /**
     * @param $view
     */
    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }

        $this->view = $view;

        $this->page->setContentFile(__DIR__ . '/../../App/' . $this->app->name() . '/Modules/' . $this->module . '/Views/' . $this->view . '.php');
    }

    /**
     *
     */
    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);

        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action "' . $this->action . '" n\'est pas définie sur ce module');
        }

        $this->$method($this->app->httpRequest());
    }

    /**
     *
     */
    public function deleteCache()
    {
        $folderRoot = $this->app()->config()->get('cache');

        if (!file_exists($folderRoot)) {
            $arrayRoot = scandir($folderRoot);

            array_shift($arrayRoot);
            array_shift($arrayRoot);

            foreach ($arrayRoot as $folderData) {

                if (file_exists($folderRoot . $folderData)) {

                    $arrayFolderData = scandir($folderRoot . $folderData);

                    array_shift($arrayFolderData);
                    array_shift($arrayFolderData);

                    foreach ($arrayFolderData as $file) {

                        $this->cache()->setFileName($folderRoot . $folderData . '/' . $file);
                        $this->cache()->deleteFile();
                    }

                } else {
                    echo 'Le dossier ' . $folderRoot . $folderData . ' n\'existe pas! <br>';
                }
            }
        } else {
            echo 'Le dossier ' . $folderRoot . ' n\'existe pas! <br>';
        }
    }

    /**
     * @return null|Cache
     */
    public function cache()
    {
        return $this->cache;
    }

    /**
     * @return null|Page
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @return string
     */
    public function module()
    {
        return $this->module;
    }


    //SETTERS

    /**
     * @return string
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function view()
    {
        return $this->view;
    }

    /**
     * @return mixed
     */
    public function viewId()
    {
        return $this->viewId;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        if (empty($cache)) {
            throw new \InvalidArgumentException('Le ne doit pas être un Cache vide!');
        }

        $this->cache = $cache;
    }

    /**
     * @param $viewId
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;
    }

    /**
     * @param $blockName
     * @param mixed ...$args
     * @return false|string
     */
    public function getBlock($fileName, ...$args)
    {
        ob_start();
        include $fileName;

        return ob_get_clean();
    }
}
