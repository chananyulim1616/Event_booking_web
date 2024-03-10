<?php
$db_host = "localhost";
$db_username = "S044ONUJ";
$db_password = "4323PEM516";
$db_name = "booking_concert";

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
date_default_timezone_set("Asia/Bangkok");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_token"])) {
    if (isset($_COOKIE['user_token']) && isset($_COOKIE['role'])) {
        $role = $_COOKIE['role'];
        if ($role == 'customer') {
            $sql = "SELECT user_id, email, fname, password, token, expire_token FROM $role WHERE token = ?";
        } else if($role == 'organizer'){
            $sql = "SELECT org_id, email, password, token, expire_token FROM $role WHERE token = ?";
        }else{
            $sql = "SELECT sales_id, email, password, token, expire_token FROM $role WHERE token = ?";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_COOKIE['user_token']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($role == 'customer') {
                $id = $row["user_id"];
            }else if ($role == 'organizer'){
                $id = $row["org_id"];
            }else{
                $id = $row["sales_id"];
            }
            $_SESSION["user_token"] = $_COOKIE['user_token'];
            if($role == 'customer'){
                $_SESSION["user_username"] = $row["fname"];
                }
            $_SESSION["role"] = $role;
            $_SESSION["id"] = $id;

        } else {
            session_destroy();
            setcookie('user_token', '', time() - 3600);
            setcookie('role', '', time() - 3600);
            header("Location: login.php");
            exit();
    }
    }
}

function checklogin($conn, $return, $role)
{
    if (isset($_SESSION["user_token"])) {
        if ($_SESSION['role'] == $role) {
            if ($_SESSION['role'] == 'customer') {
                $sql = "SELECT user_id, token, expire_token FROM " . $_SESSION['role'] . " WHERE token = ?";
            } else if($_SESSION['role'] == 'organizer') {
                $sql = "SELECT org_id, token, expire_token FROM " . $_SESSION['role'] . " WHERE token = ? ";
            }else{
                $sql = "SELECT sales_id, token, expire_token FROM " . $_SESSION['role'] . " WHERE token = ? ";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION["user_token"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if ($result->num_rows == 1) {
                $timestamp = date("Y-m-d H:i:s");
                if ($row['expire_token'] > $timestamp) {
                    $check = true;
                } else {
                    $check = false;
                }
            } else {
                $check = false;
            }
        } else {
            $check = false;
        }
    } else {
        $check = false;
    }
    if (!$check and $return) {
        header("Location: login.php");
        exit();
    } else {
        return $check;
    }
}?>
