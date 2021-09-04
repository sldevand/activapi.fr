<?php

namespace OCFram;

/**
 * Class Page
 * @package OCFram
 */
class Page extends ApplicationComponent
{
    /**
     * @var string $contentFile
     */
    protected $contentFile;

    /**
     * @var string $contentCache
     */
    protected $contentCache = '';

    /**
     * @var array $vars
     */
    protected $vars = [];

    /**
     * @param string $var
     * @param mixed $value
     * @return Page
     */
    public function addVar($var, $value)
    {
        if (empty($var) || !is_string($var) || is_numeric($var)) {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;

        return $this;
    }

    /**
     * @return false|string
     */
    public function getGeneratedPage()
    {
        $user = $this->app->user();
        if (!empty($this->contentCache)) {
            ob_start();
            echo $this->contentCache;

            return ob_get_clean();
        }

        if (!file_exists($this->contentFile)) {
            throw new \RuntimeException('La vue spécifiée n\'existe pas');
        }

        extract($this->vars);

        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.phtml';

        return ob_get_clean();
    }

    /**
     * @return false|string
     */
    public function getGeneratedJSON()
    {
        if (!empty($this->contentCache)) {
            ob_start();
            echo $this->contentCache;
            return ob_get_clean();
        } else {
            if (!file_exists($this->contentFile)) {
                throw new \RuntimeException('La vue spécifiée n\'existe pas');
            }

            extract($this->vars);

            ob_start();
            require $this->contentFile;
            $content = ob_get_clean();

            return $content;
        }
    }

    /**
     * @param string $contentFile
     */
    public function setContentFile(string $contentFile)
    {
        if (empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }

    /**
     * @param string $contentCache
     */
    public function setContentCache($contentCache)
    {
        if (!is_string($contentCache) || empty($contentCache)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentCache = $contentCache;
    }

    /**
     * @return string
     */
    public function contentCache()
    {
        return $this->contentCache;
    }
}
