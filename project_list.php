<?php
include ('conn.php');

if (isset($_SESSION['login_success']) && $_SESSION['login_success'] == true) {
  $showToastr = true;
  unset($_SESSION['login_success']);
} else {
  $showToastr = false;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>COA TSO Special Services Assignment Tracker</title>
    <!-- CSS FILES -->
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
<?php 
include ('loader.html');
?>
    <main>

        <?php
        include('./nav.php');
        ?>

        <section class="hero-section d-flex justify-content-center align-items-center" id="section_1">
        </section>



        <section class=" p-2 table-responsive" style="width: 100%;">
        <?php

// Assuming you've already established a database connection

$sql = "SELECT 
            p.contractor_project, 
            u.name AS action_officer_name,  
            c.initials AS collaboration_with, 
            s.subject_name AS subjects, 
            p.document_no, 
            p.date_received, 
            p.date_assigned, 
            p.agency, 
            sup.name AS supervisor_name
        FROM 
            project_list p
        JOIN 
            users u ON p.user_id = u.id  -- Join with user table to get the action officer name
        JOIN 
            user_task_collaboration c ON p.collaboration_id = c.id
        JOIN 
            subjects s ON p.subject_id = s.id
        JOIN 
            supervisors sup ON p.supervisor_id = sup.id";

$sql2 = "SELECT
            p.*, 
            u.name AS action_officer, 
            GROUP_CONCAT(u2.name) AS collaborators,
            u3.name AS supervisor_name  -- Join with users table to get supervisor's name
        FROM
            project_list p
        JOIN 
            users u ON p.user_id = u.id  -- Action officer join
        JOIN 
            user_task_list utl ON p.contractor_project = utl.project_code_name  -- Link project and tasks
        LEFT JOIN
            users u2 ON FIND_IN_SET(u2.id, utl.collaboration) > 0  -- Get collaborators
        LEFT JOIN 
            users u3 ON utl.supervisor = u3.id  -- Join users table again for supervisor's name
        GROUP BY
            p.contractor_project";


$result = $conn->query($sql2);
?>
<!-- HTML Table Structure -->
<table class="table">
    <thead>
        <tr>
            <th style="font-size: 10px;" scope="col">CONTRACTOR / PROJECT</th>
            <th style="font-size: 10px;" scope="col">ACTION OFFICER</th>
            <th style="font-size: 10px;" scope="col">Collaboration With (initials only)</th>
            <th style="font-size: 10px;" scope="col">SUBJECT</th>
            <th style="font-size: 10px;" scope="col">DOCUMENT NO.</th>
            <th style="font-size: 10px;" scope="col">DATE RECEIVED</th>
            <th style="font-size: 10px;" scope="col">DATE ASSIGNED</th>
            <th style="font-size: 10px;" scope="col">AGENCY</th>
            <th style="font-size: 10px;" scope="col">SUPERVISOR/ TEAM LEADER</th>
            <th style="font-size: 10px;" scope="col">ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are results
        if ($result->num_rows > 0) {
            // Loop through the results and display them in the table
            while ($project = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['contractor_project']); ?></td>
                    <td>
                        <select style="font-size: 10px;" class="form-select">
                            <option value="">Select Action Officer</option>
                            <?php
                            $result_users = $conn->query("SELECT id, name FROM users");
        
                            if ($result_users->num_rows > 0) {
                                while ($row = $result_users->fetch_assoc()) {
                                    $selected = ($row['id'] == $project['user_id']) ? "selected" : "";
                                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No users available</option>";
                            }
                            ?>
                        </select>
                    </td>


                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['collaborators']); ?></td>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['subjects']); ?></td>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['document_no']); ?></td>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['date_received']); ?></td>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['date_assigned']); ?></td>
                    <td ondblclick="makeEditable(this)" style="font-size: 10px;"><?php echo htmlspecialchars($project['agency']); ?></td>
                    <td>
                        <select style="font-size: 10px;" class="form-select">
                            <option value="">Select Supervisor</option>
                            <?php
                            $result_supervisors = $conn->query("SELECT id, name FROM users WHERE role_id = 4");
                            if ($result_supervisors->num_rows > 0) {
                                 while ($row = $result_supervisors->fetch_assoc()) {
                                    $selected = ($row['id'] == $project['supervisor']) ? "selected" : "";
                                    echo "<option value='" . $row['id'] . "' $selected>" . $row['name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No supervisors available</option>";
                            }
                            ?>
                        </select>
                    </td>



                    <td></td> <!-- Empty cell for ACTION column -->
                </tr>
                <?php
            }
        } else {
            // If no records found
            echo "<tr><td colspan='10'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>




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
    <script>
    function makeEditable(cell) {
        // Get the original content
        let originalContent = cell.innerText;

        // Create the input field
        let input = document.createElement("input");
        input.type = "text";
        input.value = originalContent;
        input.className = "edit-input";

        // Create Save button
        let saveBtn = document.createElement("button");
        saveBtn.innerText = "Save";
        saveBtn.className = "button save-btn";
        saveBtn.onclick = function() {
            cell.innerText = input.value;
        };

        // Create Cancel button
        let cancelBtn = document.createElement("button");
        cancelBtn.innerText = "Cancel";
        cancelBtn.className = "button cancel-btn";
        cancelBtn.onclick = function() {
            cell.innerText = originalContent;
        };

        // Clear cell content and add input and buttons
        cell.innerHTML = "";
        let editContainer = document.createElement("div");
        editContainer.className = "edit-container";
        editContainer.appendChild(input);
        editContainer.appendChild(saveBtn);
        editContainer.appendChild(cancelBtn);

        cell.appendChild(editContainer);
    }
</script>

</body>

</html>