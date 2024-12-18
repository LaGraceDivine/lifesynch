<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        *   {
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

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #4c780c;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #115911;
        }
        #error-message {
            display: none;
            color: red;
            text-align: center;
            font-size: 14px;
            margin-top: 10px;
        }
        #loading {
            display: none;
            text-align: center;
            margin-top: 10px;
            color: #555;
            font-size: 14px;
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

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li {
            margin-left: 1.5rem;
        }

        .nav-links a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background: #4c780c;
            color: white;
            width: 100%;
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

        .register-link {
            text-align: center;
            margin-top: 10px;
            text-decoration: none;
        }

        body .register-link p a {
            text-decoration: none;
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
        <div class="login-container">
            <h2>Login</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="username_or_email">Email</label>
                    <input type="email" id="username_or_email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn">Login</button>

                <div class="register-link">
                <p>Don't have an account? <a href="register.php">Register</a></p>
                </div>

                <div id="error-message"></div>
                <div id="loading">Processing your login, please wait...</div>
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

    <script>
        $(document).ready(function () {
            
            $('#loginForm').on('submit', function (e) {
                e.preventDefault(); 

                const email = $('#username_or_email').val();
                const password = $('#password').val();

               
                if (!email || !password) {
                    showErrorMessage("Please fill in all fields.");
                    return;
                }

                $.ajax({
                    url: 'login_action.php', 
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        email: email,
                        password: password
                    },
                    beforeSend: function () {
                        
                        $('#error-message').hide();
                        $('#loading').show();
                    },
                    success: function (response) {
                        $('#loading').hide();

                        if (response.success) {
                            
                            window.location.href = "dashboard.php";
                        } else {
                            
                            showErrorMessage(response.message);
                        }
                    },
                    error: function () {
                        $('#loading').hide(); 
                        showErrorMessage("Wrong password or email try again.");
                        
                    }
                });
            });

        
            function showErrorMessage(message) {
                $('#error-message').text(message).show();
            }
        });
    </script>
</body>
</html>
