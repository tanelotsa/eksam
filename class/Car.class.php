<?php
class Car{

    private $connection;

    // käivitatakse siis kui on = new User(see jõuab siia)
    function __construct($mysqli)
    {
        //this viitab sellele klassile ja selle klassi muutujale
        $this->connection = $mysqli;
    }

    function saveCar($carmark,$carnumber) {

        $stmt = $this->connection ->prepare("INSERT INTO r_car (carmark, carnumber) VALUE(?,?)");
        echo $this->connection->error;

        $stmt->bind_param("ss", $carmark, $carnumber);

        if($stmt->execute() ) {
            echo "Õnnestus!","<br>";
        } else{
            echo "ERROR " . $stmt->error;
        }

    }

    function getAllCars()
    {

        $stmt = $this->connection->prepare("SELECT id, carmark, carnumber FROM r_car");
        echo $this->connection->error;

        $stmt->bind_result($id, $carmark, $carnumber);
        $stmt->execute();

        //tekitan massiivi
        $result = array();

        // tee seda seni, kuni on rida andmeid
        // mis vastab select lausele
        while ($stmt->fetch()) {

            //tekitan objekti
            $c = new StdClass();
            $c->id = $id;
            $c->carnumber = $carnumber;

            array_push($result, $c);
        }

        $stmt->close();

        return $result;
    }

    function saveEvent($carnumber, $date, $distance, $comment) {

        $stmt = $this->connection->prepare("INSERT INTO r_diary (carnumber, date, distance, comment, caruser) VALUE (?, ?, ?, ?, ?)");
        echo $this->connection->error;

        $stmt->bind_param("ssisi", $carnumber, $date, $distance, $comment, $_SESSION ["userId"]);

        if ($stmt->execute() ){
            echo "�nnestus";
        } else {
            echo "ERROR".$stmt->error;
        }
    }


    function myDiary() {


        $stmt = $this->connection->prepare("SELECT id, carnumber, date, distance, comment FROM r_diary WHERE caruser = ?");

        $stmt->bind_param("i", $_SESSION ["userId"]);

        $stmt->bind_result($id, $carnumber, $date, $distance, $comment);

        $stmt->execute ();
        $results = array();

        //tsükli sisu tehakse niimitu korda , mitu rida sql lausega tuleb
        while($stmt->fetch()) {
            $myDiary = new StdClass();
            $myDiary->id = $id;
            $myDiary->carnumber = $carnumber;
            $myDiary->date = $date;
            $myDiary->distance = $distance;
            $myDiary->comment = $comment;
            array_push($results,$myDiary);

        }
        return $results;

    }

}
?>