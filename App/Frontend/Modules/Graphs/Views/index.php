<?php echo $jst->toVars(); ?>
<div class="row">
    <div class="col s12">
        <?php
        echo $graphCard;
        ?>
    </div>
</div>

<script src="<?= JS_UTILS . '/palette.js' ?>"></script>
<script src="<?= JS . '/graphsManage/TempGraph.js' ?>"></script>
<script src="<?= JS . '/graphsManage/graphsManage.js' ?>"></script>
<?php echo $jst->toVars(); ?>

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
