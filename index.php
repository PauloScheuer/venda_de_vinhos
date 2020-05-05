<?php session_start(); ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script> 
        <script src="http://malsup.github.com/jquery.form.js"></script> 
        <script>  
        $(document).ready(function() {
            $("#content").hide();
            $("#load").delay(1000).fadeOut("slow");
            $("#content").delay(1000).fadeIn("slow");
        });
        window.onload = initPage;
        //função para iniciar a pagina corretamente
        function initPage() {
        if(sessionStorage.getItem('order') != null){
        var href = sessionStorage.getItem('order')+".php";    
        }else{
        var href= "fiel.php";    
        }
        
        $('#listDiv').load(href).hide().fadeIn('fast'); 
        }
        $('#order').ajaxForm(function () {
        var type = $("input[name='order']:checked").val();
        if (type == "fiel") {
        var href = "fiel.php";
        sessionStorage.setItem('order','fiel');
        } else if (type == "total") {
        var href = "total.php";
        sessionStorage.setItem('order','total');
        } else if (type == "ultAno") {
        var href = "ultAno.php";
        sessionStorage.setItem('order','ultAno');
        }
        $('#listDiv').load(href).hide().fadeIn('fast'); 
        });
        
        function mostrarVinhos(id){
            $.ajax({
            url: 'vinhos.php', 
            dataType: 'html',
            type: 'post', 
            data: { id: id },
 
            success: function(result){
            $("#vinhos"+id).html(result);
            
        },
    error: function(){
           alert('Ouve um erro'); 
    }
        });
        }
        
        </script>
        <style>
            html, body {
                height:100%;
                margin: 0;
            }
            #geral{
                min-height: 100%;
                font-family: sans-serif;
               background-color: #f0f0f0;
               padding-bottom: 10%;
               position: relative;
            }
            #load{
                position: absolute;
                left: 40%;
                top: 30%;
                width: 10%;
                height: 10%;
            }
            header{
                height: 130px;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #AA5AE8;
            }
            #content{
               height: auto;
               padding: 0 30px 0 30px;
               margin-bottom: 100px;
            }
            #content h2{
               margin:0;
               padding: 30px;
            }
            #list{
                width: 100%;
                border-spacing: 30px;
                border: 3px solid #666666;
                border-radius: 8px;
                
            }
            #list h1,#list h2,#list h3{
                color: #660066
            }
            .nomevinho{
               color: #660066 
            }
            #order{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-bottom: 30px;
            }
            .indi{
                padding: 10px;
                background-color: #AA5AE8;
                border-radius: 8px;
                font-size: 15px;
                font-weight: bold;
                text-decoration: none;
                color: white;
                border: none;
                cursor: pointer;
                outline: none;
            }
            .indi:visited{
                text-decoration: none;
                color: white;
            }
            .indi:link{
                text-decoration: none;
                color: white;
            }
        </style>
        
    </head>
    <body>
        <div id="geral">
        <header>
            <h2>Sistema de controle - Velasquez</h2>
        </header>
        <div id="load">
            <img src="load.gif">
        </div>
        <div id="content">
            <div id="order">
            <h2>Listagem de clientes</h2>
            <form method="POST" action="index.php">
                <input type="radio" name="order" value="fiel" checked="true">Mostrar clientes ordenados pela fidelidade<br>
                <input type="radio" name="order" value="total">Mostrar clientes ordenados pelos gastos totais<br>
                <input type="radio" name="order" value="ultAno">Mostrar comprador da maior compra do último ano<br><br>
                <center><input type="submit" class="indi" value="Mostrar"></center>
            </form>
            </div>
        <?php
// pega o json com os clientes
$jsonFile = "http://www.mocky.io/v2/598b16291100004705515ec5";
$clientes = json_decode(file_get_contents($jsonFile), true); 
$numCli = sizeof($clientes);

//pega o json com as compras
$json2File = "http://www.mocky.io/v2/598b16861100004905515ec7";
$compras = json_decode(file_get_contents($json2File), true); 
$numCom = sizeof($compras);

//atribui as compras aos clientes
if(empty($_SESSION['clientesDados'])){
$i=0;
while($i<$numCli){
     $clientes[$i]['numComCli'] = 0; //campo dos clientes que vai armazenar numero de compras
     $clientes[$i]['gastoTotal'] = 0; //campo dos clientes que vai armazenar total de gastos
     $clientes[$i]['maiorCompra'] = array(); //campo dos clientes que vai armazenar valores da maior compra
     $clientes[$i]['maiorCompra']['valor'] = 0;
     $clientes[$i]['maiorCompra']['cod'] = "";
     $clientes[$i]['tipoVinho'] = array(); //campo dos clientes que vai armazenar os tipos de vinho que ele gosta
     $cpfSemDot = str_replace(".", "", $clientes[$i]['cpf']); //retira pontos do cpf
     $cpfClean = str_replace("-", "", $cpfSemDot); //retira traços do cpf
     
     $j=0;
     while($j<$numCom){//percorre as compras em busca de correspondencias com os clientes
         $codClisemDot = str_replace(".", "", $compras[$j]['cliente']);//retira pontos do codigo do cliente(arquivo das compras)
         $codCli = str_replace("-", "", $codClisemDot);//retira traços do codigo do cliente(arquivo das compras) (nesse caso, não há traços, mas por garantia é melhor ver isso)
         $anoCompra = substr($compras[$j]['data'],-4);//pega ano da compra
         if($codCli == $cpfClean){  //identifica correspondencias
             
         $clientes[$i]['numComCli']++;   // incrementa campo de numero de compras
         
         $clientes[$i]['gastoTotal'] += $compras[$j]['valorTotal']; //incrementa campo de valor total de compras
         
         if(($clientes[$i]['maiorCompra']['valor'] < $compras[$j]['valorTotal']) and($anoCompra=="2016")){ //verifica se a compra atual vai ou não ser substituida
               $clientes[$i]['maiorCompra']['valor'] = $compras[$j]['valorTotal'];//altera campo do valor
               $clientes[$i]['maiorCompra']['cod'] = $compras[$j]['codigo']; //altera campo do codigo(pode ser util ter esse codigo
            }
            
         $k=0;
         $numItens = sizeof($compras[$j]['itens']);
         while($k<$numItens){
         if(!in_array($compras[$j]['itens'][$k]['variedade'], $clientes[$i]['tipoVinho'])){//verifica se esse tipo de vinho já está armazenado
         $clientes[$i]['tipoVinho'][] = $compras[$j]['itens'][$k]['variedade']; //adiciona tipo de vinho da compra para o campo de tipos de vinhos que o cliente gosta
            }
         $k++;
              }  
            
         }
        $j++; 
     }
     $i++;
    }
    $_SESSION['clientesDados'] = $clientes;  
}    
//associa os tipos de vinhos aos vinhos
if(empty($_SESSION['vinhos'])){
$vinhos = array();    
$v= 0;
while ($v<$numCom){
    $vk = 0;
    $numItensV= sizeof($compras[$v]['itens']);
    while ($vk<$numItensV){
        $indexV = $compras[$v]['itens'][$vk]['variedade']; 
        $nameV = $compras[$v]['itens'][$vk]['produto'];
        if(!array_key_exists($indexV, $vinhos)){
        $vinhos[$indexV][] = $nameV;
        }else if(!in_array($nameV, $vinhos[$indexV])){
        $vinhos[$indexV][] = $nameV;    
        }
        $vk++;
    }
    
    $v++;
}

$_SESSION['vinhos'] = $vinhos;
}

echo "<div id='listDiv'>";
echo "</div>";     
        ?>
        </div>    
        <?php        
        include 'rodape.php';
        ?>
        </div>
    </body>
</html>
