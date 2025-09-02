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

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);
    
    $delete_sql = "DELETE FROM books WHERE book_id = :book_id";
    $delete_stmt = oci_parse($conn, $delete_sql);
    oci_bind_by_name($delete_stmt, ":book_id", $book_id);
    
    if (oci_execute($delete_stmt)) {
        $message = "✅ Book deleted successfully.";
    } else {
        $error = oci_error($delete_stmt);
        $message = "❌ Failed to delete book: " . $error['message'];
    }

    oci_free_statement($delete_stmt);
}

// Fetch all books
$books = [];
$sql = "SELECT * FROM books ORDER BY title ASC";
$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
while ($row = oci_fetch_assoc($stmt)) {
    $books[] = $row;
}
oci_free_statement($stmt);
oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Show All Books</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff6a00, #ee0979);
            margin: 0;
            padding: 0;
            height: 100vh;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 60px;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 850px;
        }

        h2 {
            text-align: center;
            color: #ee0979;
            margin-bottom: 25px;
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

        input[type="submit"] {
            padding: 6px 12px;
            background-color: #ee0979;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #d4086a;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #ee0979;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h2> List Of Books</h2>

    <?php if (isset($message)): ?>
        <p class="<?php echo strpos($message, '❌') !== false ? 'error' : 'message'; ?>"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (count($books) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Available</th>
                <th>Action</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['BOOK_ID']; ?></td>
                    <td><?php echo htmlspecialchars($book['TITLE']); ?></td>
                    <td><?php echo htmlspecialchars($book['AUTHOR']); ?></td>
                    <td><?php echo htmlspecialchars($book['AVAILABLE']); ?></td>
                    <td>
                        <form method="POST" action="delete_book.php" onsubmit="return confirm('Are you sure you want to delete this book?');">
                            <input type="hidden" name="book_id" value="<?php echo $book['BOOK_ID']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No books available.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
