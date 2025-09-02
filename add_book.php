<?php
session_start();

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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);

    $sql = "INSERT INTO books (title, author, available) VALUES (:title, :author, 1)";
    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, ":title", $title);
    oci_bind_by_name($stmt, ":author", $author);
    

    if (oci_execute($stmt)) {
        $message = "✅ Book added successfully!";
    } else {
        $error = oci_error($stmt);
        $message = "❌ Error adding book: " . $error['message'];
    }

    oci_free_statement($stmt);
}

oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
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
            padding-top: 60px;
        }

        .form-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 450px;
            text-align: center;
        }

        h2 {
            color: #0072ff;
            margin-bottom: 25px;
        }

        form {
            text-align: left;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0072ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
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
    </style>
</head>
<body>

<div class="form-container">
    <h2>➕ Add a New Book</h2>

    <?php if ($message): ?>
        <p class="<?php echo strpos($message, '❌') !== false ? 'error' : 'message'; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="add_book.php">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>

        <label for="author">Author:</label>
        <input type="text" name="author" id="author" required>

        <input type="submit" value="📚 Add Book">
    </form>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
