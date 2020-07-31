<?php

// 创造用户评论到数据库里
function setComment($connection) {

    if (isset($_POST['commSubmit'])) {
        $username = $_POST['uid'];
        $dt = $_POST['datetime'];
        $msg = $_POST['message'];

        $sql = "INSERT INTO comment (pname, comment, pdate) VALUES ('$username', '$msg', '$dt')";
        $result = $connection->query($sql);
    }
}

// 获取所有评论
function getComment($connection) {

    // 使用 [DESC] 最新的评论展示第一 
    $sql = "SELECT * FROM comment ORDER BY pdate DESC";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {

        $name = $row['pname'];
        $c_sql = "SELECT * FROM users WHERE username='$name'";
        $c_result = $connection->query($c_sql);

        if ($c_row = $c_result->fetch_assoc()) {
            if (isset($_SESSION['uName'])) {
                if ($_SESSION['uName'] == $c_row['username']) {
                    echo "<div class='my_community_history'>";
                    echo "<button class='btn btn-primary'><span class='glyphicon glyphicon-user'></span></button>
                        <a style='color: steelblue; font-weight: bold;'>".$row['pname']."</a>";
                    echo "<p><p>".nl2br($row['comment']);
                    echo "<p style='color: gray;'>".$row['pdate']."<a>(UTC+8)</a>";
                    // 删除评论按钮
                    echo "<form class='delete-form' method='POST' action='".delComment($connection)."'>
                        <input type='hidden' name='cid' value='".$row['pid']."'>
                        <button class='btn btn-danger' name='msgDel'>删除</button> 
                    </form><hr>";
                }
                else {
                    echo "<div class='my_community_history'>";
                    echo "<button class='btn btn-primary'><span class='glyphicon glyphicon-user'></span></button>
                        <a style='color: steelblue; font-weight: bold;'> ".$row['pname']."</a>";
                    echo "<p><p>".nl2br($row['comment']);
                    echo "<p style='color: gray;'>".$row['pdate']."<a>(UTC+8)</a><hr>";
                //     echo "<form class='delete-form' method='POST' action='".delComment($connection)."'>
                //     <input type='hidden' name='cid' value='".$row['cid']."'>
                //     <button class='btn btn-primary' name='msgDel'>删除</button> 
                // </form><hr>";
                }
            }
        }
    }
}

// 删除个人评论
function delComment($connection) {
    if (isset($_POST['msgDel'])) {
        $cid = $_POST['cid'];

        $sql = "DELETE FROM comment WHERE pid='$cid'";
        $result = $connection->query($sql);
                
        echo("<script>location.href = '../index/1_life_2_2.html';</script>");
    }
}

?>
