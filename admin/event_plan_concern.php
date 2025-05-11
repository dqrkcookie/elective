<?php
require_once ('../config/conn.php');

$table_name = 'event_planner';
$file_name = 'event_plan_concern.php';
$query = $pdo->query("SELECT * FROM $table_name");
$concerns = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Planner - Seat and Guest Management</title>
  <style>
    body { font-family: sans-serif; margin: 0; background-color:rgb(147, 92, 250) ; color: #333; }
        header { background-color: #6f42c1; color: #fff; padding: 2em 0; text-align: center; }
        nav ul { list-style-type: none; padding: 0; text-align: center; background-color: #5a379e; }
        nav ul li { display: inline; margin-right: 20px; }
        nav ul li a { text-decoration: none; color: #fff; padding: 15px 20px; display: inline-block; }
        nav ul li a:hover { background-color: #4a2d82; }
        .container { padding: 20px; }
        .breadcrumb { margin-bottom: 20px; }
        .breadcrumb a { text-decoration: none; color: #007bff; }
        .tool-section { background-color: #fff; margin-bottom: 20px; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .tool-section h2 { color: #6f42c1; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        footer { text-align: center; padding: 20px; background-color: #333; color: #fff; margin-top: 30px; }
    
    
    
    
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
      color: #444; 
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
      color: #333;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
      margin-top: 0;
    }

    form {
      margin-top: 20px;
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    input[type="text"], input[type="date"], input[type="number"] {
      padding: 10px;
      width: 95%;
      background-color: #f0f0f0;
      color: #333;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    button {
      background-color: #444;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #222;
    }

    .seating-layout {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 30px;
    }
    .seat, .table { 
      background-color: #555; 
      color: #fff; 
      padding: 15px; 
      text-align: center;
      border-radius: 5px; 
    }
    .seat { width: 40px; height: 40px; }
    .table { width: 90px; height: 100px; }

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
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    tr:hover {
      background-color: #f1f1f1;
      transition: background-color 0.2s;
    }

    footer { 
      text-align: center; 
      padding: 20px; 
      background-color: #333; 
      color: #ccc; 
      margin-top: 30px; 
    }
  </style>
</head>
<body>

<header>
  <h1>Event Planner - Seat and Guest Management</h1>
</header>

<div class="container">
  <div class="breadcrumb">
    <a href="index.php">Dashboard</a> &gt; Event Planner
  </div>

  <div class="content-area">
    <h2>Create Event</h2>
    <form method="POST" action="remote/create_event.php">
      <label for="eventName">Event Name:</label>
      <input type="text" id="eventName" name="event_name" required>

      <label for="eventDate">Event Date:</label>
      <input type="date" id="eventDate" name="event_date" required>

      <label for="guestPerformers">Guest Performers:</label>
      <input type="text" id="guestPerformers" name="guest_performers" placeholder="Enter guest performers" required>

      <button type="submit" name="submit">Create Event</button>
    </form>

    <h2>Venue Layout & Seating</h2>
    <p>20 seats and 5 tables for your event.</p>

    <div id="upcoming-events" class="section">
        <h2>Upcoming Events</h2>
        <p>Stay updated with the latest events scheduled in the system:</p>
        <ul class="clickable-list">
            <?php if(!empty($concerns)) { ?>
                <?php foreach ($concerns as $event) { ?>
                    <li>
                        <form method="GET" action="remote/resolve.php" style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <p>
                                    Event Name: <?php echo htmlspecialchars($event->event_name); ?><br>
                                    Event Date: <?php echo htmlspecialchars($event->event_date); ?><br>
                                    Event Guest: <?php echo htmlspecialchars($event->event_performers); ?>
                                </p>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($event->id); ?>">
                                <input type="hidden" name="table_name" value="<?php echo htmlspecialchars($table_name); ?>">
                                <input type="hidden" name="file_name" value="<?php echo htmlspecialchars($file_name); ?>">
                            </div>
                            <div style="display: flex; align-items: center;">
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to remove this event?');"
                                        style="background-color: #e74c3c; color: white; border: none; padding: 10px 16px; border-radius: 4px; cursor: pointer; margin-left: 20px;">
                                    Remove
                                </button>
                            </div>
                        </form>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <li><p>No upcoming events.</p></li>
            <?php } ?>
        </ul>
    </div>

    <hr>

    <div class="seating-layout">
      <div class="seat">S1</div>
      <div class="seat">S2</div>
      <div class="seat">S3</div>
      <div class="seat">S4</div>
      <div class="seat">S5</div>
      <div class="seat">S6</div>
      <div class="seat">S7</div>
      <div class="seat">S8</div>
      <div class="seat">S9</div>
      <div class="seat">S10</div>
      <div class="seat">S11</div>
      <div class="seat">S12</div>
      <div class="seat">S13</div>
      <div class="seat">S14</div>
      <div class="seat">S15</div>
      <div class="seat">S16</div>
      <div class="seat">S17</div>
      <div class="seat">S18</div>
      <div class="seat">S19</div>
      <div class="seat">S20</div>

      <div class="table">T1</div>
      <div class="table">T2</div>
      <div class="table">T3</div>
      <div class="table">T4</div>
      <div class="table">T5</div>
    </div>
  </div>
</div>

<footer>
  <p>&copy; 2025 Your Company Name. All rights reserved.</p>
</footer>

</body>
</html>
