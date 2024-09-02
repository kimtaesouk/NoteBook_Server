<?php
require_once("db_connect.php");

$upid = $_POST["upid"];
$date = $_POST["date"];

$sql = "SELECT timer.pid, timer.color, timer.colorhex ,t.starttime, t.stoptime
FROM timer AS timer 
LEFT JOIN time AS t ON t.tpid = timer.pid AND t.date = ?
WHERE timer.upid = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("si", $date, $upid); // "si"는 string과 integer를 나타냅니다.

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
        
            $item = array(
                "pid" => $row["pid"],
                "color" => $row["color"],
                "colorhex" => $row["colorhex"],
                "starttime" => $row["starttime"],
                "stoptime" => $row["stoptime"]
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
    $response = array("success" => false, "message" => "쿼리 실행 실패: " . mysqli_error($conn));
    echo json_encode($response);
}

$stmt->close();
$conn->close();

?>
