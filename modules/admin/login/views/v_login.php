<?= $this->extend('\Modules\Shared\Layouts\Views\v_login_layout') ?>

<?= $this->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header text-center">
        <a href="#" class="h2"><b>PresensiApp</b><br>Admin Panel</a>
    </div>
    <div class="card-body">
        <form action="#" method="post">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Username">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input type="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember">
                        <label for="remember">
                            Remember Me
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <p class="mb-1">
            <a href="#">I forgot my password</a>
        </p>
    </div>
    <!-- /.card-body -->
</div>
<?= $this->endSection() ?>