<!DOCTYPE html>
<html>
<head>
    <title>Library Login</title>
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

        .login-container {
            background: #ffffff;
            padding: 40px 50px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            text-align: center;
            width: 300px;
        }

        h2 {
            margin-bottom: 25px;
            color: #0072ff;
        }

        label {
            display: block;
            text-align: left;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: border 0.3s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #0072ff;
        }

        input[type="submit"] {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #0072ff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #005ecb;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>🔐 EWU Library Login</h2>
    <form method="POST" action="login_process.php">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Login">
    </form>
    <div class="footer">
        <p>📚 East West University Library System</p>
    </div>
</div>

</body>
</html>
