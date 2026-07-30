
<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission (adding a new field)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $size_acres = $_POST['size_acres'];
    $soil_type = $_POST['soil_type'];
    $location = $_POST['location'];

    $stmt = $pdo->prepare("INSERT INTO fields (user_id, name, size_acres, soil_type, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $name, $size_acres, $soil_type, $location]);

    header("Location: fields.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM fields WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$fields = $stmt->fetchAll();
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
      <a href="fields.php" class="active">Fields</a>
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
    <h1>Fields</h1>
    <p>Manage the plots of land on your farm.</p>

    <div class="panel">
      <h2>Add a field</h2>
      <form method="POST">
        <label>Field name</label>
        <input type="text" name="name" required>

        <label>Size (acres)</label>
        <input type="number" step="0.01" name="size_acres">

        <label>Soil type</label>
        <input type="text" name="soil_type" placeholder="e.g. Loamy, Clay, Sandy">

        <label>Location</label>
        <input type="text" name="location" placeholder="e.g. North block">

        <button type="submit" class="btn-primary">Add field</button>
      </form>
    </div>

      
      <div class="panel">
      <h2>All fields</h2>
      <table>
        <tr>
          <th>Name</th>
          <th>Size (acres)</th>
          <th>Soil type</th>
          <th>Location</th>
        </tr>
        <?php foreach ($fields as $f): ?>
        <tr>
          <td><?= htmlspecialchars($f['name']) ?></td>
          <td><?= htmlspecialchars($f['size_acres']) ?></td>
          <td><?= htmlspecialchars($f['soil_type']) ?></td>
          <td><?= htmlspecialchars($f['location']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
  

  </main>

</div>

</body>
</html>