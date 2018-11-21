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
        $mysqli = new mysqli("localhost", "xavier", "1234", "biblioteca");
        $mysqli->set_charset("utf8");
        $contador = 0;
        if (!empty($_POST["seguent"])) {
            $contador = $_POST["contador"] + 10;
            $query = ("SELECT * FROM AUTORS LIMIT 10 OFFSET " . $contador);
        } elseif (!empty($_POST["anterior"])) {
            $contador = $_POST["contador"] - 10;
            $query = ("SELECT * FROM AUTORS LIMIT 10 OFFSET " . $contador);
        } elseif (!empty($_POST["fi"])) {
            $contador = 0;
            $query = ("SELECT * FROM AUTORS order by ID_AUT desc LIMIT 10");
        } elseif (!empty($_POST["inici"])) {
            //$contador = $_POST["contador"] - 10;
            $query = ("SELECT * FROM AUTORS order by ID_AUT asc LIMIT 10");
        } else {
            $query = ("SELECT * FROM AUTORS LIMIT 10");
        }
        echo "<table  border = \"1\">";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>NOM AUTOR</th>";
        echo "</tr>";
        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_AUT"] . "</td>";
                echo "<td>" . $row["NOM_AUT"] . "</td>";
                echo "</tr>";
            }
            $result->free();
        }

        $mysqli->close();
        ?>
        <form action="llistaAutors.php" method="post" id="filtres" >
            <input type="hidden" name="contador" value="<?= $contador ?>">
            <input type="submit" value="Inici" name="inici">
            <input type="submit" value="Anterior" name="anterior">
            <input type="submit" value="Següent" name="seguent">
            <input type="submit" value="Fi" name="fi">
        </form>
    </body>
</html>
