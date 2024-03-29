<?php

namespace OCFram;

use Exception;
use Materialize\FormView;

/**
 * Class BackController
 * @package OCFram
 */
abstract class BackController extends ApplicationComponent
{
    use FormView;

    /** @var string */
    protected $action = '';

    /** @var string */
    protected $module = '';

    /** @var Page */
    protected $page;

    /** @var string */
    protected $view = '';

    /** @var int */
    protected $viewId = '';

    /** @var Managers */
    protected $managers;

    /** @var Cache */
    protected $cache;

    /** @var string */
    protected $baseAddress;

    /** @var \OCFram\MessageHandler */
    protected $messageHandler;

    /**
     * @var bool
     */
    protected $restricted = false;

    /**
     * BackController constructor.
     * @param Application $app
     * @param string $module
     * @param string $action
     * @throws Exception
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
        $this->baseAddress = $app->config()->getEnv('BASE_URL');
        $this->messageHandler = new MessageHandler();
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
     * @param string $view
     */
    public function setView(string $view)
    {
        if (empty($view)) {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }

        $this->view = $view;
        $contentFile = __DIR__ . '/../../App/'
            . $this->app->name()
            . '/Modules/'
            . $this->module
            . '/Views/'
            . $this->view
            . '.php';

        $this->page->setContentFile($contentFile);
    }

    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);

        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action "' . $this->action . '" n\'est pas définie sur ce module');
        }

        $this->$method($this->app->httpRequest());
    }

    /**
     * @throws \Exception
     */
    public function deleteCache()
    {
        $folderRoot = $this->app()->config()->get('cache');

        if (!file_exists($folderRoot)) {
            throw new \Exception("Le dossier $folderRoot n'existe pas!");
        }

        $arrayRoot = scandir($folderRoot);

        array_shift($arrayRoot);
        array_shift($arrayRoot);

        foreach ($arrayRoot as $folderData) {
            if (!file_exists($folderRoot . $folderData)) {
                continue;
            }

            $arrayFolderData = scandir($folderRoot . $folderData);

            array_shift($arrayFolderData);
            array_shift($arrayFolderData);

            foreach ($arrayFolderData as $file) {
                $this->cache()->setFileName($folderRoot . $folderData . '/' . $file);
                $this->cache()->deleteFile();
            }
        }
    }

    /**
     * @param string $action
     * @param string $appName
     * @param string $module
     * @throws Exception
     */
    protected function deleteActionCache(string $action, string $appName = '', string $module = '')
    {
        $controller = clone $this;
        $controller->setAction($action);
        if ($module) {
            $controller->setModule($module);
        }
        $this->cache()->setViewPath($controller, $appName);
        $this->cache()->deleteFile();
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
            throw new \InvalidArgumentException('Le cache ne doit pas être un Cache vide!');
        }

        $this->cache = $cache;
    }

    /**
     * @return false|string
     */
    public function deleteFormView()
    {
        return $this->getBlock(BLOCK . '/deleteFormView.phtml');
    }

    /**
     * @param $fileName
     * @param mixed ...$args
     * @return false|string
     */
    public function getBlock($fileName, ...$args)
    {
        ob_start();
        require $fileName;
        return ob_get_clean();
    }

    /**
     * @param HTTPRequest $httpRequest
     * @param string $method
     * @throws Exception
     */
    public function checkMethod($httpRequest, $method)
    {
        if ($httpRequest->method() !== $method) {
            throw new Exception(
                'Wrong method : '
                . $httpRequest->method()
                . ', use '
                . $method
                . ' method instead'
            );
        }
    }

    /**
     * @param array $jsonPost
     * @return void
     * @throws Exception
     */
    public function checkJsonBodyId($jsonPost)
    {
        if (!empty($jsonPost['id'])) {
            throw new Exception('JSON body contains an id');
        }
    }

    /**
     * @param array $jsonPost
     * @return void
     * @throws Exception
     */
    public function checkNotJsonBodyId($jsonPost)
    {
        if (empty($jsonPost['id'])) {
            throw new Exception('JSON body must contain an id');
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getApiUrl()
    {
        $apiBaseAddress = $this->app()->config()->getEnv('BASE_URL');

        return $apiBaseAddress . "api/mesures/";
    }

    /**
     * @return int
     */
    public function getViewId()
    {
        return $this->viewId;
    }

    /**
     * @param int $viewId
     */
    public function setViewId($viewId)
    {
        $this->viewId = $viewId;
    }

    /**
     * @return bool
     */
    public function isRestricted(): bool
    {
        return $this->restricted;
    }
}
