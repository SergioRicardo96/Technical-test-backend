<?php extendsLayout('layouts/app'); ?>

<?php startSection('content'); ?>
    <h1 class="mt-4"><?= trans('tasks', 'tasks'); ?></h1>
    <a href="/admin/tasks/create" class="btn btn-light mb-3"><?= trans('tasks', 'create_task'); ?></a>

    <?php if (has_flash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <?= get_flash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php foreach($tasks as $task): ?>
        <div class="card text-center mb-5">
            <div class="card-header">
                <?= $task['catalog_task_status']['name'] ?>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $task['title'] ?></h5>
                <p class="card-text"><?= $task['description'] ?></p>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <a href="/admin/tasks/<?= $task['id'] ?>/edit" class="btn btn-primary"><?= trans('tasks', 'edit'); ?></a>
                    <form method="POST" action="/admin/tasks/<?= $task['id'] ?>" onsubmit="return confirmDelete()">
                        <input type="hidden" name="_method" value="DELETE">
                        @csrf
                        <button type="submit" class="btn btn-danger"><?= trans('tasks', 'delete'); ?></button>
                    </form>
                </div>
            </div>

            <div class="card-footer text-body-secondary">
                <?= $task['updated_at'] ?>
            </div>
        </div>
    <?php endforeach ?>

    <script>
        function confirmDelete() {
            return confirm(<?= json_encode(trans('tasks', 'confirm_delete')); ?>);
        }
    </script>
<?php endSection(); ?>

<?= renderLayout(); ?>