<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $entry_date = $_POST['entry_date'];

    $stmt = $pdo->prepare("INSERT INTO expenses (user_id, type, category, description, amount, entry_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $type, $category, $description, $amount, $entry_date]);

    header("Location: expenses.php");
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM expenses WHERE user_id = ? ORDER BY entry_date DESC");
$stmt->execute([$user_id]);
$entries = $stmt->fetchAll();


$totalIncome = 0;
$totalExpense = 0;
foreach ($entries as $e) {
    if ($e['type'] === 'income') {
        $totalIncome += $e['amount'];
    } else {
        $totalExpense += $e['amount'];
    }
}
$balance = $totalIncome - $totalExpense;
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
      <a href="inventory.php">Inventory</a>
      <a href="expenses.php" class="active">Finances</a>
    </nav>
    <div class="sidebar-footer">
      <p><?= $_SESSION['full_name'] ?></p>
      <a href="logout.php">Log out</a>
    </div>
  </aside>

  <main class="content">
    <h1>Finances</h1>
    <p>Track farm income and expenses.</p>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="label">Total income</div>
        <div class="value">R<?= number_format($totalIncome, 2) ?></div>
      </div>
      <div class="stat-card">
        <div class="label">Total expenses</div>
        <div class="value">R<?= number_format($totalExpense, 2) ?></div>
      </div>
      <div class="stat-card">
        <div class="label">Balance</div>
        <div class="value">R<?= number_format($balance, 2) ?></div>
      </div>
    </div>

    <div class="panel">
      <h2>Add an entry</h2>
      <form method="POST">
        <label>Type</label>
        <select name="type">
          <option value="expense">Expense</option>
          <option value="income">Income</option>
        </select>

        <label>Category</label>
        <input type="text" name="category" placeholder="e.g. Seeds, Sales, Labor">

        <label>Amount (R)</label>
        <input type="number" step="0.01" name="amount" required>

        <label>Date</label>
        <input type="date" name="entry_date" required>

        <label>Description</label>
        <input type="text" name="description">

        <button type="submit" class="btn-primary">Add entry</button>
      </form>
    </div>

    <div class="panel">
      <h2>All entries</h2>
      <table>
        <tr>
          <th>Date</th>
          <th>Type</th>
          <th>Category</th>
          <th>Amount</th>
        </tr>
        <?php foreach ($entries as $e): ?>
        <tr>
          <td><?= htmlspecialchars($e['entry_date']) ?></td>
          <td><?= htmlspecialchars($e['type']) ?></td>
          <td><?= htmlspecialchars($e['category']) ?></td>
          <td>R<?= number_format($e['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </main>

</div>

</body>
</html>