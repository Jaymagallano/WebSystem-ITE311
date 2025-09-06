<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header text-center"><h4>Register</h4></div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('error')): ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                        <?php endif; ?>
                        
                        <?= form_open('register') ?>
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" value="<?= set_value('name') ?>">
                                <?= form_error('name', '<small class="text-danger">', '</small>') ?>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= set_value('email') ?>">
                                <?= form_error('email', '<small class="text-danger">', '</small>') ?>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password">
                                <?= form_error('password', '<small class="text-danger">', '</small>') ?>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirm">
                                <?= form_error('password_confirm', '<small class="text-danger">', '</small>') ?>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Register</button>
                            <div class="text-center mt-3">
                                <p>Already have an account? <a href="<?= base_url('login') ?>">Login</a></p>
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
