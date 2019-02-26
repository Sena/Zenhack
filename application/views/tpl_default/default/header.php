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
<?php echo isset($assets) ? $assets : NULL; ?>
<?php echo isset($css) ? $css : NULL; ?>
<?php echo isset($js) ? $js : NULL; ?>
</head>
<body>
<div class="container">
    <?php if($error):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong> <?= $error; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;?>
    <nav class="nav nav-pills flex-column flex-sm-row">
        <a class="flex-sm-fill text-sm-center nav-link home_index" href="#">Pendentes</a>
        <a class="flex-sm-fill text-sm-center nav-link home_listing disabled" href="./lista">Lista</a>
        <a class="flex-sm-fill text-sm-center nav-link report" href="./relatorio">Relatório</a>
    </nav>