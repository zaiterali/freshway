<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


// session_destroy();
session_start();

include "db.php";



if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data)
    {

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;
    }

    $uname = validate($_POST['username']);

    $pass = validate($_POST['password']);

    if (empty($uname)) {


        header("Location: ../login.php?erroruser=User Name is required");
        exit();
    } else if (empty($pass)) {

        header("Location: ../login.php?errorpass=Password is required");
        exit();
    } else {

        $sql = "SELECT * FROM users WHERE username='$uname' AND password='$pass' AND isactive = 1";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['username'] === $uname && $row['password'] === $pass) {

                echo "Logged in!";

                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['firstname'];
                $_SESSION['roleId'] = $row['type'];
                $_SESSION['id'] = $row['id'];

                if ($row['type'] == 1 || $row['type'] == 2 || $row['type'] == 3) {
                    header("Location:../html/index.php");
                } elseif ($row['type'] == 2 || $row['type'] == 9) {
                    // header("Location:../html/pmanager.php");
                } elseif ($row['type'] == 1) {
                    // header("Location:../html/bmanager.php");
                } elseif ($row['type'] == 3 || $row['type'] == 4 || $row['type'] == 5 || $row['type'] == 6 || $row['type'] == 7) {
                    // header("Location:../html/warehousepanel.php");
                }
                exit();
            } else {

                header("Location: ../login.php?error=Incorect User name or password");

                exit();
            }
        } else {

            header("Location: ../login.php?error=Incorect User name or password");

            exit();
        }
    }
} else {
    header("Location:../login.php");
    exit();
}
