<?php

namespace OCFram;

use App\Frontend\Modules\Graphs\GraphsController;
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

    /**
     * @throws \Exception
     */
    protected function makeTimeStamp()
    {
        if (!$this->enabled()) {
            return;
        }

        $dateTime = new DateTime('NOW', new DateTimeZone('Europe/Paris'));
        $expiration = $this->app()->config()->get('cache_expiration');

        $dateTime->add(DateInterval::createFromDateString($expiration));
        $this->timeStamp = $dateTime->getTimestamp();
    }

    /**
     * @return bool
     */
    public function getTimeStamp()
    {
        if (!$this->fileArray) {
            return false;
        }

        $this->timeStamp = $this->fileArray[0];

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isExpired()
    {
        if (!$this->enabled()) {
            return true;
        }

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
     * @throws \Exception
     */
    public function enabled()
    {
        return (bool)$this->app()->config()->get('cache');
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
     * @throws \Exception
     */
    public function getData(string $file)
    {
        if (!$this->enabled()) {
            return false;
        }

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
     * @throws \Exception
     */
    public function saveData(string $file, array $entities)
    {
        if (!$this->enabled()) {
            return;
        }

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
     * @throws \Exception
     */
    public function setDataPath(string $file)
    {
        $this->fileName = $this->app()->config()->get('cache_dir') . '/datas/' . $file;
    }

    /**
     * @param \OCFram\BackController $controller
     * @return string | bool
     * @throws \Exception
     */
    public function getView(BackController $controller)
    {
        $this->setViewPath($controller);
        $content = '';

        if (!$this->exists()) {
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

        return $content;
    }

    /**
     * @param $controller
     * @param string $html
     * @throws \Exception
     */
    public function saveView($controller, string $html)
    {
        if (!$this->enabled()) {
            return;
        }
        $this->setViewPath($controller);
        $this->makeTimeStamp();
        file_put_contents($this->fileName, $this->timeStamp() . PHP_EOL);
        file_put_contents($this->fileName, $html, FILE_APPEND);
    }

    /**
     * @param BackController $controller
     * @param string $appName
     * @throws \Exception
     */
    public function setViewPath(BackController $controller, string $appName = '')
    {
        $appName = $appName ?: $this->app()->name();
        $this->fileName = $this->app()->config()->get('cache_dir') . 'views' . '/' .
            $appName . '_' .
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
     * @param string $timeStamp
     * @return $this
     */
    public function setTimeStamp(string $timeStamp)
    {
        $this->timeStamp = $timeStamp;

        return $this;
    }

    /**
     * @param array $datas
     * @return $this
     */
    public function setDatas(array $datas)
    {
        $this->datas = $datas;

        return $this;
    }

    /**
     * @param string $fileName
     * @return \OCFram\Cache
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @param array $fileArray
     * @return $this
     */
    public function setFileArray(array $fileArray)
    {
        $this->fileArray = $fileArray;

        return $this;
    }
}
