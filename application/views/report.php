<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/main.css">
    <title>Zenhack - list</title>
</head>
<body>
<h1>Interações realizadas</h1>
    <p class="diff_<?=$today_diff_status;?>">
        <strong>Hoje, até agora</strong>:<br>
        <?=$today;?> - <?=$today_diff;?>% <?=$today_diff_status == 'up' ? '△': '▽';?>
    </p>
    <p>
        <strong>Ontem</strong>:<br>
        <?=$yesterday;?>
    </p>
    <p class="diff_<?=$thisweek_diff_status;?>">
        <strong>Esta semana, até agora</strong>:<br>
        <?=$thisweek;?> - <?=$thisweek_diff;?>% <?=$thisweek_diff_status == 'up' ? '△': '▽';?>
    </p>
    <p>
        <strong>Semana passada</strong>:<br>
        <?=$lastweek;?>
    </p>
    <p>
        <strong>Últimos 7 dias</strong>:<br>
        <?=$days7;?>
    </p>
    <p>
        <strong>Este mês, até agora</strong>:<br>
        <?=$month;?>
    </p>
    <p>
        <strong>Últimos 30 dias</strong>:<br>
        <?=$days30;?>
    </p>
</body>
</html>