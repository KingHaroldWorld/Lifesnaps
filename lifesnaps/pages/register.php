<?php
$errors = array();

if (isset($_POST['submit'])) {
  $dbcon = mysqli_connect(config('dbhost'), config('dbuser'), config('dbpass'), config('dbname'));
  // print_r($dbcon);
  $username = $_POST['username'];

  // check if passwords match.
  if (strcmp($_POST['password'], $_POST['confirmpassword']) !== 0) {
    array_push($errors, "Passwords don't match");
  } else {
    // proceed inserting to db if username is unique
    $username = mysqli_real_escape_string($dbcon, $_POST['username']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password_hash, ispublic) VALUES ('$username', '$password_hash', 1)";
    // print_r($sql);

    if (mysqli_query($dbcon, $sql)) {
      header('Location: ?page=login');
    } else {
      if(mysqli_errno($dbcon) == 1062){ // if there is a duplicate entry
          array_push($errors, "Username is already taken");
      }
    }
  }

  mysqli_close($dbcon);
}
?>

<div class="row">
  <div class="col">
    <form method="post" action="">
      <h6>Register</h6>
      <div class="form-group">
        <!-- <label for="username">Username</label> -->
        <input
          type="text"
          class="form-control"
          name="username"
          placeholder="Username"
          autocomplete="off"
          pattern="[a-zA-Z]{1}[a-zA-Z0-9]{1,9}"
          title="Username should begin with a letter with at most 10 characters consisting of letters and numbers."
          value="<?php if(isset($username)) echo $username; ?>"
          required>
      </div>
      <div class="form-group">
        <!-- <label for="exampleInputPassword1">Password</label> -->
        <input
          type="password"
          class="form-control"
          name="password"
          placeholder="Password"
          autocomplete="off"
          pattern=".{6,}"
          title="Password should be at least six characters."
          required>
      </div>
      <div class="form-group">
        <!-- <label for="exampleInputPassword1">Password</label> -->
        <input
          type="password"
          class="form-control"
          name="confirmpassword"
          placeholder="Confirm password"
          autocomplete="off"
          pattern=".{6,}"
          title="Password should be at least six characters."
          required>
      </div>
      <p class="text-danger">
        <?php foreach ($errors as $e) {
          echo $e . '<br>';
        } ?>
      </p>
      <input type="submit" name="submit" class="btn btn-outline-primary btn-sm"></input>
    </form>
  </div>
</div>
