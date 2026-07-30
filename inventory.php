<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle adding a new inventory item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];

    $stmt = $pdo->prepare("INSERT INTO inventory (user_id, item_name, category, quantity, unit) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $item_name, $category, $quantity, $unit]);

    header("Location: inventory.php");
    exit;
}

// Get all inventory items for this user
$stmt = $pdo->prepare("SELECT * FROM inventory WHERE user_id = ? ORDER BY category, item_name");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll();
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
      <a href="tasks.php">Schedule</a>
      <a href="inventory.php" class="active">Inventory</a>
      <a href="expenses.php">Finances</a>
    </nav>
    <div class="sidebar-footer">
      <p><?= $_SESSION['full_name'] ?></p>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="content">
    <h1>Inventory</h1>
    <p>Track seeds, fertilizer, equipment, and feed stock.</p>

    <div class="panel">
      <h2>Add an item</h2>
      <form method="POST">
        <label>Item name</label>
        <input type="text" name="item_name" required>

        <label>Category</label>
        <select name="category">
          <option value="seed">Seed</option>
          <option value="fertilizer">Fertilizer</option>
          <option value="equipment">Equipment</option>
          <option value="feed">Feed</option>
          <option value="other">Other</option>
        </select>

        <label>Quantity</label>
        <input type="number" step="0.01" name="quantity">

        <label>Unit</label>
        <input type="text" name="unit" placeholder="e.g. kg, bags, litres">

        <button type="submit" class="btn-primary">Add item</button>
      </form>
    </div>

    <div class="panel">
      <h2>All inventory</h2>
      <table>
        <tr>
          <th>Item</th>
          <th>Category</th>
          <th>Quantity</th>
        </tr>
        <?php foreach ($items as $i): ?>
        <tr>
          <td><?= htmlspecialchars($i['item_name']) ?></td>
          <td><?= htmlspecialchars($i['category']) ?></td>
          <td><?= htmlspecialchars($i['quantity']) ?> <?= htmlspecialchars($i['unit']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </main>

</div>

</body>
</html>