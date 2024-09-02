<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$upid = $_POST["pid"];
$selectedDate = $_POST["selectedDate"];

// SQL 쿼리 작성
$sql = "SELECT SUM(totalSeconds) AS Seconds FROM time WHERE upid = ? AND date = ? ";

// SQL 쿼리 실행을 준비합니다.
$stmt = $conn->prepare($sql);

// 매개변수를 바인딩합니다.
$stmt->bind_param("ss", $upid, $selectedDate);

// 쿼리 실행
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $item = array(
                "Seconds" => $row["Seconds"],
            );
            array_push($items, $item);
        }
        $response = array("success" => true, "data" => $items);
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "데이터가 없습니다.");
        echo json_encode($response);
    }
} else {
    // 쿼리 실행 실패
    $response = array("success" => false, "message" => "쿼리 실행 실패: " . mysqli_error($conn));
    echo json_encode($response);
}

// SQL 문 종료
$stmt->close();

// 데이터베이스 연결 종료
$conn->close();
?>
