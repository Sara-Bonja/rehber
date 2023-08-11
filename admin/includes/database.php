<?php 

    //VERITABANI BAGLANTISI
    $host="localhost";
    $db="postgres";
    $hesap="postgres";
    $sifre="123456";
    // $db = new PDO("pgsql:host=$host dbname=$db", $hesap , $sifre);
            // , array(
            //     PDO::PGSQL_COMMAND_OK => 'SET NAMES utf8',
            //     PDO ::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            // )

            $db = pg_connect("host=$host port=5432 dbname=$db user=$hesap password=$sifre");
if(!$db){
    echo "error";
}

?>
