<?php
require_once("db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// POST 데이터 가져오기
$pid = $_POST["upid"];
$name = $_POST["name"];
$status = '1';
$personnel = '50';

// 현재 시간과 날짜 가져오기
date_default_timezone_set('Asia/Seoul'); // 시간대 설정 (필요에 따라 변경)
$currentTime = date('Y-m-d H:i:s');

// PID를 JSON 형식으로 변환
$pidJson = json_encode(array($pid));

// SQL 쿼리 작성
$sql = "INSERT INTO study (name, master, Participant, created, status,personnel) VALUES (?, ?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $pid, $pidJson, $currentTime, $status, $personnel);
// status 값 '1'로 설정
// 쿼리 실행
if ($stmt->execute()) {
    // 쿼리 실행 성공
    $response = array(
        "success" => true,
        "message" => "데이터가 성공적으로 추가됨"
    );
} else {
    // 쿼리 실행 실패
    $response = array(
        "success" => false,
        "message" => "데이터 추가 실패"
    );
}

echo json_encode($response);

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
