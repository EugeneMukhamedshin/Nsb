<html>
    <body>

        <form action="upload_directory.php" method="post" enctype="multipart/form-data">
            <label for="name">Model name:</label>
            <input type="text" name="name" id="name"/> <br/>
            <input type="file" name="files[]" id="files" multiple="" directory="" webkitdirectory="" mozdirectory=""><br/>
            <input class="button" type="submit" value="Upload" />
        </form>

    </body>
</html>
<br/>
<br/>

<?php

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "nsb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "SELECT id, name FROM models";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
           echo "<a href=\"view.php?id=" . $row["id"] . "\"> Id: " . $row["id"]. " - Name: " . $row["name"] . "</a><br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
?>