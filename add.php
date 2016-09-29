<?php
    header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавление модели</title>
</head>
<body>
    <form action="upload.php">
        <input name=""/>
        <input type="file" id="input" multiple onchange="handleFiles(this.files)">
    </form>
    <ul id="fileList"></ul>
    <script type="application/javascript">
        var fileDict = {};

        function handleFiles() {
            var fileList = document.getElementById('input').files;
            var element = document.getElementById('fileList');
            for (var i = 0; i < fileList.length; i++) {
                var li = document.createElement('li');
                li.textContent = fileList[i].name;
                element.appendChild(li);
                fileDict[fileList[i].name] = li;
            }

            for (var i = 0; i < fileList.length; i++) {
                fileDict[fileList[i].name].style.fontSize = 32;
            }
        }
    </script>
</body>
</html>
