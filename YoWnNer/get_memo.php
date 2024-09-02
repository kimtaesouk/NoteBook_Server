<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// POST 데이터 가져오기
$upid = $_POST["pid"];
$year = isset($_POST["year"]) ? $_POST["year"] : "";
$month = isset($_POST["month"]) ? $_POST["month"] : "";
$day = isset($_POST["day"]) ? $_POST["day"] : "";
$date = isset($_POST["date"]) ? $_POST["date"] : "";
$Complet = isset($_POST["Complet"]) ? $_POST["Complet"] : "";
// 기본 쿼리
$sql = "SELECT pid, memo, month, day, status FROM memo WHERE upid = '$upid'";

    
    
    // Complet 값에 따라 쿼리 부분을 추가
 if ($Complet == "Complet") {
    $sql .= " AND status = 2 AND month = '$month' AND year = '$year'";
 } elseif ($Complet == "inComplet") {
     $sql .= " AND status = 1 AND month = '$month' AND year = '$year'";
 } else {
        // Complet 값이 없거나 다른 값이 들어온 경우, 기본 쿼리에 날짜 조건 추가
     if ($date != 'All') {
         $sql .= " AND date = '$date' AND status != 0";
    }
}
// SQL 쿼리 실행을 준비합니다.
$stmt = $conn->prepare($sql);

if (!$stmt) {
    // 쿼리 준비에 실패한 경우
    $response = array("get_memo success" => false, "message" => "SQL Query Preparation Error: " . $conn->error);
    echo json_encode($response);
} else {
    // 매개변수를 바인딩합니다.

    // 쿼리 실행
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $items = array();
        while ($row = $result->fetch_assoc()) {
            $item = array(
                "memo" => $row["memo"],
                "pid" => $row["pid"],
                "status" => $row["status"],
                "month" => $row["month"],
                "day" => $row["day"],
            );
            $items[] = $item;
        }
        echo json_encode($items);
    }
    // SQL 문 종료
    $stmt->close();
}

// 데이터베이스 연결 종료
$conn->close();
?>
