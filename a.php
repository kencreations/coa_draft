<?php
include 'conn.php';


$sql_users = "SELECT id, name FROM users";
$result_users = $conn->query($sql_users);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contractor_project = $_POST['contractor_project'];
    $action_officer = $_POST['action_officer']; 
    $subjects = $_POST['subjects'];
    $document_no = $_POST['document_no'];
    $date_received = $_POST['date_received'];
    $date_assigned = $_POST['date_assigned'];
    $agency = $_POST['agency'];
    $supervisor = $_POST['supervisor'];

    
    $sql = "INSERT INTO project_list (contractor_project, user_id, subjects, document_no, date_received, date_assigned, agency, supervisor) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("sissssss", $contractor_project, $action_officer, $subjects, $document_no, $date_received, $date_assigned, $agency, $supervisor);

    if ($stmt->execute()) {
        echo "Data inserted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

}
?>


<form method="POST" action="add_project.php">
    <input type="text" name="contractor_project" placeholder="Contractor / Project" required>
    

    <label for="action_officer">Action Officer</label>
    <select name="action_officer">
        <option value="">Select Action Officer</option>
        <?php
        if ($result_users->num_rows > 0) {
            while ($row = $result_users->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        } else {
            echo "<option value=''>No users available</option>";
        }
        ?>
    </select>

    <input type="text" name="collaboration_with" placeholder="Collaboration With">
    <textarea name="subjects" placeholder="Subject" required></textarea>
    <input type="text" name="document_no" placeholder="Document No." required>
    <input type="date" name="date_received" required>
    <input type="date" name="date_assigned" required>
    <input type="text" name="agency" placeholder="Agency" required>
    <label for="supervisor">Supervisor</label>
<select name="supervisor" required>
    <option value="">Select Supervisor</option>
    <?php
    // Query to fetch supervisor names from the 'users' table where role_id = 4
    $resultss = $conn->query("SELECT id, name FROM users WHERE role_id = 4");

    // Check if any records are returned
    if ($resultss->num_rows > 0) {
        while ($row = $resultss->fetch_assoc()) {
            // Display each supervisor name as an option in the dropdown
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }
    } else {
        echo "<option value=''>No supervisors available</option>";
    }
    ?>
</select>

    <button type="submit">Create</button>
</form>
