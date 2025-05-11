<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Seat Plan Concern - Customer Support</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #f4f4f4; color: #333; padding-bottom: 60px; }
        header { background-color: #17a2b8; color: #fff; padding: 1em 0; text-align: center; }
        .container { padding: 20px; max-width: 800px; margin: auto; }
        .breadcrumb { margin-bottom: 20px; }
        .breadcrumb a { text-decoration: none; color: #007bff; }
        .form-area { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-area h2 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group button { background-color: #17a2b8; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .form-group button:hover { background-color: #117a8b; }
        footer { text-align: center; padding: 20px; background-color: #333; color: #fff; position: fixed; bottom: 0; width: 100%; }
    </style>
</head>
<body>

    <header>
    <h1>Submit a Seat Plan Concern</h1>
  </header>

  <div class="container">
    <div class="breadcrumb">
      <a href="../index.php">Back to Submit Concern Options</a> &gt; Seat Plan Concern
    </div>

    <div class="form-area">
      <h2>Seat Plan Concern Form</h2>
      <p>Please provide details about your seat plan concern (e.g., wrong seat, view obstruction, accessibility needs).</p>
      <form action="remote/seat.php" method="POST">
        <div class="form-group">
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="eventName">Event Name:</label>
          <input type="text" id="eventName" name="event_name" required>
        </div>
        <div class="form-group">
          <label for="ticketId">Ticket ID / Order Number:</label>
          <input type="text" id="ticketId" name="ticket_id" required>
        </div>
        <div class="form-group">
          <label for="currentSeat">Current Seat Number(s) (if applicable):</label>
          <input type="text" id="currentSeat" name="current_seat">
        </div>
        <div class="form-group">
          <label for="description">Describe Your Seat Plan Concern:</label>
          <textarea id="description" name="description" placeholder="e.g., My assigned seat has an obstructed view, I require an accessible seat, I was assigned a different seat than purchased." required></textarea>
        </div>
        <div class="form-group">
          <button type="submit" name="submit">Submit Concern</button>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Your Company Name. All rights reserved. Current Date: May 10, 2025</p>
  </footer>

</body>
</html>