<?php
    header('Content-type: text/html; charset=utf-8');
?>

<?php
    include 'config.php';

    // Create connection
    $conn = new mysqli($serverName, $username, $password, $dbName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT id, name FROM models";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<a href=\"view.php?id=" . $row["id"] . "\">" . $row["name"] . "</a><br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
?>