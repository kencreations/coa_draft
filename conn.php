<?php
$host = 'localhost:3308';
$username = 'root';
$password = '';
$dbname = 'coa_assignment_tracking';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

      
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        
        if ($stmt === false) {
            echo "Error: " . $conn->error;
        } else {
         
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role_id'] = $user['role_id'];

                    $_SESSION['login_success'] = true;

            
                    header("Location: index.php");
                    exit;
                } else {
                    echo "<script>alert('Invalid email or password. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('Invalid email or password. Please try again.');</script>";
            }

            $stmt->close();
        }
    } else {
        echo "<script>alert('Please provide email and password');</script>";
    }
}


?>
