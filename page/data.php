<?php
	//ühendan sessiooniga

	/** @noinspection PhpIncludeInspection */
	require("../functions.php");

	require("../class/Helper.class.php");
	$Helper = new Helper($mysqli);

	require("../class/Car.class.php");
	$Car = new Car($mysqli);

    if (!isset($_SESSION["userId"])) {
        header("Location: login.php");  //iga headeri järele tuleks lisada exit
        exit();
    }

    if (isset($_GET["logout"])) {

        session_destroy();

        header("Location: login.php");
        exit();
    }

	$saveCarError="";
    $saveEventError="";
	//kui ei ole sisseloginud, suunan login lehele
	if (!isset($_SESSION["userId"])) {
		header("Location: login.php");  //iga headeri järele tuleks lisada exit
		exit();
	}

    if (isset($_POST["carmark"]) &&
        isset($_POST["carnumber"]) &&
        !empty($_POST["carmark"]) &&
        !empty($_POST["carnumber"])

    ) {

        $Car->saveCar($Helper->cleanInput($_POST["carmark"]),$Helper->cleanInput($_POST["carnumber"]));

    } else {
        $saveCarError = "Täida väljad !";
    }

    if (isset($_POST["number"]) &&
        isset($_POST["date"]) &&
        isset($_POST["distance"]) &&
        isset($_POST["comment"]) &&
        !empty($_POST["number"]) &&
        !empty($_POST["date"]) &&
        !empty($_POST["distance"]) &&
        !empty($_POST["comment"])
    ) {

        $Car->saveEvent($Helper->cleanInput($_POST["number"]),$Helper->cleanInput($_POST["date"]),$Helper->cleanInput($_POST["distance"]),$Helper->cleanInput($_POST["comment"]));

    } else {
        $saveEventError = "Täida väljad !";
    }
    $carnumber=$Car->getAllCars();
	$myDiary=$Car->myDiary();
?>
<h3><a href="?logout=1">Logi Välja</a></h3>
<h1>Lisa auto:</h1>
<form method="POST">
    <?php echo $saveCarError ; ?>
    <br>
    <label>Auto mark:</label>

        <input class="form-control" name="carmark" type = "text">

        <br><br>
    <label>Auto number:</label>

        <input class="form-control" name="carnumber" type = "text" >

        <br><br>
    <input class="btn btn-success btn-sm btn-block visible-xs-block" type = "submit" value = "SAVE" >
</form>

<h1>Lisa sõit:</h1>
<form method="POST">
    <?php echo $saveEventError ; ?>
    <br>
    <label>Auto number</label><br>
    <select name="number" type="text">
        <?php

        $listHtml = "";

        foreach($carnumber as $c){


            $listHtml .= "<option value='".$c->carnumber."'>".$c->carnumber."</option>";

        }

        echo $listHtml;

        ?>
    </select>
    <br><br>

    <label>Kuupäev</label><br>
    <input class="form-control" name="date" type = "date">

    <br><br>
    <label>Läbisõit (mitu km)</label><br>
    <input class="form-control" name="distance" type = "number">

    <br><br>
    <label>Kommentaar</label><br>
    <input class="form-control" name="comment" type = "text">

    <br><br>
    <input type="submit" value="Lisa">

</form>

<h1>Minu sõidud:</h1>
<?php
$html = "<table>";

    $html .= "<tr>";
        //$html .= "<td>ID</td>";
        $html .= "<td>Number</td>";
        $html .= "<td>Kuupäev</td>";
        $html .= "<td>Läbisõit</td>";
        $html .= "<td>Kommentaar</td>";
        $html .= "</tr>";

    foreach ($myDiary as $m) {

    $html .= "<tr>";
        //$html .= "<td>".$s->id."</td>";
        $html .= "<td>".$m->carnumber."</td>";
        $html .= "<td>".$m->date."</td>";
        $html .= "<td>".$m->distance."</td>";
        $html .= "<td>".$m->comment."</td>";
        $html .= "</tr>";

    }

    $html .= "</table>";

echo $html;
?>

<h1>Koondtabelid:</h1>



