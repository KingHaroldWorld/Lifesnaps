<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$uid = $_GET['id'];
$userid = $_SESSION['user_id'];
$errors = array();
$isfollowing = false;

$sql = "SELECT * from users WHERE user_id = '$uid' LIMIT 1";
$result = mysqli_query($dbcon, $sql);

if (mysqli_num_rows($result)) {
  while ($row = mysqli_fetch_assoc($result)) {
    $username = $row['username'];
    $bio = $row['bio'];
    $public = $row['ispublic'];
    $userexist = true;
  }
} else {
  $userexist = false;
}

$posts = array();

$sql = "SELECT * FROM users JOIN posts
  WHERE users.user_id = posts.user_id
  AND users.user_id = '$uid'
  ORDER BY posts.created DESC";
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result)) {
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($posts,
      array('id' => $row['post_id'],
        'imgpath' => $row['imgpath'],
        'caption' => strip_tags($row['caption'])
      ));
  }
}

mysqli_free_result($result);

if (isset($_POST['submit'])) {
  $sql = "INSERT INTO followers (follow_by, follow_to) VALUES ('$userid', '$uid')";
  $result = mysqli_query($dbcon, $sql);
  if ($result) {
    $isfollowing = true;
  } else {
    array_push($errors, mysqli_error($dbcon));
  }
}

$sql = "SELECT * FROM followers WHERE follow_by = '$userid' AND follow_to = '$uid'";
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result)) {
  $isfollowing = true;
}

$sql = "SELECT * FROM followers WHERE follow_to = '$uid'";
$result = mysqli_query($dbcon, $sql);
if ($result) {
  $numfollowers = mysqli_num_rows($result);
}
$sql = "SELECT * FROM followers WHERE follow_by = '$uid'";
$result = mysqli_query($dbcon, $sql);
if ($result) {
  $numfollowing = mysqli_num_rows($result);
}

mysqli_free_result($result);
mysqli_close($dbcon);
?>

<?php if($userexist): ?>
  <div class="row">
    <div class="col-4">
      <img src="assets/img/avatar.png" alt="" width="100%">
    </div>
    <div class="col-8" style="padding: 0">
      <p><strong><?php echo $username; ?></strong>
        <br><?php echo count($posts) ?> posts
          <?php echo $numfollowers ?> followers
          <?php echo $numfollowing ?> following</p>
      <p><?php echo $bio; ?></p>
    </div>
  </div>
  <br>
  <?php if(!($public || $uid == $_SESSION['user_id'] || $isfollowing)): ?>
    <div class="row text-center">
      <div class="col">
        <br><br><br>
        <span class="fa-stack fa-2x">
          <i class="fas fa-lock fa-stack-1x"></i>
          <i class="fas fa-ban fa-stack-2x" style="color:inherit"></i>
        </span>
        <br><br>
        <p>This account is private.</p>
        <form action="" method="post">
          <button class="btn btn-outline-primary btn-sm" type="submit" name="submit">Follow</button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <?php if(count($posts)): ?>
      <div class="row no-gutters">
      <?php foreach ($posts as $p): ?>
        <div class="col-4">
          <a href="?page=post&id=<?php echo $p['id'] ?>">
            <img src="<?php echo $p['imgpath'] ?>" alt="<?php echo $p['caption'] ?>" width="100%">
          </a>
        </div>
      <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="row text-center">
        <div class="col">
          <br><br><br>
          <span class="fa-stack fa-2x">
            <i class="fas fa-image fa-stack-1x"></i>
            <i class="fas fa-ban fa-stack-2x" style="color:inherit"></i>
          </span>
          <br><br>
          <p>No posts available</p>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
<?php else: ?>
<div class="row text-center">
  <div class="col">
    <br><br><br>
    <span class="fa-stack fa-2x">
      <i class="fas fa-lock fa-stack-1x"></i>
      <i class="fas fa-ban fa-stack-2x" style="color:inherit"></i>
    </span>
    <br><br>
    <p>User does not exist</p>
  </div>
</div>
<?php endif; ?>

<p class="text-danger">
  <?php foreach ($errors as $e){
    echo $e . '<br>';
  } ?>
</p>
