<?php
/*需要使用pdo方式连接*/
session_start();

$dbConnection=new PDO("mysql:host=localhost:3306;dbname=library","root","19951102");
$dbConnection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//include('conn.php');

if(!empty($_POST['login'])){
    $Auditor_ID=$_POST["Auditor_ID"];
    $pwd=$_POST["password"];
    $stmt = $dbConnection->prepare("select * from auditor where Auditor_id=:Auditor_ID and password=:pwd");
    $stmt->bindParam(':Auditor_ID', $Auditor_ID);
    $stmt->bindParam(':pwd', $pwd);
    $stmt->execute();


    if($stmt->fetch()){
        $_SESSION['check']=$_POST['Auditor_ID'];
        $_SESSION['pass']=$_POST['password'];
        $_SESSION['checked'] = true;
        $url = "http://tx.zhelishi.cn/audit/index.html";   //跳转的路径
        echo "<script language='javascript'
        type='text/javascript'>";
        echo "window.location.href='$url'";
        echo "</script>";
    }
    else{
        $_SESSION['checked'] = false;
        ?>
        <script language="javascript">alert("login failed")
         location.href="http://tx.zhelishi.cn/audit/login.html";</script>
<?php
    }
}
?>