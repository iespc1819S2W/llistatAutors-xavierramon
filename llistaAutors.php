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
        </style>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>
    <body>

        <?php
        $ID_AUT = isset($_POST['ID_AUT']) ? $_POST['ID_AUT'] : "";
        $NOM_AUT = isset($_POST['NOM_AUT']) ? $_POST['NOM_AUT'] : "";
        $cercarper = isset($_POST['cercarper']) ? $_POST['cercarper'] : "'%'";
        $mentres = isset($_POST['mentres']) ? $_POST['mentres'] : "NOM_AUT";
        $addAutor = isset($_POST['afegirAutor']) ? $_POST['afegirAutor'] : "";
        $addNacio = isset($_POST['afegirNacio']) ? $_POST['afegirNacio'] : "";
        $pagina = isset($_POST['pagina']) ? $_POST['pagina'] : "ID_AUT";
        $mysqli = new mysqli("127.0.0.1", "root", "", "biblioteca");
        $mysqli->set_charset("utf8");
        $pagina = 1;
        $vinici = 0;
        $vfi = 20;
        $quantitat = "";
        $darrerId = "";
        $ordenat = "ID_AUT";
        $ordre = "asc";
        $quary = "";
        $queryFi = "select count(*) as 'quantitat' from AUTORS";
        $editaSel = "";
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
            if (!empty($NOM_AUT)) {
                $cercarper = "'%" . $NOM_AUT . "%'";
                $mentres = "NOM_AUT";
            } else if (!empty($ID_AUT)) {
                $cercarper = $ID_AUT;
                $mentres = "ID_AUT";
            } else {
                $cercarper = "'%'";
                $mentres = "ID_AUT";
            }
        }
        if (isset($_POST["afegir"])) {
            $queryDarrerId = "select max(ID_AUT) as max from AUTORS";
            if ($cursor = $mysqli->query($queryDarrerId)) {
                while ($row = $cursor->fetch_assoc()) {
                    $darrerId = $row['max'];
                    $darrerId++;
                };
                $cursor->free();
            };
            echo $darrerId . "</br>";
            if (!empty($addAutor)) {
                if (!empty($addNacio)) {
                    $queryAgregar = "INSERT INTO AUTORS(ID_AUT, NOM_AUT, FK_NACIONALITAT) VALUES ($darrerId, '$addAutor', '$addNacio')";
                    //echo $r;
                    //echo $queryAgregar . "</br>";
                } else {
                    $queryAgregar = "INSERT INTO AUTORS(ID_AUT, NOM_AUT) VALUES ($darrerId, '$addAutor')";
                }
                $r = $mysqli->query($queryAgregar);
            }
        }
        if (isset($_POST["borrar"])) {
            $idBorrar = isset($_POST['borrar']) ? $_POST['borrar'] : "0";
            if ($idBorrar > 0) {
                $queryEliminar = "DELETE FROM AUTORS WHERE ID_AUT = '$idBorrar'";
                $delete = $mysqli->query($queryEliminar);
                //echo $queryEliminar . "</br>";
            }
        }
        if (isset($_POST["editar"])) {
            $editaSel = $_POST['editar'];
        }
        if (isset($_POST["guardar"])) {
            $idAut = $_POST["guardar"];
            $nomAut = $_POST["modNomAut"];
            $nomNacio = $_POST["modNacio"];
            $queryEditar = "UPDATE AUTORS SET NOM_AUT = '$nomAut', FK_NACIONALITAT = '$nomNacio' WHERE ID_AUT = '$idAut'";
            $delete = $mysqli->query($queryEditar);
            //echo $queryEditar . "</br>";
        }
        $query = "select * from AUTORS where " . $mentres . " like " . $cercarper . " order by " . $ordenat . " " . $ordre . " limit " . $vinici . "," . $vfi;
        echo $query . "</br>";
        ?>
        <div class="container">
            <h1>Llista Autors</h1>
            <form action="llistaAutors.php" method="post" id="filtres">
                <input type="hidden" name="pagina" value="<?= $pagina ?>">
                <input type="hidden" name="ordenat" value="<?= $ordenat ?>">
                <input type="hidden" name="ordre" value="<?= $ordre ?>">
                <input type="hidden" name="cercarper" value="<?= $cercarper ?>">
                <input type="hidden" name="mentres" value="<?= $mentres ?>">
                Cercar per ID:
                <input type="text" name="ID_AUT">
                Cercar per Nom:
                <input type="text" name="NOM_AUT">
                <input type="submit" class="btn btn-primary" value="Cercar" name="cercar"></br>
                <input type="submit" class="btn btn-primary" value="NOM" name="nasc">
                <input type="submit" class="btn btn-info" value="nom" name="ndesc">
                <input type="submit" class="btn btn-primary" value="CODI" name="casc">
                <input type="submit" class="btn btn-info" value="codi" name="cdesc">
                <input type="submit" class="btn btn-dark" value="Inici" name="inici">
                <input type="submit" class="btn btn-secondary" value="Anterior" name="anterior">
                <input type="submit" class="btn btn-secondary" value="Següent" name="seguent">
                <input type="submit" class="btn btn-dark" value="Fi" name="fi"></br>
                Autor:
                <input type="text" name="afegirAutor">
                Nacionalitat:
                <input type="text" name="afegirNacio">
                <input type="submit" class="btn btn-primary" value="Afegir" name="afegir"></br>
            </form>
        </div>
        <?php
        echo '<div class="container"><table class="table">';
        echo '<thead class="thead-dark">';
        echo "<tr>";
        echo '<th scope="col">ID</th>';
        echo '<th scope="col">NOM AUTOR</th>';
        echo '<th scope="col">NACIONALITAT</th>';
        echo '<th scope="col"></th>';
        echo '<th scope="col"></th>';
        echo "</tr>";
        if ($result = $mysqli->query($query)) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_AUT"] . "</td>";
                if ($editaSel == $row["ID_AUT"]) {
                    echo "<td><input type='text' name='modNomAut' value ='" . $row["NOM_AUT"] . "' form='filtres'></td>";
                    echo "<td><input type='text' name='modNacio' value ='" . $row["FK_NACIONALITAT"] . "' form='filtres'></td>";
                    echo "<td><button type='submit' name='cancelar' value='" . $row["ID_AUT"] . "' form='filtres'>Cancelar</button></td>";
                    echo "<td><button type='submit' name='guardar' value='" . $row["ID_AUT"] . "' form='filtres'>Guardar</button></td>";
                } else {
                    echo "<td>" . $row["NOM_AUT"] . "</td>";
                    echo "<td>" . $row["FK_NACIONALITAT"] . "</td>";
                    echo "<td><button type='submit' name='borrar' value='" . $row["ID_AUT"] . "' form='filtres'>Borrar</button></td>";
                    echo "<td><button type='submit' name='editar' value='" . $row["ID_AUT"] . "' form='filtres'>Editar</button></td>";
                }
                echo "</tr>";
            }
            $result->free();
        }
        echo "</table></div>";
        $mysqli->close();
        ?>


    </body>
</html>
