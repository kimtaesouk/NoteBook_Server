<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);
// POST 데이터 가져오기
$email = $_POST["email"];
$plainPassword = $_POST["pw"];

// SQL 쿼리 작성
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

// 쿼리 실행
if ($stmt->execute()) {
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($pid, $email, $hashedPassword, $name, $birth, $sex, $identity, $profile_image);
        $stmt->fetch();

        // 비밀번호 비교
        if (password_verify($plainPassword, $hashedPassword)) {
            // 로그인 성공
            $response = array(
                "success" => true,
                "pid" => $pid,
                "email" => $email,
                "name" => $name,
                "birth" => $birth,
                "sex" => $sex,
                "identity" => $identity,
                "profile_image" => $profile_image,
            );
            echo json_encode($response);
        } else {
            // 로그인 실패
            $response = array("success" => false, "message" => "아이디와 비밀번호가 일치하지 않습니다.");
            echo json_encode($response);
        }
    } else {
        // 로그인 실패
        $response = array("success" => false, "message" => "아이디와 비밀번호가 일치하지 않습니다.");
        echo json_encode($response);
    }
} else {
    // 쿼리 실행 실패
    $response = array("success" => false, "message" => "쿼리 실행 실패.");
    echo json_encode($response);
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>

