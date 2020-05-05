<?php
session_start();
$clientes = $_SESSION['clientesDados'];
//ordena array pelo gasto total do cliente
function orderGasto($a,$b){
    return $a["gastoTotal"] < $b["gastoTotal"];
}
    usort($clientes,'orderGasto');
$iShow = 0;
$numCli = sizeof($clientes);
echo "<table id='list'>"
. "<tr><th align='center'><h2>Por total de gastos</h2></th></tr>"
. "<tr>"
        . "<td>ID</td>"
        . "<td>Nome</td>"
        . "<td>CPF</td>"
        . "<td>Número de Compras</td>"
        . "<td>Total já gasto</td>"
        . "<td>Maior compra último ano(2016)</td>"
        . "</tr>";
while($iShow<$numCli){
           echo "<tr>".
                "<td>".$clientes[$iShow]['id']."</td>".
                "<td>".$clientes[$iShow]['nome']."</td>".
                "<td>".$clientes[$iShow]['cpf']."</td>".
                "<td>".$clientes[$iShow]['numComCli']."</td>".
                "<td>".$clientes[$iShow]['gastoTotal']."</td>".
                "<td>".$clientes[$iShow]['maiorCompra']['valor']."</td>".
                "<td><div onclick='mostrarVinhos(".$clientes[$iShow]['id'].")' class='indi'>Sugestões para esse cliente</div></td>";
                
                echo "</tr>";
                echo "<tr >"
                        . "<td colspan='7' id='vinhos".$clientes[$iShow]['id']."'></td>"
                        . "</tr>";
            $iShow++;
        }
echo "</table>";  

