<?= $this->extend('Templates_admin') ?>
<?= $this->section('content') ?>

<div class="container pt-4 pb-5 bg-light">
    <h2 class="border-bottom border-2 border-primary">
        <?= ucfirst($form) . ' ' . $title ?>
    </h2>

    <form action="<?= base_url('enderecos/' . $op); ?>" method="post">
        <div class="mb-3">
            <label for="enderecos_rua" class="form-label">Rua</label>
            <input type="text" class="form-control" name="enderecos_rua" id="enderecos_rua"
                value="<?= $enderecos->enderecos_rua ?? '' ?>">
        </div>

        <div class="mb-3">
            <label for="enderecos_numero" class="form-label">Número</label>
            <input type="text" class="form-control" name="enderecos_numero" id="enderecos_numero"
                value="<?= $enderecos->enderecos_numero ?? '' ?>">
        </div>

        <div class="mb-3">
            <label for="enderecos_complemento" class="form-label">Complemento</label>
            <input type="text" class="form-control" name="enderecos_complemento" id="enderecos_complemento"
                value="<?= $enderecos->enderecos_complemento ?? '' ?>">
        </div>

        <div class="mb-3">
            <label for="enderecos_status" class="form-label">Status</label>
            <select name="enderecos_status" id="enderecos_status" class="form-select">
                <option value="1" <?= isset($enderecos) && $enderecos->enderecos_status == 1 ? 'selected' : '' ?>>Ativo
                </option>
                <option value="0" <?= isset($enderecos) && $enderecos->enderecos_status == 0 ? 'selected' : '' ?>>
                    Inativo</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="enderecos_cidade_id" class="form-label">Cidade (ID)</label>
            <input type="text" class="form-control" name="enderecos_cidade_id" id="enderecos_cidade_id"
                value="<?= $enderecos->enderecos_cidade_id ?? '' ?>">
        </div>

        <div class="mb-3">
            <label for="enderecos_usuario_id" class="form-label">Usuário (ID)</label>
            <input type="text" class="form-control" name="enderecos_usuario_id" id="enderecos_usuario_id"
                value="<?= $enderecos->enderecos_usuario_id ?? '' ?>">
        </div>

        <input type="hidden" name="enderecos_id" value="<?= $enderecos->enderecos_id ?? '' ?>">

        <div class="mb-3">
            <button class="btn btn-success" type="submit"> <?= ucfirst($form) ?> <i class="bi bi-floppy"></i></button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>