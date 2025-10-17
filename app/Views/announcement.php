<?php if (!empty($announcements)): ?>
  <ul class="list-group mb-4">
<?php foreach ($announcements as $a): ?>
    <li class="list-group-item mb-2">
        <h6 class="fw-bold"><?= esc($a['title']) ?></h6>
        <p><?= esc($a['content']) ?></p>
        <small class="text-muted">Posted on: <?= date('Y-m-d', strtotime($a['created_at'])) ?></small>
    </li>
<?php endforeach; ?>
</ul>

<?php else: ?>
  <p class="text-dark">No announcements to display.</p>
<?php endif; ?>


