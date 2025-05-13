<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Support & Event Planner</title>
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      background-color: #f0f0f0;
      color: #111;
      padding-bottom: 60px;
    }

    header {
      background-color: #111;
      color: #fff;
      padding: 1em 0;
      text-align: center;
    }

    nav ul {
      list-style: none;
      padding: 0;
      margin: 0;
      background-color: #222;
      text-align: center;
    }

    nav ul li {
      display: inline;
    }

    nav ul li a {
      text-decoration: none;
      color: #fff;
      display: inline-block;
      padding: 15px 20px;
      transition: background 0.3s;
    }

    nav ul li a:hover {
      background-color: #333;
    }

    .container {
      padding: 30px;
      max-width: 800px;
      margin: auto;
    }

    .section {
      background-color: #fff;
      margin-bottom: 20px;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .section h2 {
      color: #111;
      border-bottom: 2px solid #ccc;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .clickable-list {
      list-style-type: none;
      padding: 0;
    }

    .clickable-list li {
      margin-bottom: 15px;
    }

    .clickable-list li a {
      text-decoration: none;
      color: #111;
      background-color: #eee;
      padding: 12px 20px;
      display: block;
      border: 1px solid #ccc;
      border-radius: 6px;
      transition: background-color 0.2s, transform 0.1s;
    }

    .clickable-list li a:hover {
      background-color: #ddd;
      transform: translateY(-2px);
    }

    footer {
      text-align: center;
      padding: 15px;
      background-color: #111;
      color: #fff;
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>

  <header>
    <h1>Welcome to the Dashboard (Client)</h1>
  </header>

  <nav>
    <ul>
      <li><a href="#submit-concern">Submit Your Concern</a></li>
    </ul>
  </nav>

  <div class="container">
    <div id="submit-concern" class="section">
      <h2>Submit Your Concern</h2>
      <p>Select the relevant category to report your concern:</p>
      <ul class="clickable-list">
        <li><a href="client/submit_ticket.php">Submit a Ticket Concern</a></li>
        <li><a href="client/submit_seat_plan_concern.php">Submit a Seat Plan Concern</a></li>
        <li><a href="client/submit_table_concern.php">Submit a Table Plan Concern</a></li>
        <li><a href="client/submit_event_concern.php">Submit an Event Concern</a></li>
        <li><a href="client/chat/chat.php">Chat with an Agent</a></li>
      </ul>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Your Company Name. All rights reserved. Current Date: May 10, 2025</p>
  </footer>

</body>
</html>
