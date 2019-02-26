<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../../assets/css/main.css">
    <title>Zenhack - list</title>
</head>
<body>
<div class="container">
    Total: <?=count($post_unread);?>
    <table class="post-list">
        <tr>
            <td><strong>Título do post</strong></td>
            <td><strong>Votos</strong></td>
            <td><strong>Comentários</strong></td>
            <td align="right"><strong>Atualização</strong></td>
        </tr>
        <?php foreach ($post_unread as $row):?>
            <tr title="<?=strip_tags($row->details);?>" alt="<?=strip_tags($row->details);?>">
                <td>
                    <a href="<?=$row->html_url;?>" target="_blank">
                        <?=$row->title;?>
                    </a>
                </td>
                <td><?=$row->vote_sum;?></td>
                <td><?=$row->comment_count;?></td>
                <td align="right"><?=$row->br_updated_at;?></td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
</body>
</html>