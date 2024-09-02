<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

// POST 데이터 가져오기
$upid = $_POST["upid"];
// 기본 쿼리
$sql = "SELECT * FROM timer WHERE upid = '$upid'";

 // Complet 값에 따라 쿼리 부분을 추가
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
                "pid" => $row["pid"],
                "upid" => $row["upid"],
                "name" => $row["name"],
                "color" => $row["color"],
                "colorhex" => $row["colorhex"],
                "status" => $row["status"],
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
