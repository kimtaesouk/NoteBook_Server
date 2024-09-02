<?php
require_once("db_connect.php");

$upid = $_POST["upid"];


$sql = "SELECT totalSeconds, date FROM time WHERE upid = '$upid'";


$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $item = array(
                "totalSeconds" => $row["totalSeconds"],
                "date" => $row["date"],
                
            );
            $items[] = $item;
        }
        $response = array("success" => true, "data" => $items);
        echo json_encode($response);
    } else {
        $response = array("success" => false, "message" => "데이터가 없습니다.");
        echo json_encode($response);
    }
} else {
    $response = array("success" => false, "message" => "쿼리 실행 실패: " . $stmt->error);
    echo json_encode($response);
}

$stmt->close();
$conn->close();

?>


