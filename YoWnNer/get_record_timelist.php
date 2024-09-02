<?php
require_once("db_connect.php");

$upid = $_POST["upid"];
$date = isset($_POST["date"]) ? $_POST["date"] : null;

$sql = "SELECT time.tpid, time.pid,time.date, time.starttime, time.stoptime, time.totalSeconds, timer.name, timer.color, timer.colorhex
        FROM time AS time 
        LEFT JOIN timer AS timer ON time.tpid = timer.pid 
        WHERE time.upid = ? AND time.status = 1 AND time.date = ?";


$stmt = $conn->prepare($sql);

if (!is_null($date)) {
    $stmt->bind_param("is", $upid, $date); // "is" is for integer and string.
} else {
    $stmt->bind_param("i", $upid); // "i" is for integer.
}

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $items = array();
        while ($row = $result->fetch_assoc()) {
            $item = array(
                "pid" => $row["pid"],
                "starttime" => $row["starttime"],
                "stoptime" => $row["stoptime"],
                "totalSeconds" => $row["totalSeconds"],
                "color" => $row["color"],
                "colorhex" => $row["colorhex"],
                "date" => $row["date"],
                "name" => $row["name"]
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


