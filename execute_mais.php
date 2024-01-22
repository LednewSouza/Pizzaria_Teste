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
    $destino = "";  // Inicializa a variável $destino

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
        $stmt_pro = $pdo->prepare("INSERT INTO `promocoes`(`imagem`, `nome`, `ingredientes`, `pequena`, `media`, `grande`, `data_in`, `data_out`) VALUES ('$destino', '$sabor', '$itens', '$peq', '$med', '$gra', '$data_in', '$data_out')");
        $stmt_pro->execute();
    } 

    $stmt = $pdo->prepare("INSERT INTO `cardapio`(`imagem`, `nome`, `ingredientes`, `pequena`, `media`, `grande`, `tipo`) VALUES ('$destino', '$sabor', '$itens', '$peq', '$med', '$gra', '$tipo')");

    if ($stmt->execute()) {
        $response = 'success';
    } else {
        $response = 'error';
    }
    echo $response;
}

?>