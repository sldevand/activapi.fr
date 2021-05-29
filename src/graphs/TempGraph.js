import Chart from 'chart.js/dist/Chart';

export function TempGraph(id, p_datasets, minY = 0, maxY = 30, p_labels = []) {
    var ctx = document.getElementById(id).getContext('2d');

    return new Chart(ctx, {
        type: 'line',
        data: {
            datasets: p_datasets,
            labels: p_labels
        },
        options: {
            responsive: true,
            tooltips: {
                mode: 'point',
                intersect: false
            },
            hover: {
                mode: 'point',
                intersect: false
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    distribution: 'linear',
                    ticks: {
                        source: 'auto'
                    },
                    bounds: 'ticks',
                    time: {
                        round: 'true',
                        unit: 'hour'
                    }
                }],
                yAxes: [{
                    id: 'tempAxis',
                    type: 'linear',
                    position: 'left'
                }, {
                    id: 'hygroAxis',
                    type: 'linear',
                    position: 'right'
                }]
            }
        }
    });
}
