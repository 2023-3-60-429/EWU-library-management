<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connect to Oracle
$conn = oci_connect("SYSTEM", "DBADMIN", "//localhost/XE");
if (!$conn) {
    $e = oci_error();
    die("❌ Connection failed: " . $e['message']);
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
    <title>Books</title>
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
            padding-top: 40px;
        }

        .booklist-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 600px;
            text-align: center;
        }

        h2 {
            color: #0072ff;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
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
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="booklist-container">
    <h2>📚 List of Books</h2>

    <?php if (count($books) > 0): ?>
        <table>
            <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Available</th>
            </tr>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['BOOK_ID']; ?></td>
                    <td><?php echo htmlspecialchars($book['TITLE']); ?></td>
                    <td><?php echo htmlspecialchars($book['AUTHOR']); ?></td>
                    <td><?php echo $book['AVAILABLE'] ? '✅ Yes' : '❌ No'; ?></td>
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
