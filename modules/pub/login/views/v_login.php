<?= $renderer->extend('modules/shared/layouts/views/v_login_layout') ?>

<?= $renderer->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="#" class="h2"><b>PresensiApp</b><br>Admin Panel</a>
    </div>
    <div class="card-body">
        <form action="/login" method="post">
            <?= csrf_field() ?>
            <div class="input-group mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <?php if (!empty(session()->getFlashdata('error'))): ?>
        <div class="alert alert-danger" role="alert">
            <?=session()->getFlashdata('error')?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $renderer->endSection() ?>