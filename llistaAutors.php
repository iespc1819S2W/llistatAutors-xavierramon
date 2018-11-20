<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Algo</h1>
        <?php
        $mysqli = new mysqli("192.168.56.101", "xavier", "1234", "biblioteca");

        $query = $mysqli->query("SELECT * FROM autors");
        $result = $mysqli->query($query);

        $row = $result->fetch_array(MYSQLI_NUM);
        printf("%s (%s)\n", $row[0], $row[1]);
        $result->close();

        $mysqli->close();
        ?>
    </body>
</html>
