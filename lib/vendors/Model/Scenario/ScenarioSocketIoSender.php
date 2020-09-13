<?php

namespace Model\Scenario;

use Entity\Scenario\Scenario;
use OCFram\ApplicationComponent;
use Psinetron\SocketIO;

/**
 * Class ScenarioSocketIoSender
 * @package Model\Scenario
 */
class ScenarioSocketIoSender extends ApplicationComponent
{
    /**
     * @param Scenario $scenario
     * @return bool
     * @throws \Exception
     */
    public function send(Scenario $scenario)
    {
        $ip = $this->app()->config()->getEnv('NODE_IP');
        $port = $this->app()->config()->getEnv('NODE_PORT');
        $action = 'updateScenario';
        $dataJSON = json_encode($scenario);

        $socketio = new SocketIO();

        return $socketio->send($ip, $port, $action, $dataJSON);
    }
}
