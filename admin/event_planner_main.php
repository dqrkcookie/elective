<?php

require_once ('../config/conn.php');

$table_name = 'event_concern';
$file_name = 'event_plan_concern.php';
$query = $pdo->query("SELECT * FROM $table_name");
$concerns = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Plan Concern - Customer Support</title>
  <style>
        body { font-family: sans-serif; margin: 0; background-color:rgb(197, 152, 19); color: #333; padding-bottom: 60px; }
        header { background-color: #ffc107; color: #333; padding: 1em 0; text-align: center; }
        .container { padding: 20px; max-width: 800px; margin: auto; }
        .breadcrumb { margin-bottom: 20px; }
        .breadcrumb a { text-decoration: none; color: #007bff; }
        .form-area { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-area h2 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea,
        .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group button { background-color: #ffc107; color: #333; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .form-group button:hover { background-color: #e0a800; }
        footer { text-align: center; padding: 20px; background-color: #333; color: #fff; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>

<header>
  <h1>Event Plan Concern Management</h1>
</header>

<div class="container">
  <div class="breadcrumb">
    <a href="index.php">Dashboard</a> &gt; Event Plan Concern
  </div>

  <div class="content-area">
    <h2>Manage Event Plan Concerns</h2>
    <p>Review and resolve customer support inquiries regarding the overall event plan, schedules, or logistics.</p>

    <?php if (!empty($concerns)) { ?>
      <table>
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>Issue</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($concerns as $concern) { ?>
            <tr>
              <td><?php echo htmlspecialchars($concern->full_name); ?></td>
              <td><?php echo htmlspecialchars($concern->email_address); ?></td>
              <td><?php echo htmlspecialchars($concern->event_name); ?></td>
              <td><?php echo htmlspecialchars($concern->issue); ?></td>
              <td><?php echo nl2br(htmlspecialchars($concern->description)); ?></td>
              <td>
                <form action="remote/resolve.php" method="GET">
                  <input type="hidden" name="id" value="<?php echo $concern->id; ?>">
                  <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
                  <input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
                  <button type="submit" name="resolve">Resolve</button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>No event plan concerns found.</p>
    <?php } ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 Your Company Name. All rights reserved.</p>
</footer>

</body>
</html>