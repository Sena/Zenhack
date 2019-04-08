<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Data</th>
            <th>Analista</th>
            <th>Título</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $row) :?>
            <tr>
                <td><?php echo $row->date; ?></td>
                <td><?php echo isset($user[$row->user_id]->name) ? $user[$row->user_id]->name : null; ?></td>
                <td title="<?php echo strip_tags($row->details); ?>



<?php echo strip_tags($row->last_comment); ?>"><?php echo $row->title; ?></td>
                <td class="action">
                    <a href="<?php echo $row->html_url ?>" target="_blank">
                        <i class="fa fa-eye" title="Permissão"></i>
                    </a>
                    <a  href="./<?php echo $this->uri->segment(1); ?>/excluir/<?php echo $row->id; ?>">
                        <i class="fa fa-trash-alt" title="Excluir"></i>
                    </a>
                </td>
            </tr>
        <?php endForeach;?>
        </tbody>
    </table>
</div>