<?php

if (isset($_POST['register'])) {

    require 'db_helper.php';

    $username = $_POST['name'];
    $email = $_POST['mail'];
    $password = $_POST['pwd'];
    $passwordRep = $_POST['pwd-rep'];

    // 如果以下为空，显示错误
    if (empty($username) || empty($email) || empty($password) || empty($passwordRep)) {
        // 当输入信息错误时，显示alert消息，点击ok后返回register页面
        echo "<script>alert('表格不能为空，请重试'); window.location.href = '../index/register.html';</script>";
        // header("Location: register_inc.php?error=empty_fields&name=".$username."&mail=".$email);
        exit();
    }
    // 如果邮箱不合格，显示错误
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        // 当输入信息错误时，显示alert消息，点击ok后返回register页面
        echo "<script>alert('邮箱不合格，请重试'); window.location.href = '../index/register.html';</script>";
        // header("Location: register_inc.php?error=invalid_mailname");
        exit();
    }
    // 如果邮箱不合格，显示错误
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // 当输入信息错误时，显示alert消息，点击ok后返回register页面
        echo "<script>alert('邮箱不合格，请重试'); window.location.href = '../index/register.html';</script>";
        // header("Location: register_inc.php?error=invalid_mail&name=".$username);
        exit();
    }
    // 如果昵称不合格，显示错误
    else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        // 当输入信息错误时，显示alert消息，点击ok后返回register页面
        echo "<script>alert('无效昵称，请重试'); window.location.href = '../index/register.html';</script>";
        // header("Location: register_inc.php?error=invalid_name&mail=".$email);
        exit();
    }
    // 如果密码和再次密码不同，显示错误
    else if ($password !== $passwordRep) {
        // 当输入信息错误时，显示alert消息，点击ok后返回register页面
        echo "<script>alert('密码与确认密码不同，请重试'); window.location.href = '../index/register.html';</script>";
        // header("Location: register_inc.php?error=password_check_name=".$username."&mail=".$email);
        exit();
    }
    else {  
        $sql = "SELECT username FROM users WHERE username=?";
        $stmt = mysqli_stmt_init($connection);

        // 如果数据库出现问题，显示错误
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: register_inc.php?error=sql_error");
            exit();
        }
        // 如果昵称已使用，显示错误
        else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $resultCheck = mysqli_stmt_num_rows($stmt);

            if ($resultCheck > 0) {
                header("Location: register_inc.php?error=user_taken&mail=".$email);
                exit();
            }
            else {
                $sql = "INSERT INTO users (username, email, user_pwd) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($connection);

                // 如果数据库出现问题，显示错误
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: register_inc.php?error=sql_error");
                    exit();
                }
                else {
                    // 给用户密码加密
                    $hashPwd = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashPwd);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../index/index.html");
                    exit();
                }
            }
        }

    }
    mysqli_stmt_close($stmt);
    mysqli_close($connection);

}
// 未知错误
else {
    echo "<script>
        alert('意外错误，请检查您的昵称，邮箱和确认密码'); 
        window.location.href = '../index/register.html';
        </script>";
    exit();
    // header("Location: register_inc.php?error=unexpected_error");
    // exit();
}

?>
