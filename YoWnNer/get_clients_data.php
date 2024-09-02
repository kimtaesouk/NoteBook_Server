<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$upid = $_POST["upid"];

// SQL 쿼리 작성
$sql = "SELECT name, identity FROM user WHERE pid = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $upid);

// 쿼리 실행
if ($stmt->execute()) {
    $result = $stmt->get_result();

    // 결과 행을 배열로 저장
    $response = array();

    while ($row = $result->fetch_assoc()) {
        $response[] = array(
            "name" => $row["name"],
            "identity" => $row["identity"]
        
        );
    }

    if (!empty($response)) {
        // 결과가 존재하는 경우
        echo json_encode(array("success" => true, "data" => $response));
    } else {
        // 결과가 없는 경우
        echo json_encode(array("success" => false, "message" => "결과가 없습니다."));
    }
} else {
    // 쿼리 실행 실패
    echo json_encode(array("success" => false, "message" => "쿼리 실행 실패."));
}

// 데이터베이스 연결 종료
$stmt->close();
$conn->close();
?>