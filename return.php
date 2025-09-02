<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

// Connect
$conn = oci_connect("SYSTEM", "DBADMIN", "//localhost/XE");
if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
}

// Get user_id
$username = $_SESSION['username'];
$user_stmt = oci_parse($conn, "SELECT user_id FROM users WHERE username = :username");
oci_bind_by_name($user_stmt, ":username", $username);
oci_execute($user_stmt);
$user = oci_fetch_assoc($user_stmt);
$user_id = $user['USER_ID'];
oci_free_statement($user_stmt);

// Handle return
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['record_id'])) {
    $record_id = intval($_POST['record_id']);

    $update_stmt = oci_parse($conn, "UPDATE borrow_records SET return_date = SYSDATE WHERE record_id = :record_id AND user_id = :user_id");
    oci_bind_by_name($update_stmt, ":record_id", $record_id);
    oci_bind_by_name($update_stmt, ":user_id", $user_id);

    if (oci_execute($update_stmt)) {
        $message = "✅ Book returned successfully!";

	$update_sql = "UPDATE books 
               SET available = 1 
               WHERE book_id = (
                   SELECT book_id 
                   FROM borrow_records 
                   WHERE record_id = :record_id
               )";

        $update_stmt1 = oci_parse($conn, $update_sql);
	oci_bind_by_name($update_stmt1, ":record_id", $record_id);
	oci_execute($update_stmt1);
	oci_free_statement($update_stmt1);


    } else {
        $error = oci_error($update_stmt);
        $message = "❌ Error: " . $error['message'];
    }
    oci_free_statement($update_stmt);
}

// Fetch unreturned books
$records = [];
$sql = "SELECT br.record_id, b.title, b.author
        FROM borrow_records br
        JOIN books b ON br.book_id = b.book_id
        WHERE br.user_id = :user_id AND br.return_date IS NULL";
$stmt = oci_parse($conn, $sql);
oci_bind_by_name($stmt, ":user_id", $user_id);
oci_execute($stmt);
while ($row = oci_fetch_assoc($stmt)) {
    $records[] = $row;
}
oci_free_statement($stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            margin: 0;
            padding: 0;
            height: 100vh;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 50px;
        }

        .return-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 500px;
            text-align: center;
        }

        h2 {
            color: #0072ff;
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #0072ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #005ecb;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0072ff;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            font-weight: bold;
            margin-bottom: 10px;
            color: green;
        }

        .error {
            color: red;
        }

        p {
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="return-container">
    <h2>📤 Return a Book</h2>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, '❌') !== false ? 'error' : 'message'; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (count($records) > 0): ?>
        <form method="POST" action="return.php">
            <label for="record_id">Select a borrowed book:</label>
            <select name="record_id" id="record_id" required>
                <option value="">-- Choose a book --</option>
                <?php foreach ($records as $rec): ?>
                    <option value="<?php echo $rec['RECORD_ID']; ?>">
                        <?php echo htmlspecialchars($rec['TITLE']) . " by " . htmlspecialchars($rec['AUTHOR']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="📤 Return Book">
        </form>
    <?php else: ?>
        <p>You have no borrowed books to return.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
