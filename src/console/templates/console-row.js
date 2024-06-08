export class ConsoleRow {
    constructor(log) {
        this.log = log;
    }

    render() {
        let date = new Date();
        date.setTime(parseInt(this.log.createdAt) * 1000);
        let row = document.createElement('div');
        row.innerHTML = `${date.toLocaleTimeString("fr-FR", { timeZone: "Europe/Paris" })} ${this.log.content}`;
        return row;
    }
}
