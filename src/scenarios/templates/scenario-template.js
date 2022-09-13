export class ScenarioTemplate {
    static render(scenario) {
        if (!scenario || scenario.error) {
            scenario.id = 0;
            scenario.nom = '';
        }
        scenario.id = scenario.id || 0;
        scenario.nom = scenario.nom || '';
        let statuses = {
            'stop':'Stop',
            'play': 'Play'
        };

        let template = `
    <div class="row">
        <div class="col s6">
            <label for="scenario-name-${scenario.id}" class="active">Nom</label>
            <input type="text" name="nom" id="scenario-name-${scenario.id}" value="${scenario.nom}" required>
        </div>
        <div class="col s6">
            <label for=status" class="active">Statut</label>
            <div class="select-wrapper"><span class="caret">▼</span>
                <select name="status" id="status">`;
                for(let statusKey in statuses)
                {
                    let selected='';
                    if(statusKey === scenario.status){
                        selected = 'selected';
                    }
                    template += `<option value="${statusKey}" ${selected}>${statuses[statusKey]}</option>`;
                }
                template += `
                </select>
            </div>
        </div>
        <div class="col s6">
            <label for=visibility" class="active">Visible</label>
            <div class="select-wrapper"><span class="caret">▼</span>
                <select name="visibility" id="visibility">
                    <option value="0">Non</option>
                    <option value="1">Oui</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="sequences" class="s12"></div>
    </div>
`;
        return template;
    }
}
