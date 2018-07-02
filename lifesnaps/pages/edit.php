<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$pid = $_GET['id'];
$post = array();

$sql = "SELECT * FROM posts JOIN users WHERE posts.user_id = users.user_id AND post_id = '$pid' LIMIT 1";
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result)) {
  while($row = mysqli_fetch_assoc($result)){
    $post['post_id'] = $row['post_id'];
    $post['imgpath'] = $row['imgpath'];
    $post['caption'] = $row['caption'];
    $post['created'] = $row['created'];
    $post['user_id'] = $row['user_id'];
    $post['username'] = $row['username'];
  }
}

if (isset($_POST['submit'])) {
  $caption = mysqli_real_escape_string($dbcon, $_POST['caption']);
  $timestamp = date('Y-m-d H:i:s');
  $sql = "UPDATE posts SET caption='$caption', modified='$timestamp' WHERE post_id='$pid'";
  $result = mysqli_query($dbcon, $sql);
  if ($result) {
    header('Location: ?page=post&id=' . $pid);
  } else {
    array_push($errors, mysqli_error($dbcon));
  }
}

mysqli_free_result($result);
mysqli_close($dbcon);
?>

<div class="card bg-secondary">
  <img class="card-img-top" src="<?php echo $post['imgpath'] ?>" alt="<?php echo $post['caption'] ?>">
  <div class="card-body">
    <span class="card-text float-left">
      <img src="assets/img/avatar2.png" class="img-circle" width="30" alt="">
      &nbsp;
      <?php echo $post['username'] ?>
    </span>
    <br><br>
    <form action="" method="post">
      <div class="form-group">
        <input type="text" class="form-control" name="caption" placeholder="Caption" autocomplete="off" value="<?php echo $post['caption'] ?>">
      </div>
      <input type="submit" name="submit" class="btn btn-outline-warning btn-sm" />
    </form>
  </div>
</div>
