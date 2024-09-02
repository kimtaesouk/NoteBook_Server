<?php
require_once("db_connect.php");

$upid = $_POST["upid"];
$date = $_POST["date"];

$sql = "SELECT timer.pid, timer.name, timer.color, timer.colorhex, timer.date, timer.status, SUM(t.totalSeconds) AS totalSeconds
FROM timer AS timer 
LEFT JOIN time AS t ON t.tpid = timer.pid AND t.date = ?
WHERE timer.upid = ?
GROUP BY timer.pid, timer.name, timer.color, timer.colorhex, timer.date, timer.status";

$stmt = $conn->prepare($sql);

$stmt->bind_param("si", $date, $upid); // "si"는 string과 integer를 나타냅니다.

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $totalSeconds = isset($row["totalSeconds"]) ? $row["totalSeconds"] : '0';
        
            $item = array(
                "pid" => $row["pid"],
                "name" => $row["name"],
                "color" => $row["color"],
                "color" => $upid,
                "colorhex" => $row["colorhex"],
                "date" => $row["date"],
                "status" => $row["status"],
                "totalSeconds" => $totalSeconds
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
