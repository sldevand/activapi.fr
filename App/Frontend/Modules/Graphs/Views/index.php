<?php echo $jst->toVars(); ?>
<div class="row">
    <div class="col s12">
        <?php
        $graphCard->setContent($buttons);
        echo $graphCard->getHtml();
        ?>
    </div>
</div>
<script src="<?= DIST.'/graphs.js' ?>"></script>
<script>
    var graphs = new GraphsManage(graphId);

    if (sensorid == '') {
        var i = 0;
        sensorids.forEach(function (radioid) {

            var color = graphs.generateColor(i);

            graphs.setParams(apiURL, radioid, dateMin, dateMax, tempMin, tempMax, color, color);
            graphs.getDatasFromAPI(graphs.getFullURL());

            i++;
        });

    } else {
        var color = graphs.generateColor(0);
        graphs.setParams(apiURL, sensorid, dateMin, dateMax, tempMin, tempMax, color, color);
        graphs.getDatasFromAPI(graphs.getFullURL());
    }
</script>
