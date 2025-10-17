<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Include header template -->
  <?php include('app/Views/templates/header.php'); ?>

  <!-- Main dashboard container -->
  <div class="d-flex justify-content-center align-items-start mt-5">
    <div class="card shadow p-4 border border-dark" style="max-width: 850px; width: 100%; background-color: #e9ecef;">
      <div class="card-body">

        <!-- Welcome message -->
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

          <!-- ADMIN OVERVIEW -->
          <h5 class="mb-3 text-dark">Admin Overview</h5>
          <p class="text-dark mb-2">
            Total users:
            <strong><?= isset($data['usersCount']) ? (int) $data['usersCount'] : 0 ?></strong>
          </p>

          <hr>

          <!-- USER MANAGEMENT FORM -->
          <h5 class="mb-3 text-dark">User Management</h5>
          <form action="<?= base_url('admin/registerUser') ?>" method="post" class="mb-4 border p-3 rounded bg-white">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name" required>
              </div>
              <div class="col-md-4">
                <label for="email" class="form-label">Email or Student ID</label>
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

          <!-- POST ANNOUNCEMENT -->
          <hr>
          <h5 class="mb-3 text-dark">Post Announcement</h5>
          <form action="<?= base_url('admin/postAnnouncement') ?>" method="post" class="mb-4 border p-3 rounded bg-white">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" name="title" id="title" class="form-control" placeholder="Enter announcement title" required>
            </div>
            <div class="mb-3">
              <label for="content" class="form-label">Content</label>
              <textarea name="content" id="content" rows="3" class="form-control" placeholder="Write announcement details..." required></textarea>
            </div>
            <div class="mb-3">
              <label for="audience" class="form-label">Audience</label>
              <select name="audience" id="audience" class="form-select" required>
                <option value="">Select Audience</option>
                <option value="teacher">Teachers</option>
                <option value="student">Students</option>
              </select>
            </div>
            <button type="submit" class="btn btn-primary">Post Announcement</button>
          </form>

          <!-- RECENT USERS TABLE -->
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

          <!-- TEACHER DASHBOARD -->

          <!-- DYNAMIC ANNOUNCEMENTS -->
          <?php if (!empty($announcements)): ?>
            <h5 class="mb-3 text-dark">Announcements from Admin</h5>
            <ul class="list-group mb-4">
              <?php foreach ($announcements as $a): ?>
                <?php if ($a['audience'] !== 'teacher') continue; ?>
                <li class="list-group-item mb-2">
                  <h6 class="fw-bold"><?= esc($a['title']) ?></h6>
                  <p><?= esc($a['content']) ?></p>
                  <small class="text-muted">Posted on: <?= date('Y-m-d', strtotime($a['created_at'])) ?></small>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <h5 class="mb-3 text-dark">My Students</h5>
          <?php if (!empty($data['students'])): ?>
            <ul class="list-group mb-4">
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

          <!-- EXISTING HARD-CODED TEACHER ANNOUNCEMENTS BELOW -->
          <h5 class="mb-3 text-dark">Faculty Announcements</h5>
          <ul class="list-group mb-4">
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">Faculty Meeting Reminder</h6>
              <p>All teachers are required to attend the faculty meeting on October 18 at 3 PM in the main conference room.</p>
              <small class="text-muted">Posted on: 2025-10-16</small>
            </li>
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">Grade Submission Notice</h6>
              <p>Please submit your students’ midterm grades before October 25 through the faculty portal.</p>
              <small class="text-muted">Posted on: 2025-10-15</small>
            </li>
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">Teaching Evaluation Schedule</h6>
              <p>Teacher evaluation forms will be available next week. Please remind your students to complete them.</p>
              <small class="text-muted">Posted on: 2025-10-14</small>
            </li>
          </ul>

        <?php else: ?>

          <!-- STUDENT DASHBOARD -->

          <!-- DYNAMIC ANNOUNCEMENTS -->
          <?php if (!empty($announcements)): ?>
            <h5 class="mb-3 text-dark">Announcements from Admin</h5>
            <ul class="list-group mb-4">
              <?php foreach ($announcements as $a): ?>
                <?php if ($a['audience'] !== 'student') continue; ?>
                <li class="list-group-item mb-2">
                  <h6 class="fw-bold"><?= esc($a['title']) ?></h6>
                  <p><?= esc($a['content']) ?></p>
                  <small class="text-muted">Posted on: <?= date('Y-m-d', strtotime($a['created_at'])) ?></small>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <!-- EXISTING HARD-CODED STUDENT ANNOUNCEMENTS BELOW -->

          
          <h5 class="mb-3 text-dark">Latest Announcements</h5>
          <ul class="list-group mb-4">
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">Welcome to the Student Portal</h6>
              <p>This is your official student dashboard. Check here regularly for updates.</p>
              <small class="text-muted">Posted on: 2025-10-17</small>
            </li>

            
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">Midterm Examination Schedule</h6>
              <p>Midterm exams will start on October 20. Please check your email for detailed schedules.</p>
              <small class="text-muted">Posted on: 2025-10-15</small>
            </li>
            <li class="list-group-item mb-2">
              <h6 class="fw-bold">System Maintenance</h6>
              <p>The portal will be temporarily unavailable from 10 PM to 12 AM for maintenance.</p>
              <small class="text-muted">Posted on: 2025-10-12</small>
            </li>
          </ul>

          <!-- STUDENT PROFILE -->
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

  <!-- jQuery and Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
