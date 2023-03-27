<?php

include('configDB.php');
use Twilio\Rest\Client;
session_start();
$nome = $_COOKIE['nome'];
// Criar conexão com o MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}

// Selecionar todos os clientes da tabela "clientes"
$sql = "SELECT * FROM imoveis";
$result = $conn->query($sql);

// Função para atualizar os dados de um cliente
if(isset($_POST['atualizar'])) {
    $atualizacao = $_POST['atualizacao'];
    $coluna = $_POST['coluna'];
    $id_imovel = $_POST['id_imovel'];
    $sql = "UPDATE imoveis SET ".$coluna."='".$atualizacao."' WHERE id_imovel=".$id_imovel;
    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso";
        header('Refresh:0');
    } else {
        echo "Erro ao atualizar dados: " . $conn->error;
    }
}

// Função para excluir um cliente
if(isset($_POST['excluir'])) {
    $id_imovel = $_POST['id_imovel'];
    $sql = "DELETE FROM imoveis WHERE id_imovel=".$id_imovel;
    if ($conn->query($sql) === TRUE) {
        header('Refresh:0');
        
    } else {
        echo "Erro ao excluir imovel: " . $conn->error;
    }
}

if(isset($_POST['alerta'])) {
    //Verificar se os alertas da imobiliaria estão no máximo
    require('configDBlogin.php');
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
            //!verificar numero do twilio
            $query = "SELECT twilio_number FROM cadastros WHERE login_imob='$nome'";
            $resultado = mysqli_query($mysqli, $query);
            if (mysqli_num_rows($resultado) == 1) {
                $twilioNumber = $_SESSION['twilio_number'] = mysqli_fetch_assoc($resultado)['twilio_number'];
            }            
            $conn->close();

            //Envia alertas
            include('configDB.php');
            require_once 'vendor/autoload.php';
            // Criar conexão com o MySQL
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão com o banco de dados falhou: " . $conn->connect_error);
            }
            $tipo_imovel = $_POST['tipo_imovel'];
            $c_a_imovel = $_POST['c_a_imovel'];
            $m2_imovel = $_POST['m2_imovel'];
            $cidade_imovel = $_POST['cidade_imovel'];
            $corpo_msg_imovel = $_POST['corpo_msg_imovel'];
            $preco_imovel = $_POST['preco_imovel'];
            $link_imovel = $_POST['link_imovel'];
            $sql = "SELECT nome_cliente,telefone_cliente FROM clientes WHERE c_a LIKE '$c_a_imovel' AND tipo_imovel LIKE '$tipo_imovel' AND m2 LIKE '$m2_imovel' AND cidade LIKE '$cidade_imovel' AND preco_inicial <= '$preco_imovel' AND preco_final >= '$preco_imovel'";
            $result = $conn->query($sql);
            //Autenticação na API do Twilio
            $sid = "AC3e5a99b9b896f24f1b1d9d144a650903";
            $token = "0dae383cf41f1f20b6ae45d0fcb29bf4";
            $client = new Client($sid, $token);
            

            //Enviar msg no zap para cada numero encontrado
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $alerta = "*Olá ".$row["nome_cliente"].", talvez encontramos o imóvel que procura!*\n\n".$corpo_msg_imovel."\n\n".$link_imovel."";

                    trim($alerta);
                    
                    //Enviar a mensagem
                    $message = $client->messages->create("whatsapp:".$row["telefone_cliente"], array('from' => "whatsapp:".$twilioNumber, 'body' => $alerta));

                    $conn = null;
                }
                
                
            }
          

        }
            
    }  
    header('Refresh:0');

}

echo '<button class="btn btn-primary" onclick="window.location.href=\'CrudImovel/cadastrar_imovel.html\'">Cadastrar Novo Imóvel</button>';

// Exibir tabela com todos os clientes e opções de atualizar e excluir
if ($result->num_rows > 0) {
    // monta o cabeçalho da tabela
    echo "<table>
    <tr>
        <th>Numeral</th>
        <th>Tipo do Imóvel</th>
        <th>C/A</th>
        <th>M²</th>
        <th>Cidade</th>
        <th>Corpo da Mensagem</th>
        <th>Preço</th>
        <th>Link</th>
        <th>Ações</th> 
    </tr>";
    // Saída de dados de cada linha
    $i = 1;
    while($row = $result->fetch_assoc()) {        
        echo "
        <tr>
            <td>".$i."</td>
            <td>".$row["tipo_imovel"]."</td>
            <td>".$row["c_a_imovel"]."</td>
            <td>".$row["m2_imovel"]."</td>
            <td>".$row["cidade_imovel"]."</td>
            <td>".$row["corpo_msg_imovel"]."</td>
            <td>".$row["preco_imovel"]."</td>
            <td><a href='".$row["link_imovel"]."'>".$row["link_imovel"]."</a></td>
            <td>
            <form method='post' action=''>
                <input type='text' name='atualizacao' placeholder='Digite a atualização'>
                <select name='coluna'>
                    <option value='tipo_imovel'>Tipo do Imóvel</option>
                    <option value='c_a_imovel'>C/A</option>
                    <option value='m2_imovel'>M²</option>
                    <option value='cidade_imovel'>Cidade</option>
                    <option value='corpo_msg_imovel'>Corpo da Mensagem</option>
                    <option value='preco_imovel'>Preço</option>
                    <option value='link_imovel'>Link</option>
                </select>
                <input type='hidden' name='id_imovel' value='".$row["id_imovel"]."'>
                <input type='hidden' name='tipo_imovel' value='".$row["tipo_imovel"]."'>
                <input type='hidden' name='c_a_imovel' value='".$row["c_a_imovel"]."'>
                <input type='hidden' name='m2_imovel' value='".$row["m2_imovel"]."'>
                <input type='hidden' name='cidade_imovel' value='".$row["cidade_imovel"]."'>
                <input type='hidden' name='corpo_msg_imovel' value='".$row["corpo_msg_imovel"]."'>
                <input type='hidden' name='preco_imovel' value='".$row["preco_imovel"]."'>
                <input type='hidden' name='link_imovel' value='".$row["link_imovel"]."'>
                <button type='submit' value='atualizar' name='atualizar' class='btn btn-secondary btn-sm'>Atualizar</button>
                <button type='submit' value='excluir' name='excluir' class='btn btn-danger btn-sm'>Excluir</button>
                <button type='submit' value='alerta' name='alerta' class='btn btn-secondary btn-sm'>Enviar Alerta</button>
            </form>
            </td>
        </tr>";
        $i++;
    }
    // fecha a tabela
    echo "</table>";
    
} else {
    echo "0 resultados";
    
}




?>