<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: #4c780c;
            color: white;
            width: 100%;
            position: relative;
            top: 0;
        }

        .logo a {
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            font-weight: bold;
        }

        nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 1.5rem;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 50px;
            margin-bottom: 40px;
        }

        .register-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #4d9126;
            box-shadow: 0 0 5px rgba(77, 145, 38, 0.5);
        }

        .terms-checkbox {
            margin: 10px 0 20px;
        }

        .terms-checkbox a {
            color:#000000;
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4c780c;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #115911;
        }

        .error-message {
            color: #d9534f;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        p {
            text-align: center;
        }

        a {
            text-decoration: none;
        }

        #chatbox-iframe {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px;
            height: 400px;
            border: none; 
            background: transparent; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0);
            border-radius: 10px;
            z-index: 1000;
        }

        .chatbox-label {
            position: fixed;
            bottom: 100px;
            right: 20px;
            font-size: 14px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 5px;
            border-radius: 5px;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #4c780c;
            color: white;
            margin-top: auto;
            width: 100%;
        }

        footer p {
            margin: 0;
        }

    </style>
</head>
<body>

    <nav>
        <div class="logo">
            <a href="index.php">LifeSynch</a>
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>

    <main>
        <div class="register-container">
            <h2>Register</h2>

            <div id="error-message" class="error-message" style="display: none;">
                <p>Please fill out all fields correctly.</p>
            </div>

            <form id="registerForm" action="register_action.php" method="POST" >
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>

                <div class="terms-checkbox">
                    <label>
                        <input type="checkbox" id="terms" name="terms" required> I agree to the <a href="termsandconditions.php" target="_blank">Terms and Conditions</a>.
                    </label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn">Register</button>
                </div>
                <div class="form-group">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </main>

    <div class="chatbox-label">
        <p>Need help? <br>Chat with us!</p>
    </div>
    <iframe id="chatbox-iframe" src="chatbox.php" title="Contact Us Chatbox"></iframe>

    <footer>
        <p>&copy; 2024 LifeSynch. All rights reserved.</p>
    </footer>

</body>
</html>
