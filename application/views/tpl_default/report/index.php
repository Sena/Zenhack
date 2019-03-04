<h1>Interações realizadas</h1>
<p>Tempo de resposta no mês atual: <?= $thismonth->news_diff; ?> horas</p>
<div class="row">
    <div class="col-sm">
        <canvas id="todayXyesterday"></canvas>
    </div>
    <div class="col-sm">
        <canvas id="thisWeekXlastWeek"></canvas>
    </div>
    <div class="col-sm">
        <canvas id="thisMonthXlastMonth"></canvas>
    </div>
</div>
<h2>SLA</h2>
<div class="row">
    <div class="col-sm">
        <h3>Crítico <small>(<?= $setting['slabad']->value; ?> horas)</small></h3>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                 style="width:<?= $slascore->slabad; ?>%"></div>
        </div>
        <h3>Regular <small>(<?= $setting['slareegular']->value; ?> horas)</small></h3>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                 style="width:<?= $slascore->slareegular; ?>%"></div>
        </div>
        <h3>Meta <small>(<?= $setting['slagoal']->value; ?> horas)</small></h3>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                 style="width:<?= $slascore->slagoal; ?>%"></div>
        </div>
    </div>
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