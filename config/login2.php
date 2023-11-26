<?php
// code for clients login 
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


        header("Location: ../client.php?erroruser=User Name is required");
        exit();
    } else if (empty($pass)) {

        header("Location: ../client.php?errorpass=Password is required");
        exit();
    } else {

        $sql = "SELECT * FROM clients WHERE username='$uname' AND password='$pass' AND isactive = 1";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);

            if ($row['username'] === $uname && $row['password'] === $pass) {

                echo "Logged in!";

                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['companyname'];
                $_SESSION['contact'] = $row['contactperson'];
                $_SESSION['id'] = $row['id'];

                header("Location:../html/clientdash.php");
            } else {

                header("Location: ../client.php?error=Incorect User name or password");

                exit();
            }
        } else {

            header("Location: ../client.php?error=Incorect User name or password");

            exit();
        }
    }
} else {
    header("Location:../client.php");
    exit();
}
