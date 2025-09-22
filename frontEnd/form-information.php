<!DOCTYPE html>

<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างบัญชีใหม่</title>
</head>
<body>
    <h1>Information</h1>
    <form action="process-information.php" method="POST">
        <div>
            <input name="first_name" type="text" placeholder="ชื่อ" required>
        </div>
        <div>
            <input name="last_name" type="text" placeholder="นามสกุล" required>
        </div>
        <div>
            <input name="email_account" type="email" placeholder="อีเมล" required>
        </div>
        <div>
            <input name="phone_number" type="tel" placeholder="เบอร์โทรศัพท์" required>
        </div>
        <div>
            <input name="password_account1" type="password" placeholder="รหัสผ่าน" required>
        </div>
         <div>
            <input name="password_account2" type="password" placeholder="ยืนยันรหัสผ่าน" required>
        </div>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
