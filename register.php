<?php require "include/header.php" ?>
<?php
$errors = "";
if($_SERVER['REQUEST_METHOD']=='POST'){
  $account = new User();
  $account->username = $_POST['user'];
  $account->password = $_POST['pass'];
  $repass = $_POST['repass'];
  
  if($account->password != $repass){
    $errors = "Re-Password does not match!";
  }else{
    $errors = $account->insert($pdo);
  }
  
}  
?>
<div class="container-fluid mt-5 p-5" style="background-color: #F4F0EB;">
<div class="box p-5 bg-white rounded-4 m-auto">
        <h3 class="text-center">Register</h3>
        <br>
        <br>
        <form method="post">
            <div class="mb-4 inputbox">
                <input type="text" name="user"  class="form-control" required />
                <label for="">Username</label>
              </div>
          
            <div class="mb-4 inputbox">
              <input type="password"  name="pass"  class="form-control" required />
              <label for="">Password</label>
            </div>

            <div class="mb-4 inputbox">
              <input type="password"  name="repass"  class="form-control" required />
              <label for="">Re-Password</label>
            </div>
          
            <button type="submit" class="btn btn-primary btn-block mb-4 form-control rounded-pill">Register</button>
          
            <div class="text-center">
              <p>Have an account? <a href="login.php">Sign in</a></p>
            </div>
        </form>
        <p class="text-center text-danger"><?= $errors?></p>
    </div>
</div>
<?php require "include/footer.php" ?>