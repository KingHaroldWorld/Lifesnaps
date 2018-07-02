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
    $post['caption'] = strip_tags($row['caption']);
    $post['created'] = $row['created'];
    $post['user_id'] = $row['user_id'];
    $post['username'] = $row['username'];
    $post['created'] = $row['created'];
  }
}

mysqli_free_result($result);
mysqli_close($dbcon);
?>

<div class="card bg-secondary">
  <img class="card-img-top" src="<?php echo $post['imgpath'] ?>" alt="<?php echo $post['caption'] ?>">
  <div class="card-body">
    <a href="?page=profile&id=<?php echo $post['user_id'] ?>">
      <span class="card-text float-left">
        <img src="assets/img/avatar.png" class="" width="30" alt="">
        &nbsp;
        <?php echo $post['username'] ?>
      </span>
    </a>
    <?php if ($post['user_id'] === $_SESSION['user_id']): ?>
      <span class="float-right" style="line-height:2">
        <a href="?page=edit&id=<?php echo $post['post_id'] ?>"><i class="fas fa-edit fa-lg"></i></a>
      </span>
    <?php endif; ?>
    <br><br>
    <p class="card-text">
      <?php echo $post['caption'] ?>
      <br>
      <?php echo date_format(date_create($post['created']), 'd M, h:i a') ?></small>
    </p>
  </div>
</div>
