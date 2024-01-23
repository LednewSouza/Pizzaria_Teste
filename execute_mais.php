<?php
include 'conect.php';

if ($_POST['adicionar'] == true) {
    // Recuperar os dados do formulário
        $sabor = $_POST["sabor"];
        $itens = $_POST["itens"];
        $peq = $_POST["peq"];
        $med = $_POST["med"];
        $gra = $_POST["gra"];
        $tipo = $_POST['tipo'];

        if ($tipo == 'Novo') {
            $tipo = $_POST['new_tipo'];
        }
         // Processar a imagem (salvar em algum diretório no servidor, se necessário)
         if ($_FILES['img']['name'] != '') {
            $imagem_nome = $_FILES["img"]["name"];
            $imagem_temp = $_FILES["img"]["tmp_name"];

            // Construir o caminho completo para o destino da imagem
            $destino = "imagens/pizzas/" . $imagem_nome;

            // Move o arquivo temporário para o destino
            move_uploaded_file($imagem_temp, $destino);
        }

        if ($tipo == 'Promocao') {
            $data_in = $_POST['ini'];
            $data_out = $_POST['out'];
        
            $stmt = $pdo->prepare("INSERT INTO promocoes (imagem, nome, ingredientes, pequena, media, grande, data_in, data_out) VALUES (:imagem, :nome, :ingredientes, :pequena, :media, :grande, :data_in, :data_out)");
            
            $stmt->bindParam(':imagem', $destino, PDO::PARAM_STR);
            $stmt->bindParam(':nome', $sabor, PDO::PARAM_STR);
            $stmt->bindParam(':ingredientes', $itens, PDO::PARAM_STR);
            $stmt->bindParam(':pequena', $peq, PDO::PARAM_STR);
            $stmt->bindParam(':media', $med, PDO::PARAM_STR);
            $stmt->bindParam(':grande', $gra, PDO::PARAM_STR);
            $stmt->bindParam(':data_in', $data_in, PDO::PARAM_STR);
            $stmt->bindParam(':data_out', $data_out, PDO::PARAM_STR);

        } else {
            $stmt = $pdo->prepare("INSERT INTO cardapio (imagem, nome, ingredientes, pequena, media, grande, tipo) VALUES (:imagem, :nome, :ingredientes, :pequena, :media, :grande, :tipo)");
            
            $stmt->bindParam(':imagem', $destino, PDO::PARAM_STR);
            $stmt->bindParam(':nome', $sabor, PDO::PARAM_STR);
            $stmt->bindParam(':ingredientes', $itens, PDO::PARAM_STR);
            $stmt->bindParam(':pequena', $peq, PDO::PARAM_STR);
            $stmt->bindParam(':media', $med, PDO::PARAM_STR);
            $stmt->bindParam(':grande', $gra, PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            $response = 'success';
        } else {
            $response = 'error';
        }
        echo $response;
}

?>