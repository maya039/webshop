<?php

session_start();

require "class/db.php";

$db = new Database();
if ( $_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST["password"];
    $username = $_POST["username"];
    $username = htmlspecialchars($username);
    if ( empty($password) || empty($username)) {
        $status = "Missing username or password.";
    } else {
        // log in user
        if ( authenticateUser($username,$password,$db)){
            $_SESSION["username"] = $username;
            $_SESSION["loginCounter"]= 0;
            $randomtoken = base64_encode( openssl_random_pseudo_bytes(32));
            $_SESSION["CSRF_token"] = $randomtoken;
            header("Location: user.php");
        }else {
          // Log in failed
            $status = "Wrong username or password";
            $_SESSION["loginCounter"] = $_SESSION["loginCounter"] +1;
            if($_SESSION["loginCounter"] == 2){
              $status = "Login failed 2 times. Next failed login attempt will
               lead to a 5s wait before you can try to login again.";
            }
            // Resets counter
            if($_SESSION["loginCounter"] >= 3){
              $_SESSION["loginCounter"]= 0;
              sleep(5);

            }
        }
    }
}
?>

<?php require("inc/header.php") ?>

<h1>Login</h1>
<div class="center-text">
    <form action="" method="post">
        <table class="table-login" >
            <tr>
                <td>
                    <label for="username">UserName :</label>
                </td>
                <td>
                    <input type="text" name="username"/><br />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password">Password :</label>
                </td>
                <td>
                    <input type="password" name="password"/><br/>
                </td>
            </tr>
        </table>

        <input type="submit" value=" Submit "/><br />


        <?php if ( isset($status) ) : ?>
            <?= $status ;?>
        <?php endif ?>
    </form>

</div>
<?php require("inc/footer.php") ?>
