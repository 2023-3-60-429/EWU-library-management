<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .dashboard-container {
            background: #ffffff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 10px;
            color: #0072ff;
        }

        p {
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 12px 0;
        }

        a {
            display: inline-block;
            padding: 10px 18px;
            background-color: #0072ff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #005ecb;
        }

        .logout {
            margin-top: 20px;
            font-size: 14px;
        }

        .logout a {
            background-color: #ff4d4d;
        }

        .logout a:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
    <p>Your role: <?php echo htmlspecialchars($role); ?></p>

    <?php if ($role === 'admin'): ?>
        <ul>
            <li><a href="add_book.php">➕ Add Book</a></li>
            <li><a href="booklist_admin.php">📚 See All Books</a></li>
            <li><a href="view_users.php">👥 View Users</a></li>
        </ul>
    <?php elseif ($role === 'student'): ?>
        <ul>
            <li><a href="search_books.php">🔍 Search Books</a></li>
            <li><a href="booklist_student.php">📚 See All Books</a></li>
            <li><a href="borrow.php">📥 Borrow Book</a></li>
            <li><a href="return.php">📤 Return Book</a></li>
        </ul>
    <?php endif; ?>

    <div class="logout">
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

</body>
</html>
