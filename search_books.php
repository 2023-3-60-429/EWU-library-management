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

$results = [];
$search_term = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = trim($_POST['search']);

    $sql = "SELECT * FROM books WHERE LOWER(title) LIKE '%' || LOWER(:term) || '%' OR LOWER(author) LIKE '%' || LOWER(:term) || '%'";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":term", $search_term);
    oci_execute($stmt);

    while ($row = oci_fetch_assoc($stmt)) {
        $results[] = $row;
    }

    oci_free_statement($stmt);
}

oci_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
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

        .search-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 600px;
            text-align: center;
        }

        h2, h3 {
            color: #0072ff;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #0072ff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        input[type="submit"]:hover {
            background-color: #005ecb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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

<div class="search-container">
    <h2>🔍 Search Books</h2>

    <form method="POST" action="search_books.php">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Enter title or author" required>
        <input type="submit" value="Search">
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <h3>Results:</h3>
        <?php if (count($results) > 0): ?>
            <table>
                <tr>
                    <th>Book ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Available</th>
                </tr>
                <?php foreach ($results as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['BOOK_ID']); ?></td>
                        <td><?php echo htmlspecialchars($book['TITLE']); ?></td>
                        <td><?php echo htmlspecialchars($book['AUTHOR']); ?></td>
                        <td><?php echo $book['AVAILABLE'] ? '✅ Yes' : '❌ No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No books found matching '<strong><?php echo htmlspecialchars($search_term); ?></strong>'.</p>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="dashboard.php">⬅ Back to Dashboard</a></p>
</div>

</body>
</html>
