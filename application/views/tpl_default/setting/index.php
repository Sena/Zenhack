<div class="container">
    <h1>Configurações</h1>
    <form role="form" method="post" enctype="multipart/form-data" action="./<?php echo $this->uri->segment(1); ?>/salvar">
        <?php foreach ($setting as $row): ?>
            <div class="form-group">
                <label for="<?= $row->key; ?>"><?= $row->label; ?> <?= $row->required ? '(Obrigatório)' : null; ?>: </label>
                <input class="form-control" type="text" name="<?= $row->key; ?>" id="<?= $row->key; ?>" value="<?= $row->value; ?>" <?= $row->required ? 'required="required"' : null; ?> />
            </div>
        <?php endforeach; ?>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Salvar">
        </div>
    </form>
</div>
