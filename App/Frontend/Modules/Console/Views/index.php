<div class="row">
    <div class="col s12">
        <div id="console-card" class="card">
            <div class="card-title textOnPrimaryColor primaryLightColor valign-wrapper">
                Console
                <i id="ioconnection" class="valign material-icons z-depth-1 circle red lighten-3 red-text right">fiber_manual_record</i>
            </div>

            <div class="card-content">
                <div class="row">
                    <div class="col s12">
                        <label for="console-edit-text">Commande</label>
                        <input id="console-edit-text" name="console-edit-text" type="text">
                    </div>
                    <div class="col s12">
                        <?php echo $sendButton->getHtml(); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col s12">
                        <label for="console-display">Affichage</label>
                        <div id="console-display">
                            <?php echo $log; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="<?= DIST . '/socketio.js' ?>"></script>
