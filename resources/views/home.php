<?php extendsLayout('layouts/app'); ?>

<?php startSection('content'); ?>
    <h1><?= trans('home', 'welcome'); ?></h1>
<?php endSection(); ?>

<?= renderLayout(); ?>
