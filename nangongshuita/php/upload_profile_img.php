<?php
include_once 'db_helper.php';

    if (isset($_POST['upload'])) {

        // require 'db_helper.php';

        $avatar = $_FILES['profileimg'];
        $userID = $_POST['uid'];

        $avatar_name = $avatar['name'];
        $avatar_tmp = $avatar['tmp_name'];
        $avatar_size = $avatar['size'];
        $avatar_error = $avatar['error'];
        $avatar_type = $avatar['type'];

        $date = new DateTime();

        $avatar_ext = explode('.', $avatar_name);
        $avatar_actual_ext = strtolower(end($avatar_ext));

        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($avatar_actual_ext, $allowed_ext)) {
            if ($avatar_error === 0) {
                // Byte 转换 Megabyte
                if ($avatar_size < 50000000) {
                    $avatar_uniqid = "Avatar_".$userID."_".$date->format('YmdHis').".".$avatar_actual_ext;
                    $dir = '../uploaded/profile_image/'.$avatar_uniqid;
    
                    // $query = "UPDATE useravatar SET avatarImg='$avatar_name', avatarName='$avatar_uniqid', userID='$userID' WHERE userID='$userID'";
                    $query = "INSERT INTO useravatar (avatarImg, avatarName, userID) VALUES ('$avatar_name', '$avatar_uniqid', '$userID')";
                    mysqli_query($connection, $query);
                    move_uploaded_file($avatar_tmp, $dir);
                    $sql = "UPDATE useravatar SET avatarImg='$avatar_name', avatarName='$avatar_uniqid', userID='$userID', status=0 WHERE userID='$userID'";
                    $result = mysqli_query($connection, $sql);
                    header("Location: ../index/1_life_2_3.html?update_avatar_success");
                }
                else {
                    echo "<script>alert('图片不能超过50MB，请重试'); window.location.href = '../index/1_life_2_3.html';</script>";
                }
            }
            else {
                echo "<script>alert('上传图片时出错，请重试'); window.location.href = '../index/1_life_2_3.html';</script>";
            }
        }
        else {
            echo "<script>alert('仅支持(.jpg, .jpeg, .png, .gif)，请重试'); window.location.href = '../index/upload_profile_img.html';</script>";
        }
    }

    function getAvatar($connection) {
        $sql = "SELECT * FROM users";
        $result = mysqli_query($connection, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $uid = $row['id'];
                $sqlImg = "SELECT * FROM useravatar WHERE userID='$uid'";
                $resultImg = mysqli_query($connection, $sqlImg);

                $rowImg = mysqli_fetch_array($resultImg, MYSQLI_ASSOC);
                if (isset($_SESSION['uId'])) {
                    if ($_SESSION['uId'] == $rowImg['userID']) {
                        echo '<img id="dLabel" src="../uploaded/profile_image/'.$rowImg['avatarName'].'" alt="Avatar" class="profile-avatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    }
                    elseif ($rowImg['userID'] == 0) {
                        echo '<img id="dLabel" src="../uploaded/profile_image/default.png" alt="Avatar" class="profile-avatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    }
                }
                else {
                    echo '<img id="dLabel" src="../uploaded/profile_image/default.png" alt="Avatar" class="profile-avatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                }
            }
        }
    }
?>
