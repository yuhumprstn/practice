<?php

session_start(); 


$conn = new mysqli("localhost", "root", "", "quiz_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['restart'])) {
    session_destroy();        
    header("Location: q.php"); 
    exit();                    
}

if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

$sql = "SELECT * FROM questions ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);
$question = $result->fetch_assoc(); 

$status = "Pumili ng tamang sagot!";
if (isset($_GET['choice'])) {
    if ($_GET['choice'] === $_GET['correct']) {
        $_SESSION['score'] += 10; 
        $status = "<b style='color:green;'>TAMA! +10 Points</b>";
    } else {
        $status = "<b style='color:red;'>MALI! Subukan ulit.</b>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>quiz</title>
    <style>
        body { font-family: sans-serif; text-align: center; background: #f4f4f4; padding-top: 50px; }
        .card { width: 350px; background: white; border: 3px solid #000; margin: 0 auto; padding: 20px; border-radius: 10px; box-shadow: 8px 8px 0px #000; }
        
        .img-box { width: 100%; height: 180px; border: 2px solid #000; margin: 15px 0; background: #eee; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .img-box img { max-width: 100%; max-height: 100%; }
        
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        
        .btn { padding: 15px; border: 2px solid #000; text-decoration: none; color: black; font-weight: bold; background: #fff; font-size: 14px; }
        .btn:hover { background: #000; color: #fff; }

        .restart-btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #ff4d4d; color: white; text-decoration: none; font-weight: bold; border: 2px solid #000; border-radius: 5px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>Score: <?php echo $_SESSION['score']; ?></h2>

        <p><b><?php echo $question['question_text']; ?></b></p>

        <div class="img-box">
            <img src="<?php echo $question['image_url']; ?>" alt="Quiz Image">
        </div>

        <div class="grid">
            <?php 
            $correct = $question['correct_answer']; 
            
            echo "<a href='?choice={$question['option1']}&correct=$correct' class='btn'>{$question['option1']}</a>";
            echo "<a href='?choice={$question['option2']}&correct=$correct' class='btn'>{$question['option2']}</a>";
            echo "<a href='?choice={$question['option3']}&correct=$correct' class='btn'>{$question['option3']}</a>";
            echo "<a href='?choice={$question['option4']}&correct=$correct' class='btn'>{$question['option4']}</a>";
            ?>
        </div>

        <p class="status-msg"><?php echo $status; ?></p>
    </div>

    <a href="?restart=1" class="restart-btn">RESTART GAME</a>

</body>
</html>