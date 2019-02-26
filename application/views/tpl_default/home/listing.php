
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