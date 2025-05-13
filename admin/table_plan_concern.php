<?php

require_once('../config/conn.php');

$table_name = 'table_concern'; 
$file_name = 'table_plan_concern.php';
$query = $pdo->query("SELECT * FROM $table_name");
$concerns = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Table Plan Concern - Customer Support</title>
  <style>
    body { 
      font-family: 'Segoe UI', Arial, sans-serif; 
      margin: 0; 
      background-color: #f1f1f1; 
      color: #333; 
    }
    header { 
      background-color: #222; 
      color: #fff; 
      padding: 1.5em 0; 
      text-align: center; 
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    .container { 
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px; 
    }
    .breadcrumb { 
      margin-bottom: 20px; 
      padding: 10px;
      background-color: #fff;
      border-radius: 4px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .breadcrumb a { 
      text-decoration: none; 
      color: #555; 
      transition: color 0.3s;
    }
    .breadcrumb a:hover {
      color: #000;
    }
    .content-area { 
      background-color: #fff; 
      padding: 25px; 
      border-radius: 6px; 
      box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
    }
    h1 {
      margin: 0;
      font-weight: 400;
    }
    h2 {
      color: #222;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
      margin-top: 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #333;
      color: #fff;
      font-weight: 500;
    }
    tr:nth-child(even) {
      background-color: #f8f8f8;
    }
    tr:hover {
      background-color: #eee;
      transition: background-color 0.2s;
    }
    
    button {
      background-color: #444;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #222;
    }

    footer { 
      text-align: center; 
      padding: 20px; 
      background-color: #222; 
      color: #ccc; 
      margin-top: 30px; 
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
      .container {
        padding: 10px;
      }
      .content-area {
        padding: 15px;
      }
      table {
        font-size: 14px;
      }
      th, td {
        padding: 8px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1>Table Plan Concern Management</h1>
</header>

<div class="container">
  <div class="breadcrumb">
    <a href="index.php">Dashboard</a> &gt; Table Plan Concern
  </div>

  <div class="content-area">
    <h2>Manage Table Plan Concerns</h2>
    <p>Review and resolve customer support issues specifically related to event table plans.</p>

    <?php if (!empty($concerns)) { ?>
      <table>
        <thead>
          <tr>
            <th>Full Name</th>
            <th>Email</th>
            <th>Event</th>
            <th>Ticket ID</th>
            <th>Table #</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($concerns as $concern) { ?>
            <tr>
              <td><?php echo htmlspecialchars($concern->full_name); ?></td>
              <td><?php echo htmlspecialchars($concern->email_address); ?></td>
              <td><?php echo htmlspecialchars($concern->event_name); ?></td>
              <td><?php echo htmlspecialchars($concern->ticket_id); ?></td>
              <td><?php echo htmlspecialchars($concern->table_number); ?></td>
              <td><?php echo nl2br(htmlspecialchars($concern->description)); ?></td>
              <td><?php echo htmlspecialchars($concern->status); ?></td>
              <td>
                <form action="remote/resolve.php" method="GET">
                  <input type="hidden" name="id" value="<?php echo $concern->id; ?>">
                  <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
                  <input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
                  <input type="hidden" name="email" value="<?php echo htmlspecialchars($concern->email_address); ?>">
                  <button type="submit" name="resolve">Resolved</button>
                  <button type="submit" name="assist">Assist</button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>No table plan concerns found.</p>
    <?php } ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 Your Company Name. All rights reserved.</p>
</footer>

</body>
</html>