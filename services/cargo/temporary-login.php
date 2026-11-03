<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: #0b2e52;
        }

        .box {
            background: #fff;
            padding: 36px 30px;
            border-radius: 14px;
            width: 320px;
            text-align: center;
            box-shadow: 0 12px 32px rgba(0, 0, 0, .25);
        }

        .avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            margin: 0 auto 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #2f86eb, #0b2e52);
            letter-spacing: 1px;
            user-select: none;
        }

        .username {
            font-size: 16px;
            font-weight: 600;
            color: #0b2e52;
            margin-bottom: 2px;
        }

        .subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin: 0 0 6px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d3d8e0;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 6px;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #2f86eb;
        }

        button {
            width: 100%;
            margin-top: 16px;
            padding: 11px 0;
            background: #2f86eb;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #2570cc;
        }

        .error {
            background: #fdecea;
            color: #c0392b;
            font-size: 13px;
            padding: 9px 11px;
            border-radius: 6px;
            margin: 4px 0 2px;
            display: none;
        }

        .hint {
            margin-top: 16px;
            font-size: 12px;
            color: #999;
        }

        .shake {
            animation: shake .35s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            75% {
                transform: translateX(6px);
            }
        }
    </style>
</head>

<body>
    <?php
    session_start();
    session_destroy();
    $username = $_GET['username'] ?? '';
    $fullname = $_GET['fullname'] ?? '';
    $avatar = strtoupper(substr(base64_decode($username), 0, 1));
    ?>
    <div class="box" id="loginBox">
        <div class="avatar" id="avatar"><?= $avatar ?></div>
        <div class="username" id="usernameDisplay"><?= base64_decode($fullname) ?></div>
        <div class="subtitle">Your session timed out. Enter your password to continue.</div>

   

        <div id="loginForm">
            <label for="password">Password</label>
            <input type="hidden" id="username" value="<?= base64_decode($username) ?>">
            <input type="password" id="password" name="password" placeholder="Enter your password" autofocus required>
            <button type="submit" id="login">Log In</button>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/sweetalert.js"></script>

    <script src="login.js"></script>
</body>

</html>