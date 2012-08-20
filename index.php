<?php
/*  Copyright 2012
 *	Lorenzo Braghetto monossido@lorenzobraghetto.com
 *      This file is part of IRE TAG Web GUI <https://github.com/monossido/IRE-TAG-Web-GUI>
 *      
 *      IRE TAG Web GUI is free software: you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation, either version 3 of the License, or
 *      (at your option) any later version.
 *      
 *      IRE TAG Web GUI is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with SpeakBird  If not, see <http://www.gnu.org/licenses/>.
 *      
 */


/*Edit the DB name*/
$dbname = "";
try 
{
	/*** connect to SQLite database ***/

	$sq = new PDO("sqlite:".$dbname);
	echo "Handle has been created ...... <br><br>";

	echo "Database loaded successfully ....<br><br>";
	$resultStats = $sq->query('SELECT name FROM sqlite_master WHERE type = "table" AND name LIKE "stats%"');
	$giocatori = array();
	$i = 0;
	foreach($resultStats as $nameTable)
	{
		$i++;
		$result = $sq->query('SELECT * FROM '.$nameTable['name']);
		echo "Partita ".$i;
		echo "<table border=1>";
		echo "<tr><th>Nick</th><th>Colpi sparati</th><th>Colpi a segno</th><th>Kill</th><th>Colpi a segno su amici</th><th>Team Kill</th><th>Precisione %</th><th>Respawn</th><th>Morti</th><th>Partite giocate</th><th>Punti Esperienza</th></tr>";
		foreach($result as $data)
		{
			if(!in_array($data['giocatore'], $giocatori))
			{
				array_push($giocatori, $data['giocatore']);
			}
			echo "<tr><td>".$data['giocatore']."</td>";
			echo "<td>".$data['cs']."</td>";
			echo "<td>".$data['cn']."</td>";
			echo "<td>".$data['ne']."</td>";
			echo "<td>".$data['ca']."</td>";
			echo "<td>".$data['ae']."</td>";
			echo "<td>".$data['prec']."</td>";
			echo "<td>".$data['rs']."</td>";
			echo "<td>".$data['ve']."</td>";
			echo "<td>".$data['pg']."</td>";
			echo "<td>".$data['xp']."</td></tr>";
		}
		echo "</table><br /><br />";
	}

	echo "Stats totali (di partite falsate)";

	echo "<table border=1>";
	echo "<tr><th>Nick</th><th>Colpi sparati</th><th>Colpi a segno</th><th>Kill</th><th>Colpi a segno su amici</th><th>Team Kill</th><th>Precisione %</th><th>Respawn</th><th>Morti</th><th>Partite giocate</th><th>Punti Esperienza</th></tr>";
	for($i=0;$i<count($giocatori);$i++)
	{
		echo "<tr><td>".$giocatori[$i]."</td>";
		$cs = 0;
		$cn = 0;
		$ne = 0;
		$ca = 0;
		$ae = 0;
		$prec = 0;
		$rs = 0;
		$ve = 0;
		$pg = 0;
		$xp = 0;
		$resultStats = $sq->query('SELECT name FROM sqlite_master WHERE type = "table" AND name LIKE "stats%"');
		foreach($resultStats as $nameTable)
		{
			$sql = "SELECT COUNT(*) FROM ".$nameTable['name']." WHERE giocatore='$giocatori[$i]'";
			$res = $sq->query($sql);
			$rowNum = $res->fetchColumn();
			if($rowNum>0)
			{
				$result = $sq->query("SELECT * FROM ".$nameTable['name']." WHERE giocatore='$giocatori[$i]'");
				$data = $result->fetchAll();
				$cs = $cs + $data[0]['cs'];
				$cn = $cn + $data[0]['cn'];
				$ne = $ne + $data[0]['ne'];
				$ca = $ca + $data[0]['ca'];
				$ae = $ae + $data[0]['ae'];
				$prec = $prec + $data[0]['prec'];
				$rs = $rs + $data[0]['rs'];
				$ve = $ve + $data[0]['ve'];
				$pg = $pg + $data[0]['pg'];
				$xp = $xp + $data[0]['xp'];
			}
		}
		echo "<td>".$cs."</td>";
		echo "<td>".$cn."</td>";
		echo "<td>".$ne."</td>";
		echo "<td>".$ca."</td>";
		echo "<td>".$ae."</td>";
		echo "<td>".$prec."</td>";
		echo "<td>".$rs."</td>";
		echo "<td>".$ve."</td>";
		echo "<td>".$pg."</td>";
		echo "<td>".$xp."</td>";

		echo "</tr>";
	}
	echo "</table>";
	echo "<p align='right'>Copyright <a href='http://lorenzobraghetto.com'>Lorenzo Braghetto</a> - <a href='https://github.com/monossido/IRE-TAG-Web-GUI'>IRE TAG Web Gui</a> is released under GPL license</p>";
	$db = NULL;
}
catch(PDOException $e)
{
    echo $e->getMessage();
    echo "<br><br>Database -- NOT -- loaded successfully .. ";
    die( "<br><br>Query Closed !!! $error");
}


?>
