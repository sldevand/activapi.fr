<?php foreach ($cards as $card): ?>
    <div class="row">
        <div class="col s12">
            <?php echo $card->getHtml(); ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
    $(document).ready(function () {
        $('select').material_select();

        let timePicker = $(".timepicker");

        timePicker.pickatime({
            default: 'now', // Set default time: 'now', '1:30AM', '16:30'
            fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
            twelvehour: false, // Use AM/PM or 24-hour format
            donetext: 'OK', // text for done-button
            cleartext: 'Effacer', // text for clear-button
            canceltext: 'Annuler', // Text for cancel-button
            autoclose: false, // automatic close timepicker
            ampmclickable: false, // make AM PM clickable
            aftershow: function () {
            } //Function for after opening timepicker
        });

        timePicker.on('mousedown',function(event){
            event.preventDefault();
        })
    });
</script>
