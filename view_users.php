<?php
session_start();

// Only allow admin users
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Connect to Oracle
$conn = oci_connect("SYSTEM", "DBADMIN", "//localhost/XE");
if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}



// Fetch all users
$users = [];
$sql = "SELECT * FROM users ORDER BY role ASC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
while ($row = oci_fetch_assoc($stmt)) {
    $users[] = $row;
}
oci_free_statement($stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00b09b, #96c93d);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 700px;
        }

        h2 {
            text-align: center;
            color: #00b09b;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #00b09b;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👥 User List</h2>

    <?php if (count($users) > 0): ?>
        <table>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Role</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['USER_ID']; ?></td>
                    <td><?php echo htmlspecialchars($user['USERNAME']); ?></td>
                    <td><?php echo htmlspecialchars($user['ROLE']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
