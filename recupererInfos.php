<?php 

$url = "https://api.jcdecaux.com/vls/v1/stations?contract=lyon&apiKey=88f643fdde21f3f58e0170aa5a7e7df1fa01a592";
$raw = file_get_contents($url);
echo $raw;
$tab = json_decode($raw,true);
var_dump($tab);
echo sizeof($tab);

// on se connecte à MySQL 
$db = mysql_connect('mysql1.akeyan.fr', '', ''); 

// on sélectionne la base 
mysql_select_db('adrienrochedy',$db); 

$temps = mktime()+7200;

for($i = 0;$i<sizeof($tab);$i++)
{
	echo $tab[$i]["name"].'</br>';
	echo $tab[$i]["available_bike_stands"].'</br>';
	echo $tab[$i]["available_bikes"].'</br>';
	echo $tab[$i]["last_update"].'</br>';
	echo $tab[$i]["number"].'</br>';
	echo $tab[$i]["status"].'</br>';

	$sql ='insert into adrienrochedy.mesures VALUES (NULL, '.$tab[$i]["number"].',"'.$tab[$i]["status"].'", '.$tab[$i]["available_bikes"].', '.$tab[$i]["available_bike_stands"].','.$temps.');';
	//$sql = 'INSERT INTO adrienrochedy.mesures (id, numero, statut, velos, places, time) VALUES (NULL, '.$tab[$i]["number"].', '.$tab[$i]["status"].', '.$tab[$i]["available_bikes"].', '.$tab[$i]["available_bike_stands"].', '$tab[$i]["last_update"]');'; 
	echo $sql;
	// on envoie la requête 
	$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 
}


$url = "http://api.openweathermap.org/data/2.5/weather?q=lyon&units=metric&APPID=b16c862409d05de247ef9c0e3eeef12f";
$raw = file_get_contents($url);
//echo $raw;
$tab = json_decode($raw,true);
var_dump($tab);




echo $tab["weather"][0]["main"];
echo $tab["weather"][0]["description"];
echo $tab["weather"][0]["icon"];
echo $tab["main"]["temp"];
echo $tab["main"]["pressure"];
echo $tab["main"]["humidity"];
echo $tab["wind"]["speed"];
echo $tab["wind"]["deg"];
echo $tab["rain"]["3h"];
echo $tab["clouds"]["all"];


$sql ='insert into adrienrochedy.meteo VALUES (NULL, '.$temps.',"'.$tab["weather"][0]["main"].'", "'.$tab["weather"][0]["description"].'","'.$tab["weather"][0]["icon"].'",'.$tab["main"]["temp"].', '.$tab["wind"]["speed"].','.$tab["wind"]["deg"].', '.$tab["rain"]["3h"].','.$tab["clouds"]["all"].', '.$tab["main"]["pressure"].','.$tab["main"]["humidity"].');';
	echo $sql;
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 



mysql_close(); 



?> 