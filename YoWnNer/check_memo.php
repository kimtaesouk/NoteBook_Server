<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$pid = $_POST["pid"];

// isChecked 값을 문자열 "true" 또는 "false"로 받을 수 있으므로, 불리언 값으로 변환합니다.
$isChecked = filter_var($_POST["isChecked"], FILTER_VALIDATE_BOOLEAN);

// SQL 쿼리 작성
if ($isChecked === true) {
    $status = 1;
} else {
    $status = 2;
}

$sql = "UPDATE memo SET status = ? WHERE pid = ?";
$stmt = $conn->prepare($sql); // stmt 객체 초기화
$stmt->bind_param("is", $status, $pid);

// 쿼리 실행
if ($stmt->execute()) {
    // 성공적으로 업데이트됨
    $response = array("success" => true, "message" => "데이터가 업데이트되었습니다.");
    echo json_encode($response);
} else {
    // 업데이트 실패
    $response = array("success" => false, "message" => "데이터 업데이트 실패.");
    echo json_encode($response);
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();

?>
