<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$upid = $_POST["upid"];
$tpid = $_POST["tpid"];
$date = $_POST["selectedDate"];
$starttime = $_POST["starttime"];
$stoptime = $_POST["stoptime"];
$totalSeconds = $_POST["totalSeconds"];
// status 값
$status = 1;

// 데이터 유효성 검사 (예: 숫자인지, 날짜 형식인지 확인)
if (!is_numeric($upid) || !is_numeric($tpid) || empty($date) || empty($starttime) || empty($stoptime) || !is_numeric($totalSeconds)) {
    $response = array("success" => false, "message" => "데이터가 올바르지 않습니다.");
    echo json_encode($response);
    exit;
}

// SQL 쿼리 작성 (테이블 이름과 필드 이름을 실제 데이터베이스에 맞게 수정해야 합니다)
$sql = "INSERT INTO time (upid, tpid, starttime, stoptime, totalSeconds, date, status) VALUES (?, ?, ?, ?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissssi", $upid, $tpid, $starttime, $stoptime, $totalSeconds, $date, $status);

// 쿼리 실행
if ($stmt->execute()) {
    $response = array("success" => true);
    echo json_encode($response);
} else {
    $response = array("success" => false, "message" => "데이터베이스 오류: " . $conn->error);
    echo json_encode($response);
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();

?>
