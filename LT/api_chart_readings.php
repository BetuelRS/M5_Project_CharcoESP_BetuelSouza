<?php
// api_chart_readings.php

// Fetch sensor data for the last 30 days filtered by sensor ID

function getSensorData($sensorId) {
    // Database connection (Assumed to be established)
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $today = date('Y-m-d');
    $last30Days = date('Y-m-d', strtotime('-30 days'));

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM sensor_data WHERE sensor_id = ? AND date BETWEEN ? AND ?");
    $stmt->bind_param("iss", $sensorId, $last30Days, $today);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $data;
}

// Example usage
if (isset($_GET['sensor_id'])) {
    $sensorId = intval($_GET['sensor_id']);
    $sensorData = getSensorData($sensorId);
    echo json_encode($sensorData);
} else {
    echo json_encode(['error' => 'No sensor ID provided']);
}
?>
