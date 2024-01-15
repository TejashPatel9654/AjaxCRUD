<?php
include("config.php");

if (!empty($_GET['type']) && $_GET['type'] == 'list') {
    $sql = 'SELECT * FROM users';
    $result = $conn->query($sql);
    $html = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr id="row'.$row['id'].'">
                        <td>' . $row['id'] . '</td>';
            if(!empty($row['profile_pic'])){
                $html .= '<td><img src="img/'.$row['profile_pic'].'" height="70" width="100"></td>';
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td>' . $row['name'] . '</td>
                        <td>' . $row['email'] . '</td>
                        <td>' . $row['phone'] . '</td>
                        <td>' . date('d-m-Y', strtotime($row['created_at'])) . '</td>
                        <td>
                                <button class="btn-xs btn-primary editRecord" id="' . $row['id'] . '">Edit</button>
                                <button class="btn-xs btn-danger deleteRecord" id="' . $row['id'] . '">Delete</button>
                        </td>
                    </tr>';
        }
    } else {
        $html .= '<tr>
        <td colspan="100%">Record not found.</td>
      </tr>';
    }

    $json['html'] = $html;
    echo json_encode($json);
} elseif (!empty($_GET['type']) && $_GET['type'] == 'add') {
    if(!empty($_FILES['profile']['name'])){
        $ext = explode(".",$_FILES['profile']['name']);
        $ext_name = end($ext);
        $profile = date('Ymdhis').'.'.$ext_name;
        $destination = "img/".$profile;
        move_uploaded_file($_FILES['profile']['tmp_name'],$destination);
    }else{
        $profile = '';
    }
    $sql = "INSERT INTO `users`(`name`, `email`, `phone`,`profile_pic`, `created_at`) VALUES ('" . $_POST['name'] . "','" . $_POST['email'] . "','" . $_POST['phone'] . "','" .$profile. "','" . date('Y-m-d H:i:s') . "')";
    if ($conn->query($sql) == TRUE) {
        $json["success"] = true;
        $json["message"] = "Record successfully added.";
    } else {
        $json["success"] = false;
        $json["message"] = "Due to some error pls try again.";
    }

    echo json_encode($json);
} elseif (!empty($_GET['type']) && $_GET['type'] == 'edit') {
    $sql = 'SELECT * FROM users WHERE id = "' . $_POST['id'] . '"';
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    if(!empty($row['profile_pic'])){
        $row['old_profile'] = '<img src="img/'.$row['profile_pic'].'" height="70" width="100">';
    }else{
        $row['old_profile'] = '';
    }
    echo json_encode($row);
} elseif (!empty($_GET['type']) && $_GET['type'] == 'update') {
    if(!empty($_FILES['profile']['name'])){
        $ext = explode(".",$_FILES['profile']['name']);
        $ext_name = end($ext);
        $profile = date('Ymdhis').'.'.$ext_name;
        $destination = "img/".$profile;
        move_uploaded_file($_FILES['profile']['tmp_name'],$destination);
    }else{
        $profile = $_POST['old_profile'];
    }

    $sql = "UPDATE `users` SET `name`='" . $_POST['name'] . "',`email`='" . $_POST['email'] . "',`phone`='" . $_POST['phone'] . "',`profile_pic`='" .$profile. "' WHERE id = '" . $_POST['user_id'] . "'";
    if ($conn->query($sql) == TRUE) {
        $json["success"] = true;
        $json["message"] = "Record successfully updated.";
    } else {
        $json["success"] = false;
        $json["message"] = "Due to some error pls try again.";
    }

    echo json_encode($json);
} elseif (!empty($_GET['type']) && $_GET['type'] == 'delete') {
    $sql = 'DELETE FROM `users`  WHERE id = "' . $_POST['id'] . '"';
    if ($conn->query($sql) == TRUE) {
        $json["success"] = true;
        $json["message"] = "Record successfully deleted.";
    } else {
        $json["success"] = false;
        $json["message"] = "Due to some error pls try again.";
    }
    echo json_encode($json);
}
