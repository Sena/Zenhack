
Total: <?=count($post_unread);?>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th><strong>Título do post</strong></th>
            <th><strong>Votos</strong></th>
            <th><strong>Comentários</strong></th>
            <th><strong>Atualização</strong></th>
        </tr>
        </thead>
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