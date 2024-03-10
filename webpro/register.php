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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/datepicker.min.js"></script>
    <style>
        body {
            font-family: 'IBM Plex Sans Thai', sans-serif;
        }
    </style>

    <script>
        function retry() {
            var element = document.getElementById("error");
            element.remove();
            window.location = window.location.href;

        }
    </script>
    <?php
    require_once("dbconfig.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $bod = $_POST['bod'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $token = bin2hex(random_bytes(32));
        $expirationTimestamp = date("Y-m-d H:i:s", strtotime("+24 hours"));
        $role = $_POST['role'];
        $hashedPassword = hash('sha256', $password);
        $sql = "SELECT email,  COUNT(*) as count FROM $role WHERE email = '$email'";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        if ($result['count'] > 0) {
            echo '<div id ="error" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                        email นี้ถูกใช้ไปแล้ว</h3>
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
        } else {

            $sql = "INSERT INTO $role (email, password,fname,lname,gender,dob,address,phone, token, expire_token) VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssss", $email, $hashedPassword, $fname, $lname, $gender, $bod, $address, $phone, $token, $expirationTimestamp);

            if ($stmt->execute()){
                if ($role == 'organizer') {
                    $sql = "SELECT org_id FROM organizer where email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $org_id = $result->fetch_assoc()['org_id'];

                    $hashedPassword = hash('sha256', $_POST['password2']);
                    $token = bin2hex(random_bytes(32));
                    $sql = "INSERT INTO sales_marketing (email, password, token, expire_token,org_id) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssi", $_POST['email2'], $hashedPassword, $token, $expirationTimestamp, $org_id);
                    $stmt->execute();
                }
                header("Location: login.php");
                exit();
            } else {
                echo "<div id ='error' class='relative z-10' aria-labelledby='modal-title' role='dialog' aria-modal='true'>
            <div class='fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity'></div>
            <div class='fixed inset-0 z-10 w-screen overflow-y-auto'>
                <div class='flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0'>
                    <div
                        class='px-4 pb-4 pt-5 sm:p-6 sm:pb-4 relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-fit sm:max-w-lg'>
                        <div class='bg-white '>
                            <div class='sm:flex sm:items-start'>
                                <div
                                    class='mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10'>
                                    <svg class='h-6 w-6 text-red-600' fill='none' viewBox='0 0 24 24' stroke-width='1.5'
                                        stroke='currentColor' aria-hidden='true'>
                                        <path stroke-linecap='round' stroke-linejoin='round'
                                            d='M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' />
                                    </svg>
                                </div>
                                <div class='mt-3 text-center sm:ml-4  sm:text-left'>
                                    <h3 class='text-base font-semibold leading-6 text-gray-900' id='modal-title'>
                                        ERROR</h3>
                                </div>
                            </div>
                        </div>
                        <div class='bg-gray-50 px-4 pt-4 flex justify-center sm:flex sm:flex-row-reverse sm:px-6'>
                            <button type='button' onclick = 'retry()'
                                class='inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto'>
                                ลองอีกครั้ง
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
            }
            $stmt->close();
        }


    }
    ?>
</head>


<body style="background-color: #071952;">
    <div id='check_error'></div>
    <form method="POST" action='register.php' id='myform'>
        <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-white">สมัครสมาชิก</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm gap-y-3 flex flex-col">
                <div>
                    <label for="email" class="block text-sm font-medium leading-6 text-white">ชื่อผู้ใช้
                        (อีเมล)</label>
                    <div class="mt-2">
                        <input required id="email" name="email" type="email" autocomplete="email" required
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium leading-6 text-white">รหัสผ่าน</label>
                    </div>
                    <div class="mt-2">
                        <input required id="password" name="password" type="password"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="confirmpassword"
                            class="block text-sm font-medium leading-6 text-white">ยืนยันรหัสผ่าน</label>
                    </div>
                    <div class="mt-2">
                        <input required id="confirmpassword" name="confirmpassword" type="password"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="fname" class="block text-sm font-medium leading-6 text-white">ชื่อ</label>
                    </div>
                    <div class="mt-2">
                        <input required id="fname" name="fname" type="name"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="lname" class="block text-sm font-medium leading-6 text-white">นามสกุล</label>
                    </div>
                    <div class="mt-2">
                        <input required id="lname" name="lname" type="lastname"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label for="lastname" class="block text-sm font-medium leading-6 text-white">เพศ</label>
                </div>
                <div class="text-white" style="display: flex; gap: 10px;">
                    <div>
                        <input required type="radio" id="male" name="gender" value="male">
                        <label for="male">ผู้ชาย</label>
                    </div>
                    <div>
                        <input required type="radio" id="female" name="gender" value="female">
                        <label for="female">ผู้หญิง</label>
                    </div>
                    <div>
                        <input required type="radio" id="notspecified" name="gender" value="notspecified">
                        <label for="notspecified">ไม่ระบุ</label>
                    </div>
                </div>

                <div class="relative max-w-sm">
                    <input required name="bod" id='bod' type="date" type="text" max="<?php
                    $cu = new DateTime();
                    echo $cu->format('Y-m-d') ?>" class="border-solid border-2 rounded-lg p-2 text-lg w-full "
                        placeholder="Select date">
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="addr" class="block text-sm font-medium leading-6 text-white">ที่อยู่</label>
                    </div>
                    <div class="mt-2">
                        <input required id="address" name="address" type="address"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="phone"
                            class="block text-sm font-medium leading-6 text-white">หมายเลขโทรศัพท์</label>
                    </div>
                    <div class="mt-2">
                        <input required id="phone" name="phone" type="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div class="text-white flex gap-3">
                    <div>
                        <input required type="radio" id="booker" name="role" value="customer">
                        <label for="booker">ผู้จองบัตรเข้าชม</label>
                    </div>
                    <div>
                        <input required type="radio" id="organizer" name="role" value="organizer">
                        <label for="organizer">ผู้จัดงาน</label>
                    </div>
                </div>
                <div id='sale' class='flex flex-col gap-y-3'>

                </div>
                <div>
                    <button type="button" onclick="register()"
                        class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">สมัครสมาชิก</button>
                    <button id='check' type='submit' style='display:none'></button>
                </div>
            </div>
        </div>
    </form>
    <script>
        var role = document.querySelectorAll('[name="role"]');
        var sale = document.getElementById('sale');

        role.forEach(e => {
            e.addEventListener('change', function () {
                if (e.value === 'organizer') {
                    sale.innerHTML = `
                    <div>
                    <label for="email2" class="block text-sm font-medium leading-6 text-white">ชื่อผู้ใช้ของฝ่ายจัดขาย
                        (อีเมล)</label>
                    <div class="mt-2">
                        <input required id="email2" name="email2" type="email" autocomplete="email" required
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="password2" class="block text-sm font-medium leading-6 text-white">รหัสผ่านของฝ่ายจัดขาย</label>
                    </div>
                    <div class="mt-2">
                        <input required id="password2" name="password2" type="password"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <label for="confirmpassword2"
                            class="block text-sm font-medium leading-6 text-white">ยืนยันรหัสผ่านของฝ่ายจัดขาย</label>
                    </div>
                    <div class="mt-2">
                        <input required id="confirmpassword2" name="confirmpassword2" type="password"
                            class="block w-full rounded-md border-0 p-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                </div>
                `;
                } else {
                    sale.innerHTML = ''; // Clear the content when "customer" is selected
                }
            });
        });
        
        function register() {
            var check = false
            if (document.getElementById('password').value !== document.getElementById('confirmpassword').value) {
                var check = true
            }
            else if (role == 'organizer') {
                if (document.getElementById('password2').value !== document.getElementById('confirmpassword2').value) {
                    var check = true
                }
            }
            if (check) {
                let error = document.getElementById('check_error')
                document.body.innerHTML = `<div id ="error" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
                                        รหัสผ่านไม่ตรงกัน</h3>
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
        </div>`
            }
            else {
                document.getElementById('check').click();
            }

        }
    </script>

</body>

</html>