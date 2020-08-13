<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 13/08/20
 * Time: 14:02
 */

namespace App\Backend\Modules\Crontab\Executor;


use GuzzleHttp\Client;
use Sldevand\Cron\ExecutorInterface;

/**
 * Class GuzzleExecutor
 * @package App\Backend\Modules\Crontab\Executor
 */
class GuzzleExecutor implements ExecutorInterface
{
    public function execute()
    {


        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://httpbin.org',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

    }

    /**
     *
     */
    public function getDescription()
    {
        return 'Executes a request to an url';
    }
}
