function GraphsManage(id) {

    var graphsManage = {
        m_id: id,
        m_url: 'http://',
        m_sensorid: 'sensor24',
        m_nom: 'Piece',
        m_datemin: null,
        m_datemax: null,
        m_borderColor: 'rgba(255, 99, 132, 1)',
        m_backgroundColor: 'rgba(255, 99, 132, 0.4)',
        m_minY: 10,
        m_maxY: 22,
        m_datasets: [],
        m_labels: [],
        m_colors: [],
        m_tempGraph: null,
        m_pending_gets: 0,
        m_palette: palette('cb-Paired', 8),

        getDatasFromAPI: function (fullURL) {
            graphsManage.m_pending_gets++;
            $.get(fullURL, function (sensor) {
                var sensorJSON = JSON.parse(sensor);
                var label = sensorJSON.nom;
                var datas = sensorJSON.data;

                graphsManage.m_nom = sensorJSON.nom;
                graphsManage.m_sensorid = sensorJSON.sensor_id;
                moment.locale('fr');

                var dataPointsTemp = [];
                var dataPointsHygro = [];
                var dataPointsEtat = [];

                datas.forEach(function (data) {
                    if (data.temperature != null && data.temperature != "") {
                        var dataPointTemp = {
                            x: data.horodatage,
                            y: data.temperature
                        }

                        dataPointsTemp.push(dataPointTemp);
                    }

                    if (data.hygrometrie != null && data.hygrometrie != "") {

                        var dataPointHygro = {
                            x: data.horodatage,
                            y: data.hygrometrie
                        }

                        dataPointsHygro.push(dataPointHygro);
                    }

                    if (data.etat != null && data.etat != "") {

                        var dataPointEtat = {
                            x: data.horodatage,
                            y: data.etat
                        }

                        dataPointsEtat.push(dataPointEtat);
                    }


                });

                var measuretype = 'temp';
                var graphType = 'line';
                var axis = 'tempAxis';

                if (dataPointsTemp.length > 0) {
                    axis = 'tempAxis';
                    measuretype = 'temp';
                    graphsManage.addDataset(axis, graphType, dataPointsTemp, measuretype);
                }

                if (dataPointsHygro.length > 0) {

                    if (graphsManage.m_sensorid.includes('dht11')) {
                        measuretype = 'hygro';
                        axis = 'hygroAxis'
                    }

                    if (graphsManage.m_sensorid.includes('therm')) {
                        measuretype = 'hygro';
                        axis = 'hygroAxis'
                    }

                    graphsManage.addDataset(axis, graphType, dataPointsHygro, measuretype);

                }

                if (dataPointsEtat.length > 0) {

                    measuretype = "etat"

                    graphsManage.addDataset(axis, graphType, dataPointsEtat, measuretype);

                }

                graphsManage.m_pending_gets--;
                if (graphsManage.m_pending_gets == 0) {

                    graphsManage.drawEverything();

                }
            });
        },

        drawEverything: function () {
            require(['Web/js/utils/Chart-2.7.1.min'], function (Chart) {
                graphsManage.m_tempGraph = new TempGraph(graphsManage.m_id, graphsManage.m_datasets, graphsManage.minY, graphsManage.maxY, graphsManage.m_labels);
            });
        },

        setParams: function (url, sensorid, datemin, datemax, minY = 0, maxY = 30, borderColor = 'rgba(255, 99, 132, 1)', backgroundColor = 'rgba(255, 99, 132, 0.4)') {

            graphsManage.m_url = url;
            graphsManage.m_sensorid = sensorid;
            graphsManage.m_datemin = datemin;
            graphsManage.m_datemax = datemax;
            graphsManage.m_minY = minY;
            graphsManage.m_maxY = maxY;
            graphsManage.m_borderColor = borderColor;
            graphsManage.m_backgroundColor = backgroundColor;

            graphsManage.m_colors.push({
                'sensorid': graphsManage.m_sensorid,
                'borderColor': graphsManage.m_borderColor,
                'backgroundColor': graphsManage.m_backgroundColor
            });
        },

        getFullURL: function () {
            return graphsManage.m_url + graphsManage.m_sensorid + "-" + graphsManage.m_datemin + "-" + graphsManage.m_datemax;
        },

        getThermostatURL: function () {
            return graphsManage.m_url + graphsManage.m_datemin + "-" + graphsManage.m_datemax;
        },

        addDataset: function (axisId, type, datas, measuretype) {
            var dataset = graphsManage.createBaseDataset();

            switch (type) {
                case 'bar':
                    datas.forEach(function (value) {
                        graphsManage.m_labels.push(value.x);
                        dataset.data.push(value.y);
                    });
                    break;
                case 'line':
                    dataset.data = datas;
                    break;
            }

            dataset.label = graphsManage.m_nom + ' ' + measuretype;
            dataset.yAxisID = axisId;

            dataset.type = type;
            graphsManage.m_colors.forEach(function (colors) {
                if (colors.sensorid == graphsManage.m_sensorid) {
                    dataset.borderColor = colors.borderColor;
                    dataset.backgroundColor = colors.backgroundColor;

                    return;
                }
            });

            graphsManage.m_datasets.push(dataset);
        },

        createBaseDataset: function () {
            return {
                data: [],
                fill: false,
                pointRadius: 0,
                pointHitRadius: 5,
                lineTension: 0,
                borderWidth: 4,
                borderCapStyle: 'round',
                borderJoinStyle: 'round'
            };
        },

        generateColor: function (id) {
            return "#" + this.m_palette[id];
        }
    };

    return graphsManage;
}
