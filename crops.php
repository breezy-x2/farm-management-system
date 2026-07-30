<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle adding a new crop
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field_id = $_POST['field_id'];
    $crop_name = $_POST['crop_name'];
    $planting_date = $_POST['planting_date'];
    $expected_harvest_date = $_POST['expected_harvest_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO crops (field_id, crop_name, planting_date, expected_harvest_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$field_id, $crop_name, $planting_date, $expected_harvest_date, $status]);

    header("Location: crops.php");
    exit;
}


$stmt = $pdo->prepare("SELECT id, name FROM fields WHERE user_id = ?");
$stmt->execute([$user_id]);
$userFields = $stmt->fetchAll();


$stmt = $pdo->prepare("SELECT crops.*, fields.name AS field_name 
                        FROM crops 
                        JOIN fields ON crops.field_id = fields.id 
                        WHERE fields.user_id = ?
                        ORDER BY crops.planting_date DESC");
$stmt->execute([$user_id]);
$crops = $stmt->fetchAll();
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
      <a href="crops.php" class="active">Crops</a>
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
    <h1>Crops</h1>
    <p>Track what's planted, where, and its progress.</p>

    <?php if (count($userFields) === 0): ?>
      <div class="panel">
        <p>You need to <a href="fields.php">add a field</a> before recording crops.</p>
      </div>
    <?php else: ?>

    <div class="panel">
      <h2>Add a crop</h2>
      <form method="POST">
        <label>Crop name</label>
        <input type="text" name="crop_name" required>

        <label>Field</label>
        <select name="field_id" required>
          <?php foreach ($userFields as $f): ?>
            <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?></option>
          <?php endforeach; ?>
        </select>

        <label>Planting date</label>
        <input type="date" name="planting_date">

        <label>Expected harvest date</label>
        <input type="date" name="expected_harvest_date">

        <label>Status</label>
        <select name="status">
          <option value="planted">Planted</option>
          <option value="growing">Growing</option>
          <option value="harvested">Harvested</option>
          <option value="failed">Failed</option>
        </select>

        <button type="submit" class="btn-primary">Add crop</button>
      </form>
    </div>

    <div class="panel">
      <h2>All crops</h2>
      <table>
        <tr>
          <th>Crop</th>
          <th>Field</th>
          <th>Planted</th>
          <th>Expected harvest</th>
          <th>Status</th>
        </tr>
        <?php foreach ($crops as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['crop_name']) ?></td>
          <td><?= htmlspecialchars($c['field_name']) ?></td>
          <td><?= htmlspecialchars($c['planting_date']) ?></td>
          <td><?= htmlspecialchars($c['expected_harvest_date']) ?></td>
          <td><?= htmlspecialchars($c['status']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

    <?php endif; ?>

  </main>

</div>

</body>
</html>