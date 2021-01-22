export class SwitchButtons {
    trickSwitches() {
        $("input[class*='switch-button']").each(function (index, element) {
            $(element).on('change', function (e) {
                var trickButton = $('#trick-' + e.target.id);
                if (e.target.checked) {
                    e.target.setAttribute('value', 'yes');
                    trickButton.removeAttr('name');
                } else {
                    e.target.setAttribute('value', 'no');
                    trickButton.attr('name', element.name);
                    trickButton.attr('value', 'no');
                }
            })
        });
    }
}
