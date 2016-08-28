<?php
    header('Content-type: text/html; charset=utf-8');
?>

<html>
    <head>
        <title>Административная панель</title>
    </head>
    <script src="dist/jszip.js"></script>
    <body>

        <form action="upload_directory.php" method="post" enctype="multipart/form-data">
            <label for="name">Model name:</label>
            <input type="text" name="name" id="name"/> <br/>
            <input type="file" name="files[]" id="files" multiple="" directory="" webkitdirectory="" mozdirectory=""><br/>
            <input class="button" type="submit" value="Upload" />
        </form>
        <label>
            <span class="form-label">Выберите файлы</span>
            <input type="file" multiple="" id="file-input" text="Выбрать файлы и загрузить">
        </label>
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

        <script type="text/javascript">
            var fileInput = document.getElementById("file-input");
            var zip = new JSZip();
            fileInput.addEventListener('change', function() {
                for (var i = 0; i < fileInput.files.length; i++) {
                    console.log(fileInput.files[i]);
                    zip.file(fileInput.files[i])
                }
                zip.generateAsync({type:"blob"})
                    .then(function (blob) {
                        saveAs(blob, "hello.zip");
                    }); 
            }, false);
        </script>

    </body>
</html>
