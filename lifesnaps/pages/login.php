<?php
session_destroy();
$errors = array();

if (isset($_POST['submit'])) {
  $dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
  $username = mysqli_real_escape_string($dbcon, $_POST['username']);
  $password = mysqli_real_escape_string($dbcon, $_POST['password']);
  $password_hash = password_hash($password, PASSWORD_BCRYPT);

  $sql = "SELECT * from users WHERE username = '$username' LIMIT 1";
  $result = mysqli_query($dbcon, $sql);

  if (mysqli_num_rows($result)) {
    while ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($password, $row['password_hash'])) {
        session_start();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['ispublic'] = $row['ispublic'];
        $_SESSION['bio'] = $row['bio'];
        header('Location: ?page=home');
      } else {
        array_push($errors, 'Invalid username or password');
      }
    }
  } else {
    array_push($errors, 'Invalid username or password');
  }

  mysqli_free_result($result);
  mysqli_close($dbcon);
}

?>

<div class="row">
  <div class="col">
    <form method="post" action="">
      <h6>Login</h6>
      <div class="form-group">
        <input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" required
          pattern="[a-zA-Z]{1}[a-zA-Z0-9]{1,9}"
          title="Username should begin with a letter with at most 10 characters consisting of letters and numbers.">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required
        pattern=".{6,}"
        title="Password should be at least six characters.">
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

<div class="row">
  <div class="col">
    <br><br>
    <a href="?page=register">New user? Click here to register</a>
  </div>
</div>
