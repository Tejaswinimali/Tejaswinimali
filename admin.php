<?php require('assets/includes/db_connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - View Messages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Contact Messages</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>SR</th>
          <th>Name</th>
          <th>Email</th>
          <th>Message</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            $i = 1;
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['message']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
                $i++;
            }
        } else {
            echo "<tr><td colspan='5'>No messages found.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>
