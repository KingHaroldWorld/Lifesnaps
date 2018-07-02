<?php
if (count($_SESSION) == 0) {
  header('Location: ?page=login');
}

$dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
$errors = array();
$uid = $_SESSION['user_id'];
$success = false;
$logout = false;

if (isset($_POST['submit'])) {
  $bio = mysqli_real_escape_string($dbcon, $_POST['bio']);
  $ispublic = isset($_POST['ispublic']) && $_POST['ispublic'] == '1';
  $username = mysqli_real_escape_string($dbcon, $_POST['username']);
  $password = mysqli_real_escape_string($dbcon, $_POST['password']);
  $cpassword = mysqli_real_escape_string($dbcon, $_POST['confirmpassword']);
  
  $sql = "SELECT username FROM users where username = '$username'";
  $result = mysqli_query($dbcon, $sql);
	if(mysqli_num_rows($result) > 0){
		echo "<div class='alert alert-danger' role='alert' id='formAlert'>
					  <strong>Existing Username!</strong>
					</div>";
	}
  
  if (strcmp($password, $cpassword) != 0) {
    array_push($errors, "Passwords don't match");
  }
  // print_r($ispublic);
  $timestamp = date('Y-m-d H:i:s');
  $sql = "UPDATE users SET bio='$bio', ispublic='$ispublic', modified='$timestamp' ";
  if (strcmp($username, '') != 0) {
    $sql = $sql . ", username='$username'";
    $logout = true;
  }
  if (strcmp($password, '') != 0) {
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sql = $sql . ", password_hash='$password_hash'";
    $logout = true;
  }
  $sql = $sql . " WHERE user_id='$uid'";
  // print_r($sql);

  $success = mysqli_query($dbcon, $sql);
  if ($success) {
    $_SESSION['ispublic'] = $ispublic;
    $_SESSION['bio'] = $bio;
    if ($logout) {
      header('Location: ?page=login');
    }
  } else {
    array_push($errors, mysqli_error($dbcon));
  }
}

mysqli_close($dbcon);
?>

<?php if($success): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  Account edited successfully.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?>

<div class="row">
  <div class="col">
    <h6>Account Settings</h6>
    <form class="" action="" method="post">
      <div class="form-group">
        <label for="bio">Description</label>
        <input type="text" class="form-control" name="bio" placeholder="Bio" autocomplete="off" size="50" value="<?php echo $_SESSION['bio'] ?>">
      </div>
      <div class="form-group">
        <!-- <label for="username">Change username</label> -->
		<label for="username">Username</label>
        <input type="text" class="form-control" name="username" placeholder="Change username" autocomplete="off"
          pattern="[a-zA-Z]{1}[a-zA-Z0-9]{1,9}"
          title="Username should begin with a letter with at most 10 characters consisting of letters and numbers.">
      </div>
      <div class="form-group">
        <!-- <label for="password">Change password</label> -->
		<label for="password">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Change password" autocomplete="off"
          pattern=".{6,}"
          title="Password should be at least six characters.">
        <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm password" autocomplete="off"
          pattern=".{6,}"
          title="Password should be at least six characters.">
      </div>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" <?php if($_SESSION['ispublic']) echo 'checked'; ?> name="ispublic" value="1">
        <label class="form-check-label" for="ispublic">
          Allow posts to be public?
        </label>
      </div>
      <br>
      <input type="submit" name="submit" class="btn btn-outline-primary btn-sm"/>
    </form>
  </div>
</div>
