<?php
require_once("db_connect.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

$sql = "SELECT * FROM study WHERE status = '1' ";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $data = array();
    while ($row = $result->fetch_assoc()) {
        // Participant 필드를 배열로 변환
        $participantArray = json_decode($row['Participant'], true);
        // 배열의 개수를 계산
        $participantCount = is_array($participantArray) ? count($participantArray) : 0;

        $data[] = array(
            "pid" => $row['pid'],
            "name" => $row['name'],
            "master" => $row['master'],
            "classification" => $row['classification'],
            "introduction" => $row['introduction'],
            "personnel" => $row['personnel'],
            "Participant" => $row['Participant'],
            "participantCount" => $participantCount, // 배열 개수 추가
            "profile_image" => $row['profile_image'],
            "created" => $row['created']
        );
    }

    $response = array(
        "success" => true,
        "data" => $data
    );
} else {
    $response = array(
        "success" => false,
        "data" => "데이터 가져오기 실패"
    );
}

echo json_encode($response);
$stmt->close();
$conn->close();
?>
