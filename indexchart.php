
<?php
$id =$_GET['id'];

// on se connecte à MySQL 
$db = mysql_connect('mysql1.akeyan.fr', '', ''); 

// on sélectionne la base 
mysql_select_db('',$db); 


$timeMinuit = mktime(0, 0, 0, date("m") , date("d"), date("Y"));
$timeNow =time();
$sql ='SELECT velos,places,temps  FROM mesures  WHERE id IN (SELECT * FROM (SELECT id FROM mesures WHERE numero ='.$id.' order by id desc  LIMIT 0,20) AS temp) order by id asc ;';


//SELECT velos,places,temps from mesures where numero ='.$id.' group by velos,places,temps  order by temps LIMIT 30;';
//$sql ='SELECT velos,places,temps from mesures where numero ='.$id.' and temps between  '.$timeMinuit.'000 AND '.$timeNow.'000  group velos,places,temps by order by temps ;';
// on envoie la requête 
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

// on fait une boucle qui va faire un tour pour chaque enregistrement 

$x=""; 
$y1=""; 
$y2="";
while($data = mysql_fetch_assoc($req)) 
{      
	$temps = $data['temps'];
	$temps = substr($data['temps'],0,10);
	$temps = $temps - 2*3600;

	$x.=date('Hi',$temps).',';
	$y1.=$data['velos'].',';
	$y2.=$data['places'].',';
	$nbvelos =$data['velos'];
	$nbplaces =$data['places'];
}    
$sql ='SELECT * from stations where numero ='.$id.';';
$req = mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error()); 

while($data = mysql_fetch_assoc($req)) 
{      
	$nom =$data['name'];
} 
echo '<script>';
echo '$(\'#nom\').html(\' <h2> <i class="fa fa-tag"></i>  '.$nom.' </h2>\');';
echo '$(\'#nbvelos\').html(\' <i class="fa fa-bicycle"></i> '.$nbvelos.' Velos \');';
echo '$(\'#nbplaces\').html(\' '.$nbplaces.' Places \' );';

$x= substr($x, 0, strlen($x)-1);
$y1= substr($y1, 0, strlen($y1)-1);
$y2= substr($y2, 0, strlen($y2)-1);
// on ferme la connexion à mysql 



mysql_close(); 
/*
//Get the context of the canvas element we want to select
echo'var ctx = document.getElementById("myChart").getContext("2d");';

echo 'var data = {
	labels : ['.$x.'],
	datasets : [
		{
			fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",			
			data : ['.$y1.'],
			title : "Velos",
		},
		{
			fillColor : "rgba(151,187,205,0.5)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			title : "Places",
			data : ['.$y2.']
		}
	]
};';

echo 'var myNewChart = new Chart(ctx).Line(data);';*/

echo "
    $('#myChart').highcharts({
        title: {
            text: 'Nombre de vélos ou places',
            x: -20 //center
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            categories: [".$x."]
        },
        yAxis: {
            title: {
                text: 'Nombre de vélos ou places '
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Velos',
            data: [".$y1."]
        }, {
            name: 'Places',
            data: [".$y2."]
        }]
    });
";
echo '</script>';
?> 




