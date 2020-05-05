<?php
session_start();
$clientes = $_SESSION['clientesDados'];
$vinhos= $_SESSION['vinhos'];
$id = $_POST['id']-1;
$i = 0;
$size = sizeof($clientes[$id]['tipoVinho']);
echo "<h3>Vinhos que possivelmente esse cliente gostará:</h3>";
while ($i<$size){
    $indexV = $clientes[$id]['tipoVinho'][$i];
    $j = 0;
    $size2 = sizeof($vinhos[$indexV]);
    while ($j<$size2){
       echo $indexV." - <a class='nomevinho'>".$vinhos[$indexV][$j]."</a><br>";
       $j++;
    }
    $i++;
}

