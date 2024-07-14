<?php

require '../config/function.php'; // Include the function file

$paraResultId = checkParamId('id');

if (is_numeric($paraResultId)) {

    $adminId = validate($paraResultId);

    $admin = getById('tbl_admins_penb', $adminId);

    if ($admin['status'] == 200) {

       $adminDeleteRes = delete('tbl_admins_penb', $adminId);
       if ($adminDeleteRes) {

        redirect('admins.php', 'Admin Deleted Successfully.');
           
       }
       else{

        redirect('admins.php', 'Something Went Wrong!');

       }
    }

    else{
        redirect('admins.php', $admin['message']);
    }
    //echo $adminId;
} else {
    redirect('admins.php', 'Something Went Wrong!');
}

?>
