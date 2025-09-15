<?php
include('conn.php');

if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) {
    $showToastr = true;
    unset($_SESSION['login_success']);
} else {
    $showToastr = false;
}



$sql = "SELECT u.id, u.name, u.email, u.position,
        sh.name AS section_head_name, 
        d.designation_name, 
        s.section_name, 
        r.role_name
FROM users u
LEFT JOIN section_heads sh ON u.section_heads_id = sh.id
LEFT JOIN designations d ON u.designation_id = d.id
LEFT JOIN sections s ON u.section_id = s.id
LEFT JOIN roles r ON u.role_id = r.id";







// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $position = $_POST['position'];
    $section_id = $_POST['section_id']; 
    $designation_id = $_POST['designation_id']; 
    $section_heads_id = $_POST['section_heads_id']; 
    $birthday = $_POST['birthday'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role_id = $_POST['role_name']; 

    // Insert the user with the section_name (no need for section_id now)
    $stmt = $conn->prepare("INSERT INTO users (name, email, number, position, role_id, section_heads_id , designation_id, section_id, birthday, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $name, $email, $number, $position, $role_id, $section_heads_id, $designation_id, $section_id, $birthday, $password);

    

    if ($stmt->execute()) {
        $_SESSION['message'] = "User created successfully!";
    } else {
        $_SESSION['message'] = "Error creating user: " . $stmt->error;
    }
    $stmt->close();
    
    header("Location: user_management.php");
    exit();
}



// Handle user editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $position = $_POST['position'];
    $role_name = $_POST['role_name'];
    $section_name = $_POST['section_name'];
    $designation_name = $_POST['designation_name'];
    $section_heads_id = $_POST['section_heads_id'];
    $birthday = $_POST['birthday'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, number=?, position=?, role_name=?, section_heads_id=? designation_name=?, section_name=?, birthday=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $name, $email, $number, $position, $role_name, $section_heads_id, $designation_name, $section_name, $birthday, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating user: " . $stmt->error;
    }
    $stmt->close();
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");

if (!$result) {
    die("Error fetching users: " . $conn->error);
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
            <!-- Display Message -->
<?php if (isset($_SESSION['message'])): ?>
    <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
<?php endif; ?>

<!-- User Form -->
<form action="user_management.php" method="POST">
    <input type="hidden" name="action" value="create">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="number">Phone Number:</label>
    <input type="text" id="number" name="number">

    <label for="position">Position:</label>
<input type="text" id="position" name="position" required>


<label for="role">Role:</label>
            <select id="role_name" name="role_name">
                <?php
                $role_query = "SELECT * FROM roles";
                $role_result = $conn->query($role_query);
                if ($role_result->num_rows > 0) {
                    while ($row = $role_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['role_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <label for="section_id">Section:</label>
            <select id="section_id" name="section_id">
                <?php
                $section_query = "SELECT * FROM sections";
                $section_result = $conn->query($section_query);
                if ($section_result->num_rows > 0) {
                    while ($row = $section_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['section_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <label for="section_heads_id">section_heads_id:</label>
            <select id="section_heads_id" name="section_heads_id">
                <?php
                $section_heads_query = "SELECT * FROM section_heads";
                $section_heads_result = $conn->query($section_heads_query);
                if ($section_heads_result->num_rows > 0) {
                    while ($row = $section_heads_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['section_heads_name'] . "</option>";
                    }
                }
                ?>
            </select>

            <label for="designation_id">Designation:</label>
            <select id="designation_id" name="designation_id">
                <?php
                $designation_query = "SELECT * FROM designations";
                $designation_result = $conn->query($designation_query);
                if ($designation_result->num_rows > 0) {
                    while ($row = $designation_result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['designations_name'] . "</option>";
                    }
                }
                ?>
            </select>


    <label for="birthday">Birthday:</label>
    <input type="date" id="birthday" name="birthday">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Create User</button>
</form>

<!-- Display Users -->
<?php
$sql = "SELECT 
u.id, 
u.name, 
u.email, 
u.number, 
u.position, 
u.birthday, 
r.role_name, 
s.section_name, 
d.designations_name, 
sh.section_heads_name
FROM users u
LEFT JOIN roles r ON u.role_id = r.id
LEFT JOIN sections s ON u.section_id = s.id
LEFT JOIN designations d ON u.designation_id = d.id
LEFT JOIN section_heads sh ON u.section_heads_id = sh.id";
$result = $conn->query($sql);


if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Position</th>
                <th>Role</th>
                <th>Section</th>
                <th>Designation</th>
                <th>Section Heads</th>
                <th>Birthday</th>
                <th>Actions</th>
            </tr>
          </thead>";
    echo "<tbody>";
    while ($user = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $user['name'] . "</td>
                <td>" . $user['email'] . "</td>
                <td>" . $user['number'] . "</td>
                <td>" . $user['position'] . "</td>
                <td>" . $user['role_name'] . "</td>
                <td>" . $user['section_name'] . "</td>
                <td>" . $user['designations_name'] . "</td>
                <td>" . $user['section_heads_name'] . "</td>
                <td>" . $user['birthday'] . "</td>
                <td>
                    <a href='user_management.php?id=" . $user['id'] . "'>Edit</a> | 
                    <a href='user_management.php?delete=" . $user['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                </td>
              </tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "0 results";
}
?>


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
</body>

</html>