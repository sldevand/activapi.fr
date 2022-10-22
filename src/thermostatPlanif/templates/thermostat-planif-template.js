export class ThermostatPlanifTemplate {
    static render(thermostatPlanif) {
        if (!thermostatPlanif) {
            return;
        }

        let template='';
        thermostatPlanif.timetable.forEach((minuteModeId, index) => {
        template += `
            <div class="row">
                <div class="col s6">
                    <label for="thermostat-planif-time-${index}" class="active">Time</label>
                    <input class="timepicker" type="text" name="timetable[]" id="thermostatPlanif-planif-time-${index}" value="${minuteModeId.hour}" required>
                </div>
                <div class="col s6">
                    <label for="thermostat-planif-modeId-${index}" class="active">Mode</label>
                    <input type="text" name="timetable[]" id="thermostat-planif-modeId-${index}" value="${minuteModeId.modeId}" required>
                </div>       
            </div>
        `;
        });

        return template;
    }
}
