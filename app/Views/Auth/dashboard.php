<!DOCTYPE html> 
<!-- test commit -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--  Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!--  Include header template -->
  <?php include('app/Views/templates/header.php'); ?>

  <!--  Main dashboard container -->
  <div class="d-flex justify-content-center align-items-start mt-5">
    <div class="card shadow p-4 border border-dark" style="max-width: 800px; width: 100%; background-color: #e9ecef;">
      <div class="card-body">

        <!--  Welcome message -->
        <h3 class="card-title mb-4 text-dark">
          Welcome <?= esc($role ?? (session()->get('role') ?? 'User')) ?>!
        </h3>

        <p class="text-dark">
          Hello, <?= session()->get('name') ?? 'User' ?>! Welcome to your dashboard.
          Here you can get an overview of the platform, manage your tasks efficiently,
          and explore features to help you track progress.
        </p>

        <hr>

        <?php if (($role ?? '') === 'admin'): ?>

          <!-- FLASH MESSAGES -->
          <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
          <?php elseif(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>

          <!-- ADMIN SECTION -->
          <h5 class="mb-3 text-dark">Admin Overview</h5>
          <p class="text-dark mb-2">
            Total users:
            <strong><?= isset($data['usersCount']) ? (int) $data['usersCount'] : 0 ?></strong>
          </p>

          <!-- USER MANAGEMENT SECTION -->
          <hr>
          <h5 class="mb-3 text-dark">User Management</h5>

          <!-- Register New User Form -->
          <form action="<?= base_url('admin/registerUser') ?>" method="post" class="mb-4 border p-3 rounded bg-white">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" required>
              </div>
              <div class="col-md-4">
                <label for="email" class="form-label">Email or Student ID</label>
                <!--  changed type from email → text -->
                <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email or Student ID">
              </div>
              <div class="col-md-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
              </div>
              <div class="col-md-4">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select" required>
                  <option value="">Select Role</option>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Register User</button>
          </form>

          <!-- Table of recent users -->
          <h5 class="mb-3 text-dark">Recent Users</h5>
          <?php if (!empty($data['recentUsers'])): ?>
            <div class="table-responsive">
              <table class="table table-sm table-striped table-bordered">
                <thead>
                  <tr>
                    <th>ID</th><th>Name</th><th>Email/Student ID</th><th>Role</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($data['recentUsers'] as $u): ?>
                    <tr>
                      <td><?= (int)$u['id'] ?></td>
                      <td><?= esc($u['name']) ?></td>
                      <td><?= esc($u['email']) ?></td>
                      <td><?= esc($u['role']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p class="text-dark">No recent users found.</p>
          <?php endif; ?>


        <?php elseif (($role ?? '') === 'teacher'): ?>

          <h5 class="mb-3 text-dark">My Students</h5>
          <?php if (!empty($data['students'])): ?>
            <ul class="list-group">
              <?php foreach ($data['students'] as $s): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><?= esc($s['name']) ?> (<?= esc($s['email']) ?>)</span>
                  <span class="badge bg-primary">Student</span>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-dark">No students to display.</p>
          <?php endif; ?>


        <?php else: ?>

          <h5 class="mb-3 text-dark">My Profile</h5>
          <?php if (!empty($data['profile'])): ?>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="p-3 border rounded bg-white">Name: <strong><?= esc($data['profile']['name']) ?></strong></div>
              </div>
              <div class="col-md-6">
                <div class="p-3 border rounded bg-white">Email: <strong><?= esc($data['profile']['email']) ?></strong></div>
              </div>
              <div class="col-md-6">
                <div class="p-3 border rounded bg-white">Role: <strong><?= esc($role ?? 'student') ?></strong></div>
              </div>
            </div>
          <?php else: ?>
            <p class="text-dark">Profile not available.</p>
          <?php endif; ?>

        <?php endif; ?>
      </div>
    </div>
  </div>

  <!--  jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
