<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            table{
                margin: auto;
            }            
            div{
                margin: auto;
            }            
        </style>
    </head>
    <body>
        <h1>Algo</h1>
        <?php
        $perid = isset($_POST['idcercar']) ? $_POST['idcercar'] : "";
        $pernom = isset($_POST['nomcercar']) ? $_POST['nomcercar'] : "";
        $cercarper = isset($_POST['cercarper']) ? $_POST['cercarper'] : "";
        $mentres = isset($_POST['mentres']) ? $_POST['mentres'] : "";
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
        $queryDarrerId = "select max(ID_AUT) from AUTORS";
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
        if (isset($_POST["nasc"])) {
            $ordenat = "NOM_AUT";
            $ordre = "asc";
        }
        if (isset($_POST["ndesc"])) {
            $ordenat = "NOM_AUT";
            $ordre = "desc";
        }
        if (isset($_POST["casc"])) {
            $ordenat = "ID_AUT";
            $ordre = "desc";
        }
        if (isset($_POST["cdesc"])) {
            $ordenat = "ID_AUT";
            $ordre = "asc";
        }
        if (isset($_POST["seguent"])) {
            if ($pagina != $npagines) {
                $pagina++;
            };
        } elseif (isset($_POST["anterior"])) {
            if ($pagina > 0) {
                $pagina--;
            };
        } elseif (isset($_POST["inici"])) {
            $pagina = 0;
        } elseif (isset($_POST["fi"])) {
            $pagina = $npagines;
        }
        $vinici = $pagina * $vfi;
        if (isset($_POST["cercar"])) {
            if (!empty($pernom)) {
                $cercarper = "'%" . $pernom . "%'";
                $mentres = "NOM_AUT";
            } else if (!empty($perid)) {
                $cercarper = $perid;
                $mentres = "ID_AUT";
            } else {
                $cercarper = "'%'";
                $mentres = "ID_AUT";
            }
        }
        $query = "select * from AUTORS where " . $mentres . " like " . $cercarper . " order by " . $ordenat . " " . $ordre . " limit " . $vinici . "," . $vfi;
        $queryAgregar = "INSERT INTO AUTORS VALUES ('9999', 'Fulano','1974-04-12', 'ES', '')";
        //echo $query;
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
            <div>
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
            </div>
        </form>
    </body>
</html>

