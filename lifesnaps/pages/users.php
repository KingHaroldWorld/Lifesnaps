<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$uid = $_SESSION['user_id'];
$sql = "SELECT * from users WHERE user_id != '$uid'";
$users = array();
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result)) {
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($users, array('id' => $row['user_id'], 'username' => $row['username'], 'bio' => $row['bio']));
  }
}
// print_r($users);

mysqli_free_result($result);
mysqli_close($dbcon);
?>

<div class="row">
  <div class="col">
    <h6>Who to Follow</h6>
    <ul class="list-unstyled">
      <?php foreach ($users as $u): ?>
        <a href="?page=profile&id=<?php echo $u['id'] ?>">
          <li class="media my-3">
            <img class="mr-3" src="assets/img/avatar.png" alt="Generic placeholder image" width="25%">
            <div class="media-body">
              <p><strong><?php echo $u['username'] ?></strong>
                <!-- <br>w posts x followers y following</p> -->
              <p><?php echo $u['bio'] ?></p>
            </div>
          </li>
        </a>
      <?php endforeach; ?>
  </div>
</div>
