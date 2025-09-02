<?php
session_start();

// 👇 Update this with your actual Oracle DB credentials
$conn = oci_connect("SYSTEM", "DBADMIN", "//localhost/XE");

if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = :username AND TO_CHAR(password) = :password";

    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":username", $username);
    oci_bind_by_name($stmt, ":password", $password);
    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);

    if ($row) {
        $_SESSION['username'] = $row['USERNAME'];
        $_SESSION['role'] = $row['ROLE'];
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p>❌ Invalid username or password.</p>";
        echo "<a href='login.php'>Try again</a>";
    }

    oci_free_statement($stmt);
    oci_close($conn);
}
?>
