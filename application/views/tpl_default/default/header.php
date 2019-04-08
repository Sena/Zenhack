<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?></title>
    <base href="<?php echo base_url(); ?>">
    <meta name="controller" content="<?php echo $this->router->class ?>"/>
    <meta name="method" content="<?php echo $this->router->method ?>"/>
    <script>var permission = <?= json_encode((isset($me->permission) ? $me->permission : '')); ?></script>
<?php echo isset($assets) ? $assets : NULL; ?>
<?php echo isset($css) ? $css : NULL; ?>
<?php echo isset($js) ? $js : NULL; ?>
</head>
<body>
<div class="container">
    <?php if($msg):?>
        <div class="alert alert-info alert-dismissible fade show" role="contentinfo">
            <strong>Mensagem:</strong> <?= $msg; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>
    <?php if($error):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro:</strong> <?= $error; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>
    <?php if(isset($me->id)):?>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link home_index" href="./">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link scratch_next disabled" href="./proximo" target="_blank">Próximo</a>
        </li>
        <li class="nav-item">
            <a class="nav-link scratch_index disabled" href="./pendente">Pendentes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link scape_index scape_view disabled" href="./postagem">Respondido</a>
        </li>
        <li class="nav-item">
            <a class="nav-link scratch_listing disabled" href="./lista">Lista</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle user" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" href="./usuario">Usuários</a>
            <div class="dropdown-menu">
                <a class="dropdown-item user_index disabled" href="usuario">Lista</a>
                <a class="dropdown-item user_edit disabled" href="usuario/novo">Novo</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link report_index disabled" href="./relatorio">Relatório</a>
        </li>
        <li class="nav-item">
            <a class="nav-link setting_index disabled" href="./configuracao">Configurações</a>
        </li>
    </ul>
    <?php endif;?>