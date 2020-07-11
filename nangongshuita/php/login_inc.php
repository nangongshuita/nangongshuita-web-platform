<?php

// $_POST['login'] 是登录按钮在（login.html）的name
if (isset($_POST['login'])) {

    require 'db_helper.php';

    $email = $_POST['lmail'];
    $password = $_POST['lpwd'];

    // 如果邮箱和密码为空，出报错
    if (empty($email) || empty($password)) {
        header("Location: ../index/login.html?error=emptyfields");
        exit();
    }
    else {
        $sql = "SELECT * FROM users WHERE username=? OR email=?;";
        $stmt = mysqli_stmt_init($connection);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../index/index.html=sql_error");
            exit();
        }
        else {
            mysqli_stmt_bind_param($stmt, "ss", $email, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $pwdCheck = password_verify($password, $row['user_pwd']);

                if ($pwdCheck == false) {
                    header("Location: ../index/login.html?error=wrong_password");
                    exit();
                }
                else if ($pwdCheck == true) {
                    session_start();
                    $_SESSION['uId'] = $row['id'];
                    $_SESSION['uName'] = $row['username'];

                    header("Location: ../index/index.html?login=success");
                    exit();
                }
            }
            else {
                header("Location: ../index/login.html?error=no_user_found");
                exit();
            }
        }
    }
}
else {
    header("Location: ../index/index.html");
    exit();
}

?>