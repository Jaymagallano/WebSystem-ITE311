<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Dashboard</span>
            <div>
                <span class="text-white me-3">Welcome, <?= $user['name'] ?>!</span>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header"><h4>User Information</h4></div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= $user['name'] ?></p>
                <p><strong>Email:</strong> <?= $user['email'] ?></p>
                <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
            </div>
        </div>
    </div>
</body>
</html>
