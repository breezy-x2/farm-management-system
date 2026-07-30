<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Count fields
$stmt = $pdo->prepare("SELECT COUNT(*) FROM fields WHERE user_id = ?");
$stmt->execute([$user_id]);
$fieldCount = $stmt->fetchColumn();

// Count active crops
$stmt = $pdo->prepare("SELECT COUNT(*) FROM crops 
                        JOIN fields ON crops.field_id = fields.id 
                        WHERE fields.user_id = ? AND crops.status IN ('planted','growing')");
$stmt->execute([$user_id]);
$cropCount = $stmt->fetchColumn();

// Count pending tasks
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$taskCount = $stmt->fetchColumn();

// Get upcoming tasks (next 5)
$stmt = $pdo->prepare("SELECT tasks.*, fields.name AS field_name 
                        FROM tasks 
                        LEFT JOIN fields ON tasks.field_id = fields.id 
                        WHERE tasks.user_id = ? AND tasks.status != 'completed'
                        ORDER BY tasks.scheduled_date ASC LIMIT 5");
$stmt->execute([$user_id]);
$upcoming = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="dash.css">
</head>
<body>

<div class="layout">

  <aside class="sidebar">
    <div class="brand">Harvest Hub</div>

    <nav class="nav">
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="fields.php">Fields</a>
      <a href="crops.php">Crops</a>
      <a href="tasks.php">Schedule</a>
      <a href="inventory.php">Inventory</a>
      <a href="expenses.php">Finances</a>
    </nav>

    <div class="sidebar-footer">
      <p><?= $_SESSION['full_name'] ?></p>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="content">
    <h1>Welcome, <?= $_SESSION['full_name'] ?></h1>
    <p>Here's what's happening on your farm today.</p>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="label">Fields</div>
        <div class="value"><?= $fieldCount ?></div>
      </div>
      <div class="stat-card">
        <div class="label">Active crops</div>
        <div class="value"><?= $cropCount ?></div>
      </div>
      <div class="stat-card">
        <div class="label">Pending tasks</div>
        <div class="value"><?= $taskCount ?></div>
      </div>
    </div>

    <div class="panel">
      <h2>Upcoming schedule</h2>
      <table>
        <tr>
          <th>Date</th>
          <th>Task</th>
          <th>Field</th>
        </tr>
        <?php foreach ($upcoming as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['scheduled_date']) ?></td>
          <td><?= htmlspecialchars($t['task_type']) ?></td>
          <td><?= htmlspecialchars($t['field_name'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </main>

</div>

</body>
</html>