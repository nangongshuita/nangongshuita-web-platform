<?php

// 创造用户说说到数据库里
function setMessages($connection) {

    if (isset($_POST['msgSubmit'])) {
        $username = $_POST['uid'];
        $dt = $_POST['datetime'];
        $msg = $_POST['message'];

        $sql = "INSERT INTO messages (uname, message, cdate) VALUES ('$username', '$msg', '$dt')";
        $result = $connection->query($sql);
    }
}

// 获取个人说说
function getMessages($connection) {

    $sql = "SELECT * FROM messages";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {

        $name = $row['uname'];
        $c_sql = "SELECT * FROM users WHERE username='$name'";
        $c_result = $connection->query($c_sql);

        if ($c_row = $c_result->fetch_assoc()) {
            if (isset($_SESSION['uName'])) {
                if ($_SESSION['uName'] == $c_row['username']) {
                    echo "<div class='my_community_history'>";
                    echo "<button class='btn btn-primary'><span class='glyphicon glyphicon-user'></span></button> ".$row['uname'];
                    echo "<p><p>".nl2br($row['message']);
                    echo "<p style='color: gray;'>".$row['cdate']."<a>(UTC+8)</a>";
                    // 删除评论按钮
                    echo "<form class='delete-form' method='POST' action='".delMessages($connection)."'>
                        <input type='hidden' name='cid' value='".$row['cid']."'>
                        <button class='btn btn-primary' name='msgDel'>删除</button> 
                    </form><hr>";
                }
            }
        }
    }
}

// 删除说说
function delMessages($connection) {
    if (isset($_POST['msgDel'])) {
        $cid = $_POST['cid'];

        $sql = "DELETE FROM messages WHERE cid='$cid'";
        $result = $connection->query($sql);
                
        echo("<script>location.href = '../index/1_life_2.html';</script>");
    }
}

?>