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

// Handle borrowing
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);

    // Check if already borrowed and not returned
    $check_stmt = oci_parse($conn, "SELECT * FROM borrow_records WHERE user_id = :user_id AND book_id = :book_id AND return_date IS NULL");
    oci_bind_by_name($check_stmt, ":user_id", $user_id);
    oci_bind_by_name($check_stmt, ":book_id", $book_id);
    oci_execute($check_stmt);

    if (oci_fetch_assoc($check_stmt)) {
        $message = "⚠️ You already borrowed this book and haven't returned it.";
    } else 
    {
        $insert_stmt = oci_parse($conn, "INSERT INTO borrow_records (user_id, book_id) VALUES (:user_id, :book_id)");
        oci_bind_by_name($insert_stmt, ":user_id", $user_id);
        oci_bind_by_name($insert_stmt, ":book_id", $book_id);
        if (oci_execute($insert_stmt)) {
            $message = "✅ Book borrowed successfully!";
	    
	    $update_sql = "UPDATE books SET available=0 WHERE book_id = :book_id";
    	    $update_stmt = oci_parse($conn, $update_sql);
    	    oci_bind_by_name($update_stmt, ":book_id", $book_id);
    	    oci_execute($update_stmt);
    
    	    oci_free_statement($update_stmt);


        } else {
            $error = oci_error($insert_stmt);
            $message = "❌ Error: " . $error['message'];
        }
        oci_free_statement($insert_stmt);
    }
    oci_free_statement($check_stmt);
}

// Show available books
$books = [];
$book_stmt = oci_parse($conn, "SELECT * FROM books ORDER BY title");

oci_execute($book_stmt);
while ($row = oci_fetch_assoc($book_stmt)) {
    $books[] = $row;
}
oci_free_statement($book_stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Borrow Book</title>
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

        .borrow-container {
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

        p {
            margin-top: 10px;
            color: #444;
        }

        .message {
            font-weight: bold;
            margin-bottom: 10px;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="borrow-container">
    <h2>📚 Borrow a Book</h2>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, '❌') !== false ? 'error' : 'message'; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="borrow.php">
        <label for="book_id">Select a book:</label>
        <select name="book_id" id="book_id" required>
            <option value="">-- Choose a book --</option>
            <?php foreach ($books as $book): ?>
                <option value="<?php echo $book['BOOK_ID']; ?>">
                    <?php echo htmlspecialchars($book['TITLE']) . " by " . htmlspecialchars($book['AUTHOR']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="📥 Borrow">
    </form>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
