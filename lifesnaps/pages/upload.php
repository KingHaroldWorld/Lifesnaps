<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$errors = array();
$success = true;
$uid = $_SESSION['user_id'];
$target_dir = config('uploads_path');

$sql = "SELECT * FROM posts ORDER BY post_id DESC LIMIT 1";
$result = mysqli_query($dbcon, $sql);
while($row = mysqli_fetch_assoc($result)){
  $lastid = $row['post_id'];
}
$lastid = $lastid + 1;


if (isset($_POST['submit'])) {
  // print_r($_FILES['image']);
  $filext = explode('.', $_FILES['image']['name']);
  // print_r($filext);
  $target_file = $target_dir . $uid . '_' . $lastid . '.' . $filext[1];
  $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $caption = mysqli_real_escape_string($dbcon, strip_tags($_POST['caption']));
  // print_r($target_file);
  $uploadOk = 1;

  $check = getimagesize($_FILES['image']["tmp_name"]);
  if($check == false) {
    array_push($errors, "File is not an image.");
    $uploadOk = 0;
  }

  if($filetype != "jpg" && $filetype != "png" && $filetype != "jpeg" && $filetype != "gif" ) {
    array_push($errors, "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    $uploadOk = 0;
  }

  if ($uploadOk) {
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      $sql = "INSERT INTO posts (user_id, imgpath, caption) VALUES ('$uid', '$target_file', '$caption')";
      // print_r($sql);
      $result = mysqli_query($dbcon, $sql);
      if ($result) {
        header('Location: ?page=post&id=' . $lastid);
      } else {
        array_push($errors, mysqli_error($dbcon));
      }
    } else {
      array_push($errors, "File system error");
    }
  }
}

mysqli_free_result($result);
mysqli_close($dbcon);
?>

<div class="row">
  <div class="col">
    <form action="" method="post" enctype="multipart/form-data">
      <!-- <h6>Upload</h6> -->
      <div class="custom-file">
        <input type="file" name="image" class="custom-file-input" id="imageupload" onchange="preview_image(event)" required>
        <br><br>
        <img id="img_pre" width="100%">
        <label class="custom-file-label" for="image">Choose image...</label>
      </div>
      <div class="form-group" style="margin-top: 20rem;">
        <!-- <label for="username">Caption</label> -->
        <input type="text" class="form-control" name="caption" placeholder="Caption" autocomplete="off">
      </div>
      <p class="text-danger">
        <?php foreach ($errors as $e) {
          echo $e . '<br>';
        } ?>
      </p>
      <input type="submit" name="submit" class="btn btn-outline-primary btn-sm" />
    </form>
  </div>
</div>

<script type='text/javascript'>
  function preview_image(event) {
    var reader = new FileReader();
    reader.onload = function() {
      var output = document.getElementById('img_pre');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
