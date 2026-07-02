<?php
session_start();

include "../config/database.php";

$error = "";

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin
            WHERE username='$username'
            AND password='$password'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1)
    {
        $_SESSION['admin'] = $username;

        header("Location: admin-dashboard.php");
        exit();
    }
    else
    {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f5f5f5;
}

.login-box{
    max-width:450px;
    margin:100px auto;
    background:white;
    padding:40px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,.1);
}
</style>

</head>
<body>

<div class="login-box">

<h2 class="text-center mb-4">
Admin Login
</h2>

<?php if($error!=""){ ?>

<div class="alert alert-danger">
<?php echo $error; ?>
</div>

<?php } ?>

<form method="post">

<div class="mb-3">
<label>Username</label>
<input type="text"
name="username"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password"
name="password"
class="form-control"
required>
</div>

<button
type="submit"
name="login"
class="btn btn-primary w-100">
Login
</button>

</form>

</div>

</body>
</html>