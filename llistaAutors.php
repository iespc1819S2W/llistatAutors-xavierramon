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
        $perid = isset($_POST['idcercar']) ? $_POST['idcercar'] : "";
        $pernom = isset($_POST['nomcercar']) ? $_POST['nomcercar'] : "";
        $cercarper = isset($_POST['cercarper']) ? $_POST['cercarper'] : "1";
        $mentres = isset($_POST['mentres']) ? $_POST['mentres'] : "ID_AUT";
        //$pagina = isset($_POST['pagina']) ? $_POST['pagina'] : "ID_AUT";

        $mysqli = new mysqli("localhost", "xavier", "1234", "biblioteca");
        $mysqli->set_charset("utf8");
        $pagina = 1;
        $vinici = 0;
        $vfi = 20;
        $quantitat = "";
        $ordenat = "ID_AUT";
        $ordre = "asc";
        $queryFi = "select count(*) as 'quantitat' from AUTORS";
        if ($cursor = $mysqli->query($queryFi)) {
            while ($row = $cursor->fetch_assoc()) {
                $quantitat = $row['quantitat'];
            };
            $npagines = ceil($quantitat / 20);
            $npagines -= 1;
            $cursor->free();
        };

        if (isset($_POST["pagina"])) {
            $pagina = $_POST["pagina"];
        }
        if (isset($_POST["ordre"]) && isset($_POST["ordre"])) {
            $ordre = $_POST["ordre"];
            $ordenat = $_POST["ordenat"];
        } else {
            $ordre = "asc";
            $ordenat = "ID_AUT";
        }
        if (!empty($_POST["nasc"])) {
            $ordenat = "NOM_AUT";
            $ordre = "asc";
        }
        if (!empty($_POST["ndesc"])) {
            $ordenat = "NOM_AUT";
            $ordre = "desc";
        }
        if (!empty($_POST["casc"])) {
            $ordenat = "ID_AUT";
            $ordre = "desc";
        }
        if (!empty($_POST["cdesc"])) {
            $ordenat = "ID_AUT";
            $ordre = "asc";
        }
        if (!empty($_POST["seguent"])) {
            if ($pagina != $npagines) {
                $pagina++;
            };
        } elseif (!empty($_POST["anterior"])) {
            if ($pagina > 0) {
                $pagina--;
            };
        } elseif (!empty($_POST["inici"])) {
            $pagina = 0;
        } elseif (!empty($_POST["fi"])) {
            $pagina = $npagines;
        }
        $vinici = $pagina * $vfi;
        if (!empty($_POST["cercar"])) {
            if (!empty($pernom)) {
                $cercarper = "'%" . $pernom . "%'";
                $mentres = "NOM_AUT";
            } else if (!empty($perid)) {
                $cercarper = $perid;
                $mentres = "ID_AUT";
            } else {
                $cercarper = "1";
                $mentres = "ID_AUT";
            }
            $query = "select * from AUTORS where " . $mentres . " like " . $cercarper . " order by " . $ordenat . " " . $ordre . " limit " . $vinici . "," . $vfi;
        } else {
            $query = "select * from AUTORS order by " . $ordenat . " " . $ordre . " limit " . $vinici . "," . $vfi;
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
            <input type="hidden" name="pagina" value="<?= $pagina ?>">
            <input type="hidden" name="ordenat" value="<?= $ordenat ?>">
            <input type="hidden" name="ordre" value="<?= $ordre ?>">
            <input type="hidden" name="cercarper" value="<?= $cercarper ?>">
            <input type="hidden" name="mentres" value="<?= $mentres ?>">
            Cercar per ID:
            <input type="text" name="idcercar">
            Cercar per Nom:
            <input type="text" name="nomcercar">
            <input type="submit" value="Cercar" name="cercar"></br>
            <input type="submit" value="NOM" name="nasc">
            <input type="submit" value="nom" name="ndesc">
            <input type="submit" value="CODI" name="casc">
            <input type="submit" value="codi" name="cdesc"></br>
            <input type="submit" value="Inici" name="inici">
            <input type="submit" value="Anterior" name="anterior">
            <input type="submit" value="Següent" name="seguent">
            <input type="submit" value="Fi" name="fi">
        </form>
    </body>
</html>

