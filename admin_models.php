<?php
    header('Content-type: text/html; charset=utf-8');
?>

<html>
    <head>
        <title>Административная панель</title>
    </head>
    <script src="dist/jszip.js"></script>
    <body>

        <!--<form action="upload_directory.php" method="post" enctype="multipart/form-data">
            <label for="name">Model name:</label>
            <input type="text" name="name" id="name"/> <br/>
            <input type="file" name="files[]" id="files" multiple="" directory="" webkitdirectory="" mozdirectory=""><br/>
            <input class="button" type="submit" value="Upload" />
        </form>
        <label>
            <span class="form-label">Выберите файлы</span>
            <input type="file" multiple="" id="file-input" text="Выбрать файлы и загрузить">
        </label>-->

        <span id='lb_add' href='addmodel.php'>Добавить</span>&nbsp;&nbsp;
        <span id='lb_delete' href=''>Удалить</span>

        <form action='delete.php' method='post' id='frm'>
        <table>
            <tr style='font-weight: bold;'>
                <td style='width: 50px;'/>
                <td style='width: 50px;'>ИД</th>
                <td style='width: 500px;'>Наименование</th>
            </tr>
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
                    echo "<tr>";
                    echo "  <td><input type='checkbox' name='cb_". $row["id"] ."'/></td>";
                    echo "  <td><a href=\"view.php?id=" . $row["id"] . "\">". $row["id"]. "</a></td>";
                    echo "  <td><a href=\"view.php?id=" . $row["id"] . "\">". $row["name"] . "</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "0 results";
            }
            $conn->close();
        ?>
        </table>
        </form>

        <script type="text/javascript">
            // var fileInput = document.getElementById("file-input");
            // var zip = new JSZip();
            // fileInput.addEventListener('change', function() {
            //     for (var i = 0; i < fileInput.files.length; i++) {
            //         console.log(fileInput.files[i]);
            //         zip.file(fileInput.files[i])
            //     }
            //     zip.generateAsync({type:"blob"})
            //         .then(function (blob) {
            //             saveAs(blob, "hello.zip");
            //         }); 
            // }, false);
        </script>

        <script type="text/javascript">
            function ready() {
                var btn = document.getElementById('lb_delete');
                btn.onclick = function () {
                    if (confirm('Подтвердите удаление')) {
                        document.getElementById('frm').submit();
                    }
                }
            };
            document.addEventListener("DOMContentLoaded", ready);
        </script>

    </body>
</html>
