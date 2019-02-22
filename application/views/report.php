<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/main.css?v=0.1">
    <script src="assets/js/Chart.min.js"></script>
    <script>
    </script>
    <title>Zenhack - list</title>
</head>
<body>
<h1>Interações realizadas</h1>
<p>Tempo de resposta no mês atual: <?= $thismonth->news_diff; ?> horas</p>
<div class="charts">
    <canvas id="todayXyesterday" width="500" height="200"></canvas>
    <canvas id="thisWeekXlastWeek" width="500" height="200"></canvas>
    <canvas id="thisMonthXlastMonth" width="500" height="200"></canvas>
</div>
<script>
    new Chart(document.getElementById("todayXyesterday"), {
        "type": "bar",
        "data": {
            "datasets": [{
                "label": "Hoje <?= $today_diff; ?>% <?= $today_diff_status == 'up' ? '△' : '▽'; ?>",
                "data": [<?=$today->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(12, 0, 202, 0.2)"],
                "borderWidth": 1
            }, {
                "label": "Ontem <?= $yesterday_diff; ?>% <?= $yesterday_diff_status == 'up' ? '△' : '▽'; ?>",
                "data": [<?=$yesterday->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(123, 185, 2, 0.2)"],
                "borderWidth": 1
            }]
        },
        "options": {
            responsive: false,
            "scales": {
                "yAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            }
        }
    });
    new Chart(document.getElementById("thisWeekXlastWeek"), {
        "type": "bar",
        "data": {
            "datasets": [{
                "label": "Essa semana <?=$thisweek_diff;?>% <?=$thisweek_diff_status == 'up' ? '△' : '▽';?>",
                "data": [<?=$thisweek->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(12, 0, 202, 0.2)"],
                "borderWidth": 1
            }, {
                "label": "Semana passada <?=$lastweek_diff;?>% <?=$lastweek_diff_status == 'up' ? '△' : '▽';?>",
                "data": [<?=$lastweek->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(123, 185, 2, 0.2)"],
                "borderWidth": 1
            }]
        },
        "options": {
            responsive: false,
            "scales": {
                "yAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            }
        }
    });
    new Chart(document.getElementById("thisMonthXlastMonth"), {
        "type": "bar",
        "data": {
            "datasets": [{
                "label": "Esse mês <?=$thismonth_diff;?>% <?=$thismonth_diff_status == 'up' ? '△' : '▽';?>",
                "data": [<?=$thismonth->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(12, 0, 202, 0.2)"],
                "borderWidth": 1
            }, {
                "label": "Mês passado <?=$lastmonth_diff;?>% <?=$lastmonth_diff_status == 'up' ? '△' : '▽';?>",
                "data": [<?=$lastmonth->count;?>],
                "fill": false,
                "backgroundColor": ["rgba(123, 185, 2, 0.2)"],
                "borderWidth": 1
            }]
        },
        "options": {
            responsive: false,
            "scales": {
                "yAxes": [{
                    "ticks": {
                        "beginAtZero": true
                    }
                }]
            }
        }
    });
</script>
</body>
</html>