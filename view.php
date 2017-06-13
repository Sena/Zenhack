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
Total: <?=count($post_unread);?>
<table class="post-list">
    <tr>
        <td><strong>Título do post</strong></td>
        <td><strong>Comentários</strong></td>
    </tr>
    <?php foreach ($post_unread as $row):?>
        <tr title="<?=strip_tags($row->details);?>" alt="<?=strip_tags($row->details);?>">
            <td>
                <a href="<?=$row->html_url;?>" target="_blank">
                    <?=$row->title;?>
                </a>
            </td>
            <td>
                <?=$row->comment_count;?>
            </td>
        </tr>
    <?php endforeach;?>
</table>

</body>
</html>