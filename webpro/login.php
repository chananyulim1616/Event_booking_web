<script>
    function retry() {
        var element = document.getElementById("error");
        element.remove();
    }
</script>
<?php
session_start();

require_once("dbconfig.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $remember = isset($_POST['remember']) ? $_POST['remember'] : 'false';
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST['role'];

    if ($role == 'customer') {
        $sql = "SELECT user_id, email,fname, password, token, expire_token FROM $role WHERE email = ?";
    } else if($role == 'organizer'){
        $sql = "SELECT org_id, email, password, token, expire_token FROM $role WHERE email = ?";
    }else{
        $sql = "SELECT sales_id, email, password, token, expire_token FROM $role WHERE email = ?";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row["password"];
        if($role == 'customer'){
            $id = $row["user_id"];
        }else if ($role == 'organizer'){
            $id = $row["org_id"];
        }else{
            $id = $row["sales_id"];
        }
        
        if (hash('sha256', $password) == $storedPassword) {
            // Generate a new token every time the user logs in
            $newToken = bin2hex(random_bytes(32));
            $newExpirationTimestamp = date("Y-m-d H:i:s", strtotime("+24 hours"));
            
            // Update the user's token and expiration timestamp in the database
            $updateSql = "UPDATE $role SET token = ?, expire_token = ? WHERE email = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sss", $newToken, $newExpirationTimestamp, $email);
            $updateStmt->execute();
            $updateStmt->close();

            // Set the new token in the session
            $_SESSION["user_token"] = $newToken;

            if($role == 'customer'){
            $_SESSION["user_username"] = $row["fname"];
            }
            $_SESSION["role"] = $role;
            $_SESSION["id"] = $id;
            if($remember){
                setcookie('user_token',$newToken, time() + (86400));
                setcookie('role',$role, time() + (86400));
            }
            if ($_SESSION["role"] == 'customer') {
                header("Location: index.php");
                exit();
            }else if($_SESSION["role"] == 'organizer'){
                header("Location: index_org.php");
                exit();
                }else{
                header("Location: index_sales.php");
                exit();
            }
        } else {
            echo ' <div id ="error" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
    
                    <div
                        class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-fit sm:max-w-lg">
                        <div class="bg-white ">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4  sm:text-left">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                        กรุณาตรวจสอบความถูกต้อง</h3>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 pt-4 flex justify-center sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" onclick = "retry()"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                                ลองอีกครั้ง
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
        }
    } else {
        echo ' <div id ="error" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">

                <div
                    class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-fit 
                    sm:max-w-lg">
                    <div class="bg-white ">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4  sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                    ไม่มีผู้ใช้นี้ในระบบ</h3>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 pt-4 flex justify-center sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick = "retry()"
                            class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            ลองอีกครั้ง
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTicket</title>
    <link rel="icon" type="image/x-icon" href="img/lillogo.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>
</head>

<body style="background-color: #071952;">
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-white">เข้าสู่ระบบ</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
            <form class="space-y-5" action="login.php" method="POST">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-white">ชื่อผู้ใช้ (อีเมล)</label>
                    <div class="mt-2">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-white">รหัสผ่าน</label>
                    </div>
                    <div class="mt-2">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                
                <div class="text-white flex flex-wrap gap-3 sm:gap:5">
                    <div class='flex gap-1'>
                    <input type="radio" id="booker" name="role" value="customer" required>
                    <label for="booker">ผู้จองบัตรเข้าชม</label>
                    </div>
                    <div class='flex gap-1'>
                    <input type="radio" id="organizer" name="role" value="organizer" required>
                    <label for="organizer">ผู้จัดงาน</label>
                    </div>
                    <div class='flex gap-1'>
                    <input type="radio" id="organizer" name="role" value="sales_marketing" required>
                    <label for="sales">ฝ่ายขายและการตลาด</label>
                    </div>
                </div>
                <div class='text-white flex gap-2 items-center'>
                    <input type="checkbox" name='remember' value="true" id="remember">
                    <label for="remember">จำสถานะการเข้าสู่ระบบ</label>
                </div>
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">เข้าสู่ระบบ</button>
                </div>
            </form>

            <p class="mt-10 text-center text-sm text-gray-500">
                หากท่านยังไม่ได้เป็นสมาชิก
                <a href="register.php"
                    class="font-semibold leading-6 text-indigo-600 hover:text-white">กรุณาสมัครสมาชิก</a>
            </p>
        </div>
    </div>

</body>

</html>