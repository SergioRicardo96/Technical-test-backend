<?php extendsLayout('layouts/app'); ?>

<?php startSection('content'); ?>
    <form method="POST" action="/login">
        @csrf
        <h1 class="h3 mb-4 fw-normal"><?= trans('auth', 'please_sign_in') ?></h1>
        <?php if (has_flash('error')): ?>
            <div class="alert alert-danger"> <?= get_flash('error') ?> </div>
        <?php endif; ?>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="usernameInput" name="username" placeholder="<?= trans('auth', 'username') ?>" value="<?= $data['username'] ?? ''; ?>">
            <label class="text-dark" for="usernameInput"><?= trans('auth', 'username') ?></label>
            <?php if (isset($errors['username'])): ?>
                <div class="text-danger mt-2 mb-3"><?= $errors['username'][0]; ?></div>
            <?php endif; ?>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="<?= trans('auth', 'password') ?>">
            <label class="text-dark" for="floatingPassword"><?= trans('auth', 'password') ?></label>
            <?php if (isset($errors['password'])): ?>
                <div class="text-danger mt-2 mb-3"><?= $errors['password'][0] ?></div>
            <?php endif; ?>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-4" type="submit"><?= trans('auth', 'sign_in') ?></button>
    </form>
<?php endSection(); ?>

<?= renderLayout(); ?>