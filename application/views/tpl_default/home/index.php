<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../../assets/bootstrap/css/bootstrap.min.css">
    <script src="../../../../assets/jquery/jquery-3.3.1.slim.min.js"></script>
    <script src="../../../../assets/bootstrap/js/bootstrap.min.js"></script>
    <base href="<?=base_url();?>">
    <title>Zenhack - list</title>
</head>
<body>
<div class="container">
    <H1>Hoje temos: <?=count($post_unread);?> interações pendentes.</H1>
    <a href="./proximo">Iniciar</a>
</div>
</body>
</html>