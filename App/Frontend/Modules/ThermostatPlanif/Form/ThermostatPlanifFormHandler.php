<?php

namespace App\Frontend\Modules\ThermostatPlanif\Form;

use OCFram\FormHandler;

/**
 * Class ThermostatPlanifFormHandler
 * @package App\Frontend\Modules\ThermostatPlanif\Form
 */
class ThermostatPlanifFormHandler extends FormHandler
{
    /**
     * @return bool
     * @throws \Exception
     */
    protected function beforeProcess()
    {
        $name = $this->request->postData('nom') ?: '';
        if (!$this->alreadyExists($name)) {
            return true;
        }

        $this->getMessageHandler()->addMessage("Le planning $name existe déjà!");

        return false;
    }

    /**
     * @param string $name
     * @return bool
     * @throws \Exception
     */
    protected function alreadyExists(string $name): bool
    {
        if ($this->manager->getNom($name, 'nom')) {
            return true;
        }

        return false;
    }
}
