<?php

namespace Model\Thermostat;

use OCFram\ApplicationComponent;
use Psinetron\SocketIO;

/**
 * Class SocketIoSender
 * @package Model\Thermostat
 */
class SocketIoSender extends ApplicationComponent
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function sendPwrOn()
    {
        $ip = $this->app()->config()->getEnv('NODE_IP');
        $port = $this->app()->config()->getEnv('NODE_PORT');
        $action = 'setTherPwr';
        $socketio = new SocketIO();

        return $socketio->send($ip, $port, $action, '1');
    }
}
