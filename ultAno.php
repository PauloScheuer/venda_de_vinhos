<?php
session_start();
$clientes = $_SESSION['clientesDados'];
//ordena o array pela maior comprano ultimo ano
function orderMaiorC($a,$b){
    return $a['maiorCompra']['valor'] < $b['maiorCompra']['valor'];
}
    usort($clientes, 'orderMaiorC');
echo "<table id='list'>"
. "<tr><th align='center'><h2>Dono(a) da maior compra do último ano</h2></th></tr>"
. "<tr>"
        . "<td>ID</td>"
        . "<td>Nome</td>"
        . "<td>CPF</td>"
        . "<td>Número de Compras</td>"
        . "<td>Total já gasto</td>"
        . "<td>Maior compra último ano(2016)</td>"
        . "</tr>";
           echo "<tr>".
                "<td>".$clientes[0]['id']."</td>".
                "<td>".$clientes[0]['nome']."</td>".
                "<td>".$clientes[0]['cpf']."</td>".
                "<td>".$clientes[0]['numComCli']."</td>".
                "<td>".$clientes[0]['gastoTotal']."</td>".
                "<td>".$clientes[0]['maiorCompra']['valor']."</td>".
                "<td><div onclick='mostrarVinhos(".$clientes[0]['id'].")' class='indi'>Sugestões para esse cliente</div></td></tr>";
                echo "<tr >"
                        . "<td colspan='7' id='vinhos".$clientes[0]['id']."'></td>"
                        . "</tr>";
echo "</table>";  

