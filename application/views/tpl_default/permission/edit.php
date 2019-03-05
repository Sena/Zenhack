<div class="container">
    <h1>Permissões para o usuário:
        <small><?= $user->name; ?></small>
    </h1>
    <form role="form" method="post" enctype="multipart/form-data"
          action="./<?= $this->uri->segment(1); ?>/<?= $this->uri->segment(2); ?>/salvar/<?=$user->id;?>">
        <div class="row">
            <?php foreach ($permission as $row): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="form-group">
                        <input type="checkbox" name="permission<?= $row->id; ?>" id="permission_<?= $row->id; ?>"
                               value="<?= $row->id; ?>" <?= isset($user->permission[$row->id]) ? 'checked' : null; ?>>
                        <label for="permission_<?= $row->id; ?>"><?= $row->label; ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Salvar">
        </div>
    </form>
</div>
