<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Nome</th>
            <th>E-mail</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($list as $row) :?>
            <tr>
                <td><?php echo $row->name; ?></td>
                <td><?php echo $row->email; ?></td>
                <td class="action">
                    <a href="./<?php echo $this->uri->segment(1); ?>/permissao/<?php echo $row->id; ?>">

                        <i class="fa fa-lock" title="Permissão"></i>
                    </a>
                    <a href="./<?php echo $this->uri->segment(1); ?>/editar/<?php echo $row->id; ?>">

                        <i class="fa fa-pencil-alt" title="Editar"></i>
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