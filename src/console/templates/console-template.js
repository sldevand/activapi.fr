export class ConsoleTemplate {
    static render(logs) {
        let template = '';
        let date = new Date();
        for (let log of logs) {
            date.setTime(parseInt(log.createdAt) * 1000);
            template += `<span>${date.toLocaleTimeString("fr-FR", {timeZone: "Europe/Paris"})} ${log.content}</span> <br>`;
        }
        return template;
    }
}
