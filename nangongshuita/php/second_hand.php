<?php   
// if (isset($_POST['submit'])) {
 
//     require 'db_helper.php';

//     $item_name = $_POST['iname'];
//     $item_desc = $_POST['idesc'];
//     $item_price = $_POST['iprice'];
//     $seller_ch_id = $_POST['schatid'];
//     $seller_phone = $_POST['sphone'];

//     // $sql = "INSERT INTO secondhand(itemName, itemPrice, itemDesc, sellerChatID, sellerPhone) VALUES ('$item_name', '$item_price', '$item_desc', '$seller_ch_id', '$seller_phone')";
 
//     $name = $_FILES['imgupload']['name'];
//     $target_dir = "../uploaded/";
//     $target_file = $target_dir . basename($_FILES["imgupload"]["name"]);
  
//     // Select file type
//     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  
//     // Valid file extensions
//     $extensions_arr = array("jpg", "jpeg", "png", "gif");
  
//     // Check extension
//     if( in_array($imageFileType,$extensions_arr) ){
   
//         // Insert record
//         $query = "INSERT INTO secondhand (itemName, itemPrice, itemDesc, itemImg, sellerChatID, sellerPhone) VALUES ('$item_name', '$item_price', '$item_desc', '$name', '$seller_ch_id', '$seller_phone')";
//         mysqli_query($connection, $query);
        
//         // Upload file
//         move_uploaded_file($_FILES['imgupload']['tmp_name'],$target_dir.$name);
  
//     }
   
// }

if (isset($_POST['submit'])) {

    require 'db_helper.php';
    
    $file = $_FILES['imgupload'];
    $item_name = $_POST['iname'];
    $item_desc = $_POST['idesc'];
    $item_price = $_POST['iprice'];
    $seller_ch_id = $_POST['schatid'];
    $seller_phone = $_POST['sphone'];
    $seller_name = $_POST['sname'];

    $date = new DateTime();

    $file_name = $_FILES['imgupload']['name'];
    $file_tmp = $_FILES['imgupload']['tmp_name'];
    $file_size = $_FILES['imgupload']['size'];
    $file_error = $_FILES['imgupload']['error'];
    $file_type = $_FILES['imgupload']['type'];

    $file_ext = explode('.', $file_name);
    $file_actual_ext = strtolower(end($file_ext));

    $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($file_actual_ext, $allowed_ext)) {
        if ($file_error === 0) {
            // Byte 转换 Megabyte
            if ($file_size < 50000000) {
                // $file_uniqid = uniqid('', true).".".$file_actual_ext;
                // $file_uniqid = "Items_".$seller_name.".".$file_actual_ext;
                $file_uniqid = "Items_".$seller_name."_".$date->format('YmdHis').".".$file_actual_ext;
                $dir = '../uploaded/'.$file_uniqid;

                $query = "INSERT INTO secondhand (itemName, itemPrice, itemDesc, itemImg, imgName, sellerChatID, sellerPhone, sellerName) VALUES ('$item_name', '$item_price', '$item_desc', '$file_name', '$file_uniqid', '$seller_ch_id', '$seller_phone', '$seller_name')";
                mysqli_query($connection, $query);
                move_uploaded_file($file_tmp, $dir);
                header("Location: ../index/1_life_2_3.html?upload_success");
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
        echo "<script>alert('仅支持(.jpg, .jpeg, .png, .gif)，请重试'); window.location.href = '../index/1_life_2_3.html';</script>";
    }
}

function getItems($connection) {
    $sql = "SELECT * FROM secondhand";
    $result = $connection->query($sql);

    while ($row = $result->fetch_assoc()) {
        $sname = $row['sellerName'];
        $s_sql = "SELECT * FROM users WHERE username='$sname'";
        $s_result = $connection->query($s_sql);

        if ($s_row = $s_result->fetch_assoc()) {
            if (isset($_SESSION['uName'])) {
                if ($_SESSION['uName'] == $s_row['username']) {
                    echo '<div class="column">';
                    echo '<img src="../uploaded/'.$row['imgName'].'" alt="Avatar" class="item-img" onclick="document.getElementById(\'see_product\').style.display=\'block\'">';  
                    echo '<div class="item-desc">';
                    echo '<p style="font-weight: bold; color: #00ff00">您的宝贝</p>';
                     // 删除物品按钮
                     echo "<form class='delete-form' method='POST' action='".delItem($connection)."'>
                            <input type='hidden' name='iid' value='".$row['itemID']."'>
                            <button class='btn btn-danger' name='delItem'>下架</button> 
                        </form>";
                    echo '</div> </div>';
                    // 物品点击后打开小窗口
                    // echo '<div id="see_product" class="modal">
					// 	<form class="modal-content animate" enctype="multipart/form-data">
					// 		<div class="img-container">
					// 			<span onclick="document.getElementById(\'see_product\').style.display=\'none\'" class="close" title="关闭">&times;</span>
					// 			<br>
					// 			<img src="../uploaded/'.$row['imgName'].'" alt="Avatar" class="item-container">
					// 		</div>
							
					// 		<div class="item-container">
                    //             <label style="font-weight: bold; font-size: 20px;>'.$row['itemName'].'</label>
														
					// 		</div>
							
					// 		<div class="item-container" style="background-color:#f1f1f1"></div>
					// 	</form>
					// </div>';
                }
                else {
                    // echo '<div class="column" data-toggle="modal" data-target="#myModal">';
                    echo '<div class="column">';
                    echo '<input type="hidden" name="iid" value="'.$row['itemID'].'">';
                    echo '<img src="../uploaded/'.$row['imgName'].'" alt="Avatar" class="item-img">';  
                    echo '<div class="item-desc">';
                    echo '<p style="font-weight: bold; font-size: 16px;">'.$row['itemName'].'</p>';
                    echo '<p>'.$row['itemDesc'].'</p>';
                    echo '<p style="font-weight: bold; font-size: 20px;">￥'.$row['itemPrice'].'</p>';
                    echo '<div class="dropdown">
                            <a id="dLabel" type="button" class="btn btn-success dropdown-toggle btn-s" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">卖家信息
                            <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dLabel" style="padding: 6px">
                                <a style="font-weight: bold;">微信/QQ号</a>
                                <p>'.$row['sellerChatID'].'</p>
                                <a style="font-weight: bold;">电话号码</a>
                                <p>'.$row['sellerPhone'].'</p>
                            </ul>
                        </div>';
                    // echo '<form method="POST" action="'.showDetails($connection).'">
                    //         <input type="hidden" name="iid" value="'.$row['itemID'].'">
                    //         <button class="btn btn-primary" name="showDetails">查看</button> 
                    //     </form>';
                    echo '</div> </div>';
                    // 物品点击后打开小窗口
                    // echo '<div class="modal fade" tabindex="-1" id="myModal" role="dialog" aria-labelledby="myModalLabel">
                    //     <div class="modal-dialog" role="document">
                    //         <div class="modal-content">
                    //             <div class="modal-header">
                    //                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    //                 <h4 class="modal-title">￥'.$row['itemPrice'].'</h4>
                    //             </div>
                    //             <div class="modal-body">
                    //             ...
                    //             </div>
                    //             <div class="modal-footer">
                    //                 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    //             </div>
                    //         </div>
                    //     </div>
                    // </div>';
                }
            }
        }
    }
}

// 删除个人物品
function delItem($connection) {
    if (isset($_POST['delItem'])) {
        $iid = $_POST['iid'];

        $sql = "DELETE FROM secondhand WHERE itemID='$iid'";
        $result = $connection->query($sql);
                
        echo("<script>location.href = '../index/1_life_2_3.html?success=item_deleted_$iid';</script>");
    }
}

function showDetails($connection) {
    if (isset($_POST['showDetails'])) {
        $iid = $_POST['iid'];

        $sql = "SELECT * FROM secondhand WHERE itemID=$iid";
        $result = $connection->query($sql);
        
        while ($row = $result->fetch_assoc()) {
            echo '<p>'.$row['itemName'].'</p>';
            echo '<p style="font-weight: bold;">￥'.$row['itemPrice'].'</p>';
            echo '<p>'.$row['itemID'].'</p>';
        }
    }
}

?>

<script>
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var recipient = button.data('iid') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('New message to ' + recipient)
        //   modal.find('.modal-body input').val(recipient)
    })
</script>
