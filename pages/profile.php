<?php
$oldPasswd = $newPasswd = $confirmNewPasswd = '';
$oldPasswdErr = $newPasswdErr = '';

$user = loggedInUser();
$photo = !empty($user->photo) ? $user->photo : 'pic.png'; 

if (isset($_POST['changePasswd'], $_POST['oldPasswd'], $_POST['newPasswd'], $_POST['confirmNewPasswd'])) {
    $oldPasswd = trim($_POST['oldPasswd']);
    $newPasswd = trim($_POST['newPasswd']);
    $confirmNewPasswd = trim($_POST['confirmNewPasswd']);
    if (empty($oldPasswd)) {
        $oldPasswdErr = 'please input your old password';
    }
    if (empty($newPasswd)) {
        $newPasswdErr = 'please input your new password';
    }
    if ($newPasswd !== $confirmNewPasswd) {
        $newPasswdErr = 'password does not match';
    } else {
        if (!isUserHasPassword($oldPasswd)) {
            $oldPasswdErr = 'password is incorrect';
        }
    }
    if (empty($oldPasswdErr) && empty($newPasswdErr)) {
        if (setUserNewPassword($newPasswd)) {
            unset($_SESSION['user_id']);
            echo '<div class="alert alert-success" role="alert">
                password changed successfully. <a href="./?page=login">click here</a> to login again.
                </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                try aggain.
                </div>';
        }
    }
}

if (isset($_POST['deletePhoto'])) {
    $photopath = "./assets/images/". $photo;
    if (file_exists($photopath) && $photo !== 'pic.png') {
        unlink($photopath);
        $response = deleteUserImage();
        if ($response === true) {
            $photo = 'pic.png';
            echo '<div class="alert alert-success" role="alert">
        photo deleted successfully.
        </div>';
        }
    }
}

/*profile photo upload ? */
if (isset($_POST['uploadPhoto'], $_FILES['photo'])) {
    $photo = $_FILES['photo']['name'];
    $photoTmp = $_FILES['photo']['tmp_name'];
    $photosize = $_FILES['photo']['size'];
    $photoError = $_FILES['photo']['error'];
    $photoType = $_FILES['photo']['type'];

    $fileExt = explode('.', $photo);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png');
    if (in_array($fileActualExt, $allowed)) {
        if ($photoError === 0) {
            if ($photosize < 1000000000) {
                $response = insertImage($_FILES);
                if ($response === true) {
                    echo '<div class="alert alert-success" role="alert">
                    photo updated successfully.
                    </div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                    try aggain.
                    </div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">
                your file is too big.
                </div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">
            there was an error uploading your file.
            </div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
        you cannot upload files of this type.
        </div>';
    }
}


?>
<div class="row">
    <div class="col-6">
        <form method="post" action="./?page=profile" enctype="multipart/form-data">
            <div class="d-flex justify-content-center">
                <input name="photo" type="file" id="profileUpload" hidden accept=".png, .jpg , .jpeg">
                <label role="button" for="profileUpload">
                    <img src="./assets/images/<?php echo $photo ?>" class="rounded"
                        style="width: 200px; height: 200px; object-fit: content;" alt="Profile Photo">
                </label>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" name="deletePhoto" class="btn btn-danger"
                    onclick="return confirm('are you sure you want to delete your profile photo?')">Delete</button>
                <button type="submit" name="uploadPhoto" class="btn btn-success"
                    onclick="return confirm('Do you want to upload this image?')">Upload</button>
            </div>
        </form>
    </div>
    </form>
    <div class="col-6">
        <form method="post" action="./?page=profile" class="col-md-8 col-lg-6 mx-auto">
            <h3>Change Password</h3>
            <div class="mb-3">
                <label class="form-label">Old Password</label>
                <input value="<?php echo $oldPasswd ?>" name="oldPasswd" type="password" class="form-control 
                <?php echo empty($oldPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $oldPasswdErr ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input name="newPasswd" type="password" class="form-control 
                <?php echo empty($newPasswdErr) ? '' : 'is-invalid' ?>">
                <div class="invalid-feedback">
                    <?php echo $newPasswdErr ?>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input name="confirmNewPasswd" type="password" class="form-control">
            </div>
            <button type="submit" name="changePasswd" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('profileUpload').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.querySelector('label img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>