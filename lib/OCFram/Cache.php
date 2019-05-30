<?php

namespace OCFram;

use DateInterval;
use DateTime;
use DateTimeZone;

/**
 * Class Cache
 * @package OCFram
 */
class Cache extends ApplicationComponent
{
    /**
     * @var string $timeStamp
     */
    protected $timeStamp;

    /**
     * @var string $fileName
     */
    protected $fileName;

    /**
     * @var array $fileArray
     */
    protected $fileArray = [];

    /**
     * @var array $datas
     */
    protected $datas = [];

    public function makeTimeStamp()
    {
        $dateTime = new DateTime('NOW', new DateTimeZone('Europe/Paris'));
        $expiration = $this->app()->config()->get('cacheExpiration');

        $dateTime->add(DateInterval::createFromDateString($expiration));
        $this->timeStamp = $dateTime->getTimestamp();
    }

    /**
     * @return bool
     */
    public function getTimeStamp()
    {
        if (!empty($this->fileArray)) {
            return false;
        }

        $this->timeStamp = $this->fileArray[0];

        return true;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $dateTime = new DateTime('NOW', new DateTimeZone('Europe/Paris'));
        $now = $dateTime->getTimestamp();
        $this->getTimeStamp();
        if ((int)$this->timeStamp - (int)$now >= 0) {
            return false;
        }

        $this->deleteFile();

        return true;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->fileName);
    }

    public function getFile()
    {
        $this->fileArray = file($this->fileName, FILE_SKIP_EMPTY_LINES);
    }

    /**
     * @return bool
     */
    public function deleteFile()
    {
        if (!$this->exists()) {
            return false;
        }

        return unlink($this->fileName);
    }

    /**
     * @param string $file
     * @return bool|mixed
     */
    public function getData($file)
    {
        $this->setDataPath($file);

        if (!$this->exists()) {
            return false;
        }

        $this->getFile();

        if ($this->isExpired()) {
            return false;
        }

        $fileArray = $this->fileArray[1];
        $data = unserialize($fileArray);

        if (empty($data)) {
            return false;
        }

        return $data;
    }

    /**
     * @param string $file
     * @param array $entities
     */
    public function saveData($file, array $entities)
    {
        $this->setDataPath($file);
        $this->makeTimeStamp();
        if (!$this->exists()) {
            return;
        }
        file_put_contents($this->fileName, $this->timeStamp() . PHP_EOL);
        file_put_contents($this->fileName, serialize($entities), FILE_APPEND);
    }

    /**
     * @param string $file
     */
    public function setDataPath($file)
    {
        $this->fileName = $this->app()->config()->get('cache') . 'datas' . '/' . $file;
    }

    /**
     * @param BackController $controller
     * @return bool|Page
     */
    public function getView($controller)
    {
        $this->setViewPath($controller);
        $content = '';

        if ($this->exists()) {
            return false;
        }

        $this->getFile();

        if ($this->isExpired()) {
            return false;
        }

        foreach ($this->fileArray as $key => $line) {
            if ($key > 0) {
                $content .= $line;
            }
        }

        if (empty($content)) {
            return false;
        }

        $page = new Page($this->app());
        $page->setContentCache($content);

        return $page;
    }

    /**
     * @param BackController $controller
     * @param $html
     */
    public function saveView($controller, $html)
    {
        $this->setViewPath($controller);
        $this->makeTimeStamp();
        file_put_contents($this->fileName, $this->timeStamp() . PHP_EOL);
        file_put_contents($this->fileName, $html, FILE_APPEND);
    }

    /**
     * @param BackController $controller
     */
    public function setViewPath($controller)
    {
        $this->fileName = $this->app()->config()->get('cache') . 'views' . '/' .
            $this->app()->name() . '_' .
            $controller->module() . '_' .
            $controller->action() . '-' .
            $controller->viewId();
    }

    /**
     * @return mixed
     */
    public function timeStamp()
    {
        return $this->timeStamp;
    }

    /**
     * @return mixed
     */
    public function datas()
    {
        return $this->datas;
    }

    /**
     * @return mixed
     */
    public function fileName()
    {
        return $this->fileName;
    }

    /**
     * @return array
     */
    public function fileArray()
    {
        return $this->fileArray;
    }

    /**
     * @param $timeStamp
     */
    public function setTimeStamp($timeStamp)
    {
        $this->timeStamp = $timeStamp;
    }

    /**
     * @param array $datas
     */
    public function setDatas(array $datas)
    {
        if (!empty($datas) && is_array($datas)) {
            $this->datas = $datas;
        }
    }

    /**
     * @param $fileName
     */
    public function setFileName($fileName)
    {
        if (!empty($fileName) && is_string($fileName)) {
            $this->fileName = $fileName;
        }
    }

    /**
     * @param array $fileArray
     */
    public function setFileArray(array $fileArray)
    {
        if (!empty($fileArray) && is_array($fileArray)) {
            $this->fileArray = $fileArray;
        }
    }
}
