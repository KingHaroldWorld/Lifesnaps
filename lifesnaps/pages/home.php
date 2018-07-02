<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$uid = $_SESSION['user_id'];

$posts = array();
$sql = "SELECT * FROM users JOIN posts
  WHERE users.user_id = posts.user_id
  AND users.ispublic = '1'
  ORDER BY posts.created DESC";
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result)) {
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($posts,
      array('post_id' => $row['post_id'],
        'imgpath' => $row['imgpath'],
        'caption' => $row['caption'],
        'user_id' => $row['user_id'],
        'username' => $row['username']
      )
    );
  }
}
mysqli_free_result($result);
mysqli_close($dbcon);
?>

<?php if (count($posts)): ?>
  <?php foreach ($posts as $p): ?>
  <a href="?page=post&id=<?php echo $p['post_id'] ?>">
    <div class="card bg-secondary">
      <img class="card-img-top" src="<?php echo $p['imgpath'] ?>" alt="<?php echo $p['caption'] ?>">
      <div class="card-body">
        <a href="?page=profile&id=<?php echo $p['user_id'] ?>">
          <span class="card-text float-left">
            <img src="assets/img/avatar.png" class="" width="30" alt="">
            &nbsp;
            <?php echo $p['username'] ?>
          </span>
        </a>
        <br><br>
        <p class="card-text">
          <?php echo $p['caption'] ?>
        </p>
      </div>
    </div>
  </a>
  <?php endforeach; ?>
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
