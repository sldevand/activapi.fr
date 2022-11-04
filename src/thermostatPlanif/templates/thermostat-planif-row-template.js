export class ThermostatPlanifRowTemplate {
    constructor() {
        this.prefixTimeId = 'thermostat-planif-time-';
        this.prefixModeId = 'thermostat-planif-modeId-';
    }

    render(minuteModeId, index) {
        let template = new DocumentFragment();
        let planifRow = document.getElementById('timetable-row-layout').content.cloneNode(true);
        this.createTimePicker(planifRow, minuteModeId, index);
        this.createModeSelect(planifRow, minuteModeId, index);
        template.append(planifRow);

        return template;
    }

    createTimePicker(planifRow, minuteModeId, index) {
        let timeId = `${this.prefixTimeId}${index}`;
        planifRow.getElementById(this.prefixTimeId).setAttribute('id', timeId);
        let timeElement = planifRow.getElementById(timeId);
        timeElement.setAttribute('name', timeId);
        if (minuteModeId) {
            timeElement.setAttribute('value', minuteModeId.hour);
        }
        timeElement.parentNode.prepend(this.createLabel(timeId, 'Time'));
    }

    createModeSelect(planifRow, minuteModeId, index) {
        let modeId = `${this.prefixModeId}${index}`;
        planifRow.getElementById(this.prefixModeId).setAttribute('id', modeId);

        let modeSelect = planifRow.getElementById(modeId);
        modeSelect.setAttribute('name', modeId);
        modeSelect.parentNode.parentNode.prepend(this.createLabel(modeId, 'Mode'));

        thermostatModes.forEach((mode) => {
            modeSelect.append(this.createOption(mode.id, mode.nom));
        });

        if (minuteModeId) {
            modeSelect.value = minuteModeId.modeId;
        }
    }

    createLabel(id, title) {
        let label = document.createElement("label");
        label.setAttribute('for', id);
        label.innerText = title;

        return label;
    }

    createOption(value, textContent) {
        let opt = document.createElement("option");
        opt.value = value;
        opt.textContent = textContent;

        return opt;
    }
}
