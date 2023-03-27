<?php

/*require_once '../vendor/autoload.php';
use Twilio\Rest\Client;*/

// faz a conexão com o banco de dados
require('../configDB.php');

// Criar conexão com o MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $tipoImovel = $_POST['tipo_imovel'];
    $comprarOuAlugar = $_POST['comprar_alugar'];
    $corpoMensagem = $_POST['corpo_mensagem'];
    $preco_imovel = $_POST['preco_imovel'];
    $m2 = $_POST["m2"];
    $cidade = $_POST["cidade"];
    $link = $_POST['link'];

    $sql = "INSERT INTO imoveis (tipo_imovel, c_a_imovel, corpo_msg_imovel, preco_imovel, m2_imovel, cidade_imovel, link_imovel)
        VALUES ('$tipoImovel', '$comprarOuAlugar', '$corpoMensagem', '$preco_imovel', '$m2', '$cidade', '$link')";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: ../main.php?imoveis');
    }
    else {
        echo "Erro ao cadastrar: " . mysqli_error($conn);	
      } 
    $conn->close();
}

    /*$sql = "SELECT nome_cliente,telefone_cliente FROM clientes WHERE c_a LIKE '$comprarOuAlugar' AND tipo_imovel LIKE '$tipoImovel' AND m2 LIKE '$m2' AND cidade LIKE '$cidade' AND preco_inicial <= '$preco_imovel' AND preco_final >= '$preco_imovel'";
    $result = $conn->query($sql);

    //Autenticação na API do Twilio
    $sid = "AC3e5a99b9b896f24f1b1d9d144a650903";
    $token = "fc59aebf91bf1dfd95d05639735e94ad";
    $client = new Client($sid, $token);
    $twilioNumber = "+14155238886";

    //Enviar msg no zap para cada numero encontrado
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $alerta = "*Olá ".$row["nome_cliente"].", talvez encontramos o imóvel que procura!*\n\n".$corpoMensagem."\n\n".$link."";

            trim($alerta);
            
            //Enviar a mensagem
            $message = $client->messages->create("whatsapp:".$row["telefone_cliente"], array('from' => "whatsapp:".$twilioNumber, 'body' => $alerta));

            $conn = null;
        }
        //Verificar se os alertas da imobiliaria estão no máximo
        require('../configDBlogin.php');
        $imob = $_COOKIE['nome'];
        $sql_verifica_alertas = "SELECT max_alertas, alertas_usados FROM cadastros WHERE login_imob = '$imob'";
        $result = $mysqli->query($sql_verifica_alertas);
        while($row = $result->fetch_assoc()) { 
            $max_alertas = intval($row["max_alertas"]);
            $alertas_usados = intval($row["alertas_usados"]);
            echo $max_alertas."\n\n".$alertas_usados;
            //Verificar se os alertas da imobiliaria estão no máximo
            if($max_alertas == intval($alertas_usados)){
                echo "<h1>O limite de alertas/mês ja foi atingido!</h1>";
            }
            //Se não estiverem no maximo, adiciona +1
            else{
                $alertas_usados++;
                $sql_adiciona_alertas = "UPDATE cadastros SET alertas_usados = '$alertas_usados' WHERE login_imob = '$imob'";
                $mysqli->query($sql_adiciona_alertas);
            }
                
        }
        
    }


}else{
    echo "erro";
}*/

?>