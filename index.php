<?php
require_once('includes/constants.php');
require_once ('includes/functions.php');

// If user already authenticated fwd to dashboard
if(isAuthenticated()) {
    header("Location: dashboard.php");
}

$error_msg = "";

if(isset($_POST["password"]) && isset($_POST['email_address'])) {
    $email_address = $_POST['email_address'];
    $password = $_POST['password'];
    $password = hash('sha512', $password);
    $connection = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
    if($connection->connect_error) {
        die("Connection failed:" . $connection->connect_error);
    }

    $query = "SELECT first_name, last_name from user where email_address='" . $email_address ."' AND password='" . $password ."'";
    $result = mysqli_query($connection, $query) or die(mysqli_error($con) . "<br />The query is<br />" . $query);

    if (0 < $result->num_rows) {
      $row = $result->fetch_assoc();
        $_SESSION['authenticated'] = 1;
        $_SESSION['name'] = $row['first_name'] . " " . $row['last_name'];
        header("Location: dashboard.php");
    } else {
        $error_msg = "Invalid username and/or password.";
    }

}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Login - <?php echo $TITLE; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Hey there!</h1>
                  </div>
                  <form class="user" method="post">
                      <div class="alert alert-danger" id="error_msg"
                          <?php
                          if(isset($_POST["email_address"]) && $error_msg != "") {
                              echo "> ".$error_msg;
                          } else {
                              echo ' style="display:none;">';
                          }
                          ?>
                      </div>
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="email_address" aria-describedby="emailHelp" placeholder="Enter Email Address...">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <hr>
                  
                </div>
                  <footer class="sticky-footer bg-white">
                      <div class="container my-auto">
                          <div class="copyright text-center my-auto">
                              <span><?php echo $FOOTER;?></span>
                          </div>
                      </div>
                  </footer>
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
