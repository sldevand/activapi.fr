<?php

namespace FormBuilder;

use OCFram\FormBuilder;
use OCFram\SelectField;
use OCFram\StringField;
use OCFram\TimePickerField;

/**
 * Class ThermostatPlanifFormBuilder
 * @package FormBuilder
 */
class ThermostatPlanifFormBuilder extends FormBuilder
{
    /**
     * @return \OCFram\Form
     */
    public function build()
    {
        $modesRaw = json_decode(json_encode($this->getData('modes')), true);
        $modes = [];
        foreach ($modesRaw as $key => $mode) {
            $modes[$mode["id"]] = $mode["nom"];
        }

        return $this->form
            ->add(
                new StringField([
                    'name' => 'nomid',
                    'value' => $this->form()->entity()->nomid(),
                    'hidden' => 'hidden'
                ])
            )->add(
                new SelectField([
                    'label' => 'mode',
                    'name' => 'modeid',
                    'selected' => $this->form()->entity()->modeid(),
                    'options' => $modes
                ])
            )->add(
                new SelectField([
                    'label' => 'defaultMode',
                    'name' => 'defaultModeid',
                    'selected' => $this->form()->entity()->defaultModeid(),
                    'options' => $modes
                ])
            )->add(
                new TimePickerField([
                    'label' => 'heure1Start',
                    'name' => 'heure1Start'
                ])
            )->add(
                new TimePickerField([
                    'label' => 'heure1Stop',
                    'name' => 'heure1Stop'
                ])
            )->add(
                new TimePickerField([
                    'label' => 'heure2Start',
                    'name' => 'heure2Start'
                ])
            )->add(
                new TimePickerField([
                    'label' => 'heure2Stop',
                    'name' => 'heure2Stop'
                ])
            );
    }
}
