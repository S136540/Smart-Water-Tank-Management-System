<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

function dbconnection(){

    $con = mysqli_connect("localhost","root","","temphumidnew");
    return $con;
}

class Dht11 {
    public $link = '';

    function __construct($temperature, $humidity, $water_level) {
        $this->connect();
        $this->storeInDB($temperature, $humidity, $water_level);
    }

    function connect() {
        $this->link = mysqli_connect('localhost', 'root', '') or die('Cannot connect to the DB');
        mysqli_select_db($this->link, 'temphumidnew') or die('Cannot select the DB');
    }

    function storeInDB($temperature, $humidity, $water_level) {
        $waterLevelValue = $this->getWaterLevelValue($water_level);

        $query = "INSERT INTO dht11 (humidity, temperature, water_level) VALUES ('$humidity', '$temperature', '$water_level')";
        $result = mysqli_query($this->link, $query) or die('Errant query: ' . $query);

        // Send a JSON response to the app
        $response = array("success" => "true", "message" => "Data stored successfully");
        echo json_encode($response);
    }

    function getWaterLevelValue($water_level) {
        switch ($water_level) {
            case "Low":
                return 1;
            case "Medium":
                return 2;
            case "High":
                return 3;
            default:
                return 0; // Default or unknown status
        }
    }
}

// Check if the required parameters are present in the GET request
if (isset($_GET['temperature']) && isset($_GET['humidity']) && isset($_GET['water_level'])) {
    $water_level = $_GET['water_level'];
    $dht11 = new Dht11($_GET['temperature'], $_GET['humidity'], $water_level);
}
?>
