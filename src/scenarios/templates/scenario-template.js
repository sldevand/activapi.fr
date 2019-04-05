export class ScenarioTemplate {
    static render(scenario) {
        return `
    <div class="row">
        <div class="col s8">
            <label for="scenario-name-${scenario.id} ?>" class="active">Nom</label>
            <input type="text" name="nom" id="scenario-name-${scenario.id} ?>" value="${scenario.nom}" required>
        </div>
    </div>
    <div class="row">
        <div id="sequences" class="s12"></div>
    </div>
`;
    }
}
