<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$email = $_POST["email"];
$raw_password = $_POST["pw"]; // 사용자가 입력한 원시 비밀번호

// 비밀번호를 해시로 변환
$password = password_hash($raw_password, PASSWORD_DEFAULT);

$name = $_POST["name"];
$birth = $_POST["birth"];
$sex = $_POST["sex"];
$identity = $_POST["identity"];
$profile_image = $_POST["profile_image"];

// SQL 쿼리 작성 (테이블 이름과 필드 이름을 실제 데이터베이스에 맞게 수정해야 합니다)
$sql = "INSERT INTO user (email, pw, name, birth, sex, identity, profile_image) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $email, $password, $name, $birth, $sex, $identity, $profile_image);

// 쿼리 실행
if ($stmt->execute()) {
    $response = array("success" => true, "message" => "회원가입에 성공했습니다.");
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "회원가입에 실패했습니다.");
    echo json_encode($response);
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
