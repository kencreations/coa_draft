<?php
include('conn.php');

if (isset($_SESSION['user_id'])) {
  $last_updated_by = $_SESSION['user_id']; 
} else {
  die('User not logged in');
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>COA TSO Special Services Assignment Tracker</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/coa.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
    .action-buttons {
            display: none;
        }
    .edit-container {
        display: flex;
        gap: 5px;
    }
    .edit-input {
        width: 100%;
        padding: 5px;
        font-size: 14px;
    }
    .button {
        padding: 5px 10px;
        font-size: 12px;
        cursor: pointer;
    }
    .save-btn {
        background-color: #4CAF50;
        color: white;
    }
    .cancel-btn {
        background-color: #f44336;
        color: white;
    }
    </style>
</head>
<body>
    <main>
<?php
        include('./nav.php');
        ?>

<section class="hero-section d-flex justify-content-center align-items-start position-relative">
<div class="row justify-content-around position-absolute w-100">
      <div class="col-md-5">
        <div class="card" style="width: 100%;">
          <div class="card-body">
          <canvas id="3dBarChart" width="300" height="300"></canvas>
          </div>
        </div>
      </div>
  
      <div class="col-md-5">
        <div class="card" style="width: 100%;">
          <div class="card-body">
            status update
          <canvas id="3dBarChart" width="300" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>
    </section>


    <section class=" p-2 table-responsive" style="width: 100%;">
    <?php
// Update task in the database
$updated_by_user_id = $_SESSION['user_id'];
$task_id = 1; // Task ID na i-update
$data_to_update = [
    'project_code_name' => 'New Project Name',
    'remarks_personnel' => 'Updated remarks here',
    'collaboration' => 'Collaboration example',
    'section_head' => 2,
    'start_date' => '2024-11-01 08:00:00',
    'due_date' => '2024-11-15 17:00:00',
    'comments_supervisor' => 'Supervisor comments updated',
    'progress' => 'On Going',
];

// Build the dynamic query
$set_clause = [];
$params = [];
$param_types = '';

foreach ($data_to_update as $column => $value) {
    $set_clause[] = "$column = ?";
    $params[] = $value;
    $param_types .= is_int($value) ? 'i' : (is_string($value) ? 's' : 'd');
}

// Add last_updated_by column
$set_clause[] = "last_updated_by = ?";
$params[] = $updated_by_user_id;
$param_types .= 'i';

// Add updated_at column
$set_clause[] = "updated_at = NOW()";
$set_clause_string = implode(', ', $set_clause);
$params[] = $task_id;
$param_types .= 'i';

// Update query
$sql = "UPDATE user_task_list SET $set_clause_string WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param($param_types, ...$params);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Task updated successfully."; // Store success message in session
} else {
    echo "Error updating task: " . $stmt->error;
}

$stmt->close();

// Fetch activities and task details
$activities = [];
$result = $conn->query("SELECT name FROM activities");

while ($row = $result->fetch_assoc()) {
    $activities[] = $row['name']; 
}

if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT * FROM user_task_list WHERE user_id = ?
";





$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='table bg-light text-center table-hover table-sm rounded-table'>
        <thead class='bg-dark text-light'>
            <tr style='font-size: 10px;'>
                <th>Project Code Name</th>
                <th>Activity</th>
                <th>Progress</th>
                <th>Remarks (Personnel)</th>
                <th>Collaboration</th>
                <th>Section Head</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Day Left</th>
                <th>Comments (Supervisor)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";
        while ($row = $result->fetch_assoc()) {
          $start_date = date("F j, Y", strtotime($row['start_date']));
          $due_date = date("F j, Y", strtotime($row['due_date']));
          $day_left = max(0, ceil((strtotime($row['due_date']) - time()) / (60 * 60 * 24)));
      
          $last_updated_by = $row['last_updated_by'] ? $row['last_updated_by'] : 'No updates yet';
          ?>
          <tr>
              <td class="position-relative" style="font-size: 10px;">
                  <?php echo htmlspecialchars($row['project_code_name']); ?>
                  <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                      <?php echo htmlspecialchars($last_updated_by); ?>
                  </span>
              </td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['activity_name']); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['progress']); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['remarks_personnel']); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['collaborators']); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['section_heads_name']); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($start_date); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($due_date); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($day_left); ?></td>
              <td style="font-size: 10px;"><?php echo htmlspecialchars($row['comments_supervisor']); ?></td>
              <td>
                  <a href="update_task.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-dark btn-sm">
                      <i class="fa fa-pencil"></i>
                  </a>
              </td>
          </tr>
          <script>
              console.log(<?php echo json_encode($row['collaborators']); ?>);
          </script>
          <?php
      }
      

echo "</tbody></table>";

$stmt->close();

?>
'<script>

  console.log("$row['collaborators']")
</script>'
</section>

<?php
        include('./footer.php');
        ?>

</main>

<?php
include ('./modals.php');
?>

        <!-- JAVASCRIPT FILES -->
     <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/clock.js"></script>
    <script src="js/table.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/task_list.js"></script>
    <script>
   // Data fetched from the PHP code
const activities = <?php echo json_encode($activities); ?>;

// Function to get unique colors for the bars
const getUniqueColor = () => {
  const colors = [];
  activities.forEach(() => {
    // Generate a random color for each activity
    colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`);
  });
  return colors;
};

// Initialize the chart
const ctx = document.getElementById('3dBarChart').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: activities,  // X-axis labels (activity names)
    datasets: [{
      label: 'Activity Progress',
      data: Array(activities.length).fill(100), // Default to 100% for each activity (adjust as needed)
      backgroundColor: getUniqueColor(),  // Unique colors for each bar
      borderColor: getUniqueColor(),
      borderWidth: 2,
      barThickness: 50,
      hoverOffset: 10
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(value) { return value.toFixed(2) + '%'; },
          stepSize: 5
        }
      },
      x: {
        ticks: {
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      }
    },
    plugins: {
      legend: {
        position: 'top',
        labels: {
          font: { size: 14 }
        }
      },
      tooltip: {
        callbacks: {
          label: function(tooltipItem) {
            return tooltipItem.raw.toFixed(2) + '%';  // Show percentage in tooltip
          }
        },
        titleFont: { size: 16 },
        bodyFont: { size: 14 }
      }
    },
    elements: {
      bar: {
        borderRadius: 5,
        shadowOffsetX: 3,
        shadowOffsetY: 3,
        shadowBlur: 10,
        shadowColor: 'rgba(0, 0, 0, 0.3)'
      }
    }
  }
});
</script>




</body>
</html>