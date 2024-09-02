<?php
// 데이터베이스 연결 코드를 포함합니다.
require_once("db_connect.php");

// POST 데이터 가져오기
$pid = $_POST["pid"];

// SQL 쿼리 작성
$sql = "SELECT * FROM user WHERE pid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $pid);

// 쿼리 실행
if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = array(
            "success" => true,
            "email" => $row["email"],
            "name" => $row["name"],
            "birth" => $row["birth"],
            "sex" => $row["sex"],
            "identity" => $row["identity"],
            "profile_image" => $row["profile_image"]
        );

        echo json_encode($response);
    } else {
        // 로그인 실패
        $response = array("success" => false, "message" => "해당 사용자를 찾을 수 없습니다.");
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
