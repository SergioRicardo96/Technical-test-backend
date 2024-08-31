<?php extendsLayout('layouts/app'); ?>

<?php startSection('content'); ?></h1>

    <form method="POST" action="/admin/tasks/<?= $task['id'] ?>">
        <input type="hidden" name="_method" value="PUT">
        @csrf
        <h1 class="h3 mb-4 fw-normal"><?= trans('tasks', 'edit_task') ?></h1>

        <div class="form-floating mb-3">
            <select class="form-select" id="floatingSelectTaskStatus" aria-label="Floating label select example" name="catalog_task_status_id">
                <option value="" disabled selected><?= trans('tasks', 'open_menu_select') ?></option>
                <?php foreach ($taskStatuses as $taskStatus): ?>
                    <option value="<?= $taskStatus['id'] ?>" <?= ($data['catalog_task_status_id'] ?? $task['catalog_task_status_id'] ?? '' == $taskStatus['id']) ? 'selected' : '' ?>>
                        <?= $taskStatus['name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
            <label for="floatingSelectTaskStatus"><?= trans('tasks', 'task_status') ?></label>
            <?php if (isset($errors['catalog_task_status_id'])): ?>
                <div class="text-danger mt-2 mb-3"><?= $errors['catalog_task_status_id'][0]; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="titleInput" name="title" placeholder="<?= trans('tasks', 'title') ?>" value="<?= $data['title'] ?? $task['title']; ?>">
            <label class="text-dark" for="titleInput"><?= trans('tasks', 'title') ?></label>
            <?php if (isset($errors['title'])): ?>
                <div class="text-danger mt-2 mb-3"><?= $errors['title'][0]; ?></div>
            <?php endif; ?>
        </div>
        <div class="form-floating">
            <textarea type="text" class="form-control" id="floatingDescription" name="description" placeholder="<?= trans('tasks', 'description') ?>" style="height: 100px"><?= $data['description'] ?? $task['description']; ?></textarea>
            <label class="text-dark" for="floatingDescription"><?= trans('tasks', 'description') ?></label>
            <?php if (isset($errors['description'])): ?>
                <div class="text-danger mt-2 mb-3"><?= $errors['description'][0]; ?></div>
            <?php endif; ?>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-4" type="submit"><?= trans('tasks', 'edit_task') ?></button>
    </form>

<?php endSection(); ?>

<?= renderLayout(); ?>