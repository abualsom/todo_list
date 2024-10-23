<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./styles.css">
    <title>Todo_List</title>
</head>
<body>
    <div class="form-container">
        <form class="form" action="" method="post">
            <input class="tex" type="text" name="Todo_List" placeholder="المهمة الجديدة " required>
            <button class="tex" type="submit">أضف </button>
        </form>

        <?php 
        // الاتصال بقاعدة البيانات
        $serverName = "ALGURABAA_986"; 
        $database = "todo_web"; 
        $uid = ""; 
        $pass = ""; 

        $connection = [
            "Database" => $database, 
            "Uid" => $uid , 
            "PWD" => $pass,
            "CharacterSet" => "UTF-8"
        ]; 

        $conn = sqlsrv_connect($serverName ,$connection);
        if(!$conn) {
            die(print_r(sqlsrv_errors(), true));
        } 

        // إذا تم إرسال النموذج

        // إذا تم إرسال النموذج
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['Todo_List'])) {
                $task = trim($_POST['Todo_List']); // إزالة الفراغات الزائدة
        
                // التأكد من أن المهمة ليست فارغة
                if (!empty($task)) {
                    // استعلام لإضافة المهمة إلى قاعدة البيانات
                    $sqlInsert = "INSERT INTO task (task, status) VALUES (?, 'pending')";
                    $params = [$task];
                    $stmtInsert = sqlsrv_query($conn, $sqlInsert, $params);
        
                    if ($stmtInsert === false) {
                        die(print_r(sqlsrv_errors(), true));
                    } else {
                        header("Location: index.php"); // إعادة التوجيه بعد الإدخال
                        exit();
                    }
                } else {
                    echo "يرجى إدخال مهمة.<br>";
                }
            } // تأكد من أن هذا القوس يغلق if (isset($_POST['Todo_List']))
        } // تأكد من أن هذا القوس يغلق if ($_SERVER['REQUEST_METHOD'] == 'POST')

        

            // التحقق من طلب المسح
            if (isset($_POST['delete_id'])) {
                $deleteId = $_POST['delete_id'];
                $sqlDelete = "DELETE FROM task WHERE id = ?";
                $params = [$deleteId];
                $stmtDelete = sqlsrv_query($conn, $sqlDelete, $params);

                if ($stmtDelete === false) {
                    die(print_r(sqlsrv_errors(), true));
                } else {
                    echo "تم مسح المهمة بنجاح!<br>";
                }
            }

        // استرجاع وعرض المهام
        $sql = "SELECT id, task FROM task"; // تأكد من جلب الـ ID
        $stmt = sqlsrv_query($conn, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // عرض النتائج
        // echo "<ol>";
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            // echo "<li>";
            echo "<div class='divo'>" . htmlspecialchars($row['task']) . "</div>"; // عرض المهمة
            echo "<form action='' method='post' style='margin-left: 10px; display: inline;'>"; // إضافة نموذج للمسح
            echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
            echo "<button class='miso' type='submit'>أنهيت المهمة  </button>";
            echo "</form>";
            // echo "</li>"; // نهاية القائمة
        }
        echo "</ol>";
        











        // إغلاق الاتصال
        sqlsrv_free_stmt($stmt);
        sqlsrv_close($conn);
        ?> 
    </div>
</body>
</html>
