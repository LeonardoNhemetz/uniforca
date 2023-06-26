<style>
    /* Estilos para a tabela */
    .table-container {
        overflow-x: auto; /* Adiciona uma barra de rolagem horizontal */
        max-width: 100%; /* Limita a largura da tabela para que ela não ultrapasse a largura da tela */
    }

    .table-container table {
        width: 100%; /* Define a largura da tabela como 100% */
        border-collapse: collapse;
    }

    .table-container th,
    .table-container td {
        padding: 8px;
        border: 1px solid #ccc;
    }
</style>

<?php

// faz a conexão com o banco de dados
include('configDB.php');

// Criar conexão com o MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}

// Selecionar todos os clientes da tabela "clientes"
$sql = "SELECT * FROM integrantes ORDER BY nome";
$result = $conn->query($sql);

// Função para atualizar os dados de um cliente
if(isset($_POST['atualizar'])) {
    $atualizacao = $_POST['atualizacao'];
    $coluna = $_POST['coluna'];
    $id_cliente = $_POST['id_cliente'];
    $sql = "UPDATE clientes SET ".$coluna."='".$atualizacao."' WHERE id_cliente=".$id_cliente;
    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso";
        header('Refresh:0');
    } else {
        echo "Erro ao atualizar dados: " . $conn->error;
    }
}

// Função para excluir um cliente
if(isset($_POST['excluir'])) {
    $id_cliente = $_POST['id_cliente'];
    $sql = "DELETE FROM clientes WHERE id_cliente=".$id_cliente;
    if ($conn->query($sql) === TRUE) {
        $sql = "SELECT COUNT(*) AS count FROM clientes";
        $result = $conn->query($sql);
        // Verificar se a consulta foi bem sucedida e obter o número de clientes cadastrados na tabela
        if ($result !== false && $result->num_rows > 0) {
            $linha = $result->fetch_assoc();
            $quantidade_de_clientes = $linha['count'];
            include('configDBlogin.php');
            $quant = intval($quantidade_de_clientes);
            $sql = "UPDATE cadastros SET clientes_usados = $quant WHERE login_imob = '$valor' ";

            if ($mysqli->query($sql) === TRUE) {
                mysqli_close($mysqli);
                header('Refresh:0');
            }

            
        }
        
    } else {
        echo "Erro ao excluir cliente: " . $conn->error;
    }
}

echo '<button class="btn btn-primary" onclick="window.location.href=\'CrudIntegrantes/cadastrar_integrante.html\'">Cadastrar Novo Integrante</button>';


// Exibir tabela com todos os clientes e opções de atualizar e excluir
if ($result->num_rows > 0) {
    echo '<div style="overflow-x: auto; max-width: 100%;">';
    echo "<table>
    <tr>
        <th>Numeral</th>
        <th>Nome</th>
        <th>Bloco</th>
        <th>Região</th>
        <th>Igreja</th>
        <th>RG</th>
        <th>CPF</th>
        <th>Data de Nascimento</th>
        <th>Whatsapp</th>
        <th>Endereço Casa</th>
        <th>Posição na Igreja</th>
        <th>Posição no Uniforça</th>
        <th>Departamento</th>
        <th>Entrada no UF</th>
        <th>Possui CFU?</th>
        <th>Batismo nas Águas</th>
        <th>Batismo com o Espírito Santo</th>
        <th>Profissão</th>
        <th>Endereço do Trabalho</th>
        <th>Entrada</th>
        <th>Saída</th>
        <th>curso/escola?</th>
    </tr>";
    // Saída de dados de cada linha
    $i = 1;
    while($row = $result->fetch_assoc()) {    
        echo '<style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th {
                horizontal-align: middle;
                line-height: 1;
            }

            td {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .scrollable-table {
                overflow-x: auto;
                max-width: 100%;
            }
        </style>';    
        echo "
        <tr>
            <td>".$i."</td>
            <td >".$row["nome"]."</td>
            <td>".$row["bloco"]."</td>
            <td>".$row["regiao"]."</td>
            <td>".$row["igreja"]."</td>
            <td>".$row["rg"]."</td>
            <td>".$row["cpf"]."</td>
            <td>".$row["data_nascimento"]."</td>
            <td>".$row["whatsapp"]."</td>
            <td>".$row["end_casa"]."</td>
            <td>".$row["posicao_igreja"]."</td>
            <td>".$row["posicao_uf"]."</td>
            <td>".$row["departamento"]."</td>
            <td>".$row["entrada_uf"]."</td>
            <td>".$row["cfu"]."</td>
            <td>".$row["data_bat_aguas"]."</td>
            <td>".$row["data_bat_es"]."</td>
            <td>".$row["profissao"]."</td>
            <td>".$row["end_trab"]."</td>
            <td>".$row["horario_entrada_trab"]."</td>
            <td>".$row["horario_saida_trab"]."</td>
            <td>".$row["curso_escola"]."</td><td>
        <form method='post' action=''>
        <input type='text' name='atualizacao' placeholder='Digite a atualização'>
        <select name='coluna'><option value='nome_cliente'>Nome</option>
        <option value='telefone_cliente'>Telefone</option>
        <option value='email_cliente'>Email</option>
        <option value='c_a'>Comprar/Alugar</option>
        <option value='tipo_imovel'>Tipo do imóvel</option>
        <option value='m2'>M²</option>
        <option value='preco_inicial'>Preço Inicial</option>
        <option value='preco_final'>Preço Final</option></select>
        <input type='hidden' name='id_cliente' value='".$row["id_cliente"]."'>
        <button type='submit' value='atualizar' name='atualizar' class='btn btn-secondary btn-sm'>Atualizar</button>
        <button type='submit' value='excluir' name='excluir' class='btn btn-danger btn-sm'>Excluir</button>
        </form></td></tr>";
        $i++;
    }
    echo "</table>";
} else {
    echo "0 resultados";
}



$conn->close();
?>
