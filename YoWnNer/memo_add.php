<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$memo = $_POST["memo"];
$date = $_POST["date"];
$upid = $_POST["upid"];
$year = $_POST["year"];
$month = $_POST["month"];
$day = $_POST["day"];

// status 값
$status = 1;

// SQL 쿼리 작성 (테이블 이름과 필드 이름을 실제 데이터베이스에 맞게 수정해야 합니다)
$sql = "INSERT INTO memo (upid, memo, year, month, day, date, status, created) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $upid, $memo, $year, $month, $day, $date, $status);

// 쿼리 실행
if ($stmt->execute()) {
    $response = array("success" => true, "message" => "메모 생성 성공.");
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "메모 생성 실패.");
    echo json_encode($response);
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>
