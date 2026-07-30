<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle adding a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field_id = $_POST['field_id'] !== '' ? $_POST['field_id'] : null;
    $task_type = $_POST['task_type'];
    $description = $_POST['description'];
    $scheduled_date = $_POST['scheduled_date'];

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, field_id, task_type, description, scheduled_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $field_id, $task_type, $description, $scheduled_date]);

    header("Location: tasks.php");
    exit;
}

// Get this user's fields, for the dropdown
$stmt = $pdo->prepare("SELECT id, name FROM fields WHERE user_id = ?");
$stmt->execute([$user_id]);
$userFields = $stmt->fetchAll();

// Get all tasks for this user
$stmt = $pdo->prepare("SELECT tasks.*, fields.name AS field_name 
                        FROM tasks 
                        LEFT JOIN fields ON tasks.field_id = fields.id 
                        WHERE tasks.user_id = ?
                        ORDER BY tasks.scheduled_date ASC");
$stmt->execute([$user_id]);
$tasks = $stmt->fetchAll();
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
      <a href="dashboard.php">Dashboard</a>
      <a href="fields.php">Fields</a>
      <a href="crops.php">Crops</a>
      <a href="tasks.php" class="active">Schedule</a>
      <a href="inventory.php">Inventory</a>
      <a href="expenses.php">Finances</a>
    </nav>
    <div class="sidebar-footer">
      <p><?= $_SESSION['full_name'] ?></p>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="content">
    <h1>Schedule</h1>
    <p>Plan farm activities — irrigation, spraying, harvesting, and more.</p>

    <div class="panel">
      <h2>Schedule a task</h2>
      <form method="POST">
        <label>Task type</label>
        <input type="text" name="task_type" placeholder="e.g. Irrigation, Spraying, Harvest" required>

        <label>Field (optional)</label>
        <select name="field_id">
          <option value="">— None —</option>
          <?php foreach ($userFields as $f): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Date</label>
        <input type="date" name="scheduled_date" required>

        <label>Description</label>
        <textarea name="description" rows="2"></textarea>

        <button type="submit" class="btn-primary">Schedule task</button>
      </form>
    </div>

    <div class="panel">
      <h2>All scheduled tasks</h2>
      <table>
        <tr>
          <th>Date</th>
          <th>Task</th>
          <th>Field</th>
          <th>Status</th>
        </tr>
        <?php foreach ($tasks as $t): ?>
        <tr>
          <td><?= htmlspecialchars($t['scheduled_date']) ?></td>
          <td><?= htmlspecialchars($t['task_type']) ?></td>
          <td><?= htmlspecialchars($t['field_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </main>

</div>

</body>
</html>