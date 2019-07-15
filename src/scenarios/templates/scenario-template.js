export class ScenarioTemplate {
    static render(scenario) {
        if (!scenario || scenario.error) {
            scenario.id = 0;
            scenario.nom = '';
        }
        scenario.id = scenario.id || 0;
        scenario.nom = scenario.nom || '';

        return `
    <div class="row">
        <div class="col s6">
            <label for="scenario-name-${scenario.id}" class="active">Nom</label>
            <input type="text" name="nom" id="scenario-name-${scenario.id}" value="${scenario.nom}" required>
        </div>
        <div class="col s6">
            <label for=status" class="active">Statut</label>
            <div class="select-wrapper"><span class="caret">▼</span>
                <select name="status" id="status">               
                   <option value="play" selected>Play</option>
                   <option value="stop" selected>Stop</option>                   
                </select>
            </div>
        </div>        
    </div>
    <div class="row">
        <div id="sequences" class="s12"></div>
    </div>
`;
    }
}
