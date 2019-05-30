export class SequenceTemplate {
    static render(sequence) {
        if (!sequence || sequence.error) {
            sequence.id = 0;
            sequence.nom = '';
        }
        sequence.id = sequence.id || 0;
        sequence.nom = sequence.nom || '';

        return `
    <div class="row">
        <div class="col s8">
            <label for="sequence-name-${sequence.id}" class="active">Nom</label>
            <input type="text" name="nom" id="sequence-name-${sequence.id}" value="${sequence.nom}" required>
        </div>
    </div>
    <div class="row">
        <div id="actions" class="s12"></div>
    </div>
`;
    }
}
