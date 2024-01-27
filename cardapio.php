<?php 
//phpinfo();
include 'conect.php';

// Exemplo de consulta preparada
$stmt_pro = $pdo->prepare("SELECT * FROM promocoes");
$stmt_pro->execute();
$promo = $stmt_pro->fetchAll(PDO::FETCH_ASSOC);

$sele_opt = $pdo->prepare('SELECT DISTINCT "tipo" FROM "cardapio" WHERE "tipo" != \'\'');
$sele_opt->execute();
$resultados = $sele_opt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ids'])) {
    header('Content-Type: application/json');
    $ida = $_POST['ids'];
    $type = $_POST['tips'];

    if ($type == 'promocao') {
        $exibi_pedido = $pdo->prepare('SELECT * FROM "promocoes" WHERE "id" = :ides');
    } else {
        $exibi_pedido = $pdo->prepare('SELECT * FROM "cardapio" WHERE "id" = :ides');
    }
    $exibi_pedido->bindParam(':ides', $ida, PDO::PARAM_INT); 
    $exibi_pedido->execute();
    $conjunto['promocoes'] = $exibi_pedido->fetchAll(PDO::FETCH_ASSOC);

    $item = $pdo->prepare('SELECT * FROM "extras"');
    $item->execute();
    $conjunto['extras'] = $item->fetchAll(PDO::FETCH_ASSOC);

    
    echo json_encode($conjunto);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tamanho'])) {
    header('Content-Type: application/json');
    $di = $_POST['id'];
    $oque = $_POST['oque'];
    $valor_p = $_POST['tamanho'];
    $extras = isset($_POST['extra']) ? $_POST['extra'] : '';
    

    if ($oque != 'promocao') {
        $oque == 'cardapio';
    }
    $user = 'teste';
    $int_stmt = $pdo->prepare("INSERT INTO `carro` (`usuario`, `tipo`, `id_pedido`, `tamanho`) VALUES ('$user','$oque','$di','$valor_p')");
    if ($int_stmt->execute()) {
        $consequencia = 'succeso';
    }

    echo json_encode($consequencia);
    exit;
}

include 'header.php'; 
?>
<html lang="pt">
<head>
    <style>
        body {
            position: relative;
            top: -21px;
        }
        ::-webkit-scrollbar {
            background-color: #f5f5f5;
            width: 12px;
        }
        ::-webkit-scrollbar-thumb {
            background-color: #888;
        }
        .pedido_sele {
            overflow: auto;
            padding: 60px 40px 20px;
            position: fixed;
            right: -550px;
            width: 450px;
            height: 709px;
            background-color: #ccc;
            transition: right 0.5s;
        }
        .select {
            right: 0px;
        }

        #lista_tds {
            padding-top: 30px;
            width: calc(100% - 0px);
            transition: width 0.5s;
        }
        .diminuir {
            width: calc(100% - 500px) !important;
        }

        .cardapio {
            position: relative;
            top: -30px;
            margin-bottom: 80px;
        }
        table {
            width: 100%;
        }
        #lista_tds tr{
            display: flex;
            justify-content: space-evenly;
        }
        #lista_tds td {
            width: 280px;
            height: 450px;
            display: inline-flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
        }
        #lista_tds p {
            margin: 10px 0 0;
        }
        .nome {
            margin: 10px 0 0;
        }
        .cardapio img{
            width: -webkit-fill-available;
            height: 250px
        }

        .valores input, .fase_final input {
            display: none;
        }
        .valores label {
            background-color: #7878eb;
            font-size: 18px;
            width: 60px;
            padding: 10px;
            text-align: center;
        }
        .valores {
            display: flex;
        }
        .fase_final span {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100px
        }
        .fase_final p {
            font-size: 20px;
            margin: 0 0 5px;
        }
        .fase_final {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
        }
        .fase_final label {
            background-color: #c58b8b;
            font-size: 18px;
            text-align: center;
            width: 100%;
            padding: 10px 0;
            margin-bottom: 5px;
            transition: background-color 0.3s;
        }

        .fase_final label.ativo {
            background-color: #cc4141;
            font-weight: bold;
        }
        .val {
            margin-right: 20px;
        }

        .lista {
            margin: 30px;
            max-height: 100px;
            overflow: hidden;
            transition: max-height 0.8s;
        }
        .open {
            max-height: 100vh;
        }
        .title_pro, .title {
            border-bottom: 1px solid;
            margin: 0px 30px;
            padding: 30px 30px;
        }
        .title {
            border-top: 1px solid;
        }

        
        .classe-dos-primeiros, .classe-do-ultimo {
            text-align: center;
            height: 50px;
            width: 130px;
            background-color: wheat;
            transition: background-color 0.3s;
        }
        .classe-dos-primeiros {
            margin-right: 26px;
        }
        .classe-dos-primeiros label, .classe-do-ultimo label {
            background-color: wheat;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .classe-dos-primeiros label.maisex, .classe-do-ultimo label.maisex {
            background-color: #f9c86b;
        }

        #table_extras tr{
            display: flex;
            margin-bottom: 25px;
        }
        .hidden {
            display: none;
        }

        .acoes_final {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }
        .acoes_final button{
            border-radius: 20px;
            font-size: 16px;
            border: none;
            background-color: #7878eb;
            padding: 20px 0px;
            margin-bottom: 20px;
        }
        .ult_bt {
            border: 3px solid #7878eb !important;
            background-color: #ccc !important;
        }
        .ult_bt:active {
            background-color: #aaa !important;
            transition-duration: 0.1s;
        }
    </style>
</head>
<body>
    <div class="cardapio">
    <?php
        echo '<div class="pedido_sele" id="pedido_sele"></div>';
    ?>

<?php
    echo '<div id="lista_tds">';
        echo '<div id="list_pro" class="lista open">';
            echo "<h1 class='title_pro' id='pro' onclick='abrir(this)'>Promoções</h1>";
        echo '<table class="menus">';
        
        $counter = 0;
        $id_pro = 0;
        foreach ($promo as $pro) {

            if ($counter % 3 === 0) {
                echo '<tr>';
            }

            echo '
                <td data-name="promocao" id="'. $pro['id'] .'" onclick="sele_pedido(this)">
                    <div class="info_pizza">';
                        if ($pro['imagem'] == '') {
                            echo '<img src="sem_pizza.jpg">';
                        } else {
                            echo '<img src="'. $pro['imagem'] .'">'; 
                        }
                        echo '<h2 class="nome">' . $pro['nome'] . '</h2>
                        <p>'. $pro['ingredientes'] .'</p>
                    </div>

                    <div class="valores">';
                        
                        if ($pro['pequena'] != '' && $pro['pequena'] != 0) {
                            echo '<label class="val" for="P_pro' . $id_pro . '">€ '.$pro['pequena'].'</label>
                            <input type="radio" id="P_pro' . $id_pro . '" name="tamanho" value="">';
                        }
                        $id_pro += 1;
                        if ($pro['media'] != '' && $pro['media'] != 0) {
                            echo '<label class="val" for="M_pro' . $id_pro . '">€ '.$pro['media'].'</label>
                            <input type="radio" id="M_pro' . $id_pro . '" name="tamanho" value="">';
                        }
                        $id_pro += 1;
                        if ($pro['grande'] != '' && $pro['grande'] != 0) {
                            echo '<label for="G_pro' . $id_pro . '">€ '. $pro['grande'] .'</label>
                            <input type="radio" id="G_pro' . $id_pro . '" name="tamanho" value="">';
                        }
                        $id_pro += 1;
                        echo '
                    </div>
                </td>';

            // Fecha a linha da tabela a cada 3 iterações
            if ($counter % 3 === 2) {
                echo '</tr>';
            }

            $counter++;

        }

        if ($counter % 3 !== 0) {
            echo '</tr>';
        }
        echo '</table>';
        echo '</div>';
        

        foreach ($resultados as $cont => $resultado) {
            $tipoEncontrado = $resultado['tipo'];
            echo '<div id="list_l'.$cont.'" class="lista">';
                echo "<h1 class='title' id='l".$cont."' onclick='abrir(this)'>" . $tipoEncontrado . "</h1>";
                echo '<table>';
                    ${'counter_' . $tipoEncontrado} = 0;
                    ${'id_pro_' . $tipoEncontrado} = 0;
                    
                    echo '<div class="' . $tipoEncontrado . '">';
                        $stmt = $pdo->prepare("SELECT * FROM cardapio WHERE tipo = '$tipoEncontrado'");
                        $stmt->execute();
                        $geral = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($geral as $gerais) {
                            if (${'counter_' . $tipoEncontrado} % 3 === 0) {
                                echo '<tr>';
                            }
                
                            echo '
                                <td data-name="cardapio" id="'. $gerais['id'] .'" onclick="sele_pedido(this)">
                                    <div class="info_pizza">';
                                        if ($gerais['imagem'] == '') {
                                            echo '<img src="sem_pizza.jpg">';
                                        } else {
                                            echo '<img src="'. $gerais['imagem'] .'">'; 
                                        }
                                        echo '<h2 class="nome">' . $gerais['nome'] . '</h2>
                                        <p>'. $gerais['ingredientes'] .'</p>
                                    </div>
                
                                    <div class="valores">';
                                        
                                        if ($gerais['pequena'] != '' && $gerais['pequena'] != 0) {
                                            echo '<label class="val" for="P_' . $tipoEncontrado, ${'id_pro_' . $tipoEncontrado} . '">€ '.$gerais['pequena'].'</label>
                                            <input type="radio" id="P_' . $tipoEncontrado, ${'id_pro_' . $tipoEncontrado} . '" name="tamanho" value="">';
                                        }
                                        ${'id_pro_' . $tipoEncontrado} += 1;
                                        if ($gerais['media'] != '' && $gerais['media'] != 0) {
                                            echo '<label class="val" for="M_' . $tipoEncontrado, ${'id_pro_' . $tipoEncontrado} . '">€ '.$gerais['media'].'</label>
                                            <input type="radio" id="M_' . $tipoEncontrado, ${'id_pro_' . $tipoEncontrado} . '" name="tamanho" value="">';
                                        }
                                        ${'id_pro_' . $tipoEncontrado} += 1;
                                        if ($gerais['grande'] != '' && $gerais['grande'] != 0) {
                                            echo '<label for="G_' . $tipoEncontrado, ${'id_pro_' . $tipoEncontrado} . '">€ '. $gerais['grande'] .'</label>
                                            <input type="radio" id="G_' . $tipoEncontrado,   ${'id_pro_' . $tipoEncontrado} . '" name="tamanho" value="">';
                                        }
                                        ${'id_pro_' . $tipoEncontrado} += 1;
                                        echo '
                                    </div>
                                </td>';
                
                            // Fecha a linha da tabela a cada 3 iterações
                            if (${'counter_' . $tipoEncontrado} % 3 === 2) {
                                echo '</tr>';
                            }
                
                            ${'counter_' . $tipoEncontrado}++;

                        }
                        $cels_vazias = 3 - (${'counter_' . $tipoEncontrado} % 3);
                        for ($i = 0; $i < $cels_vazias; $i++) {
                            echo '<td></td>';
                        }

                        if (${'counter_' . $tipoEncontrado} % 3 !== 0) {
                            echo '</tr>';
                        }
                    echo '</div>';
                echo '</table>';
            echo '</div>';
        }
    echo '</div>';
    ?>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    var names = '';
    function abrir(id_sele) {
        id_div = 'list_' + id_sele.id;
        document.getElementById(id_div).classList.toggle('open');
    }
    function sele_pedido(pedido) {
        var elementoIdGuardado = document.getElementById('id_guardado');
        var id_gua = '';
        if (elementoIdGuardado) {
            id_gua = elementoIdGuardado.textContent || elementoIdGuardado.innerText;
        }

        var id_pedido = pedido.id;
        names = pedido.dataset.name;
        
        if (id_gua != id_pedido) {
            abarecer()
            if (id_gua != id_pedido && id_gua != '') {
                setTimeout(abarecer, 1000)
                setTimeout(gerar, 800)
            }

            if (id_gua == '') {
                gerar();
            }
            function gerar() {
                $(".pedido_sele").empty();
                
                var elementoIdGuardado = document.getElementById('id_guardado');

                if (elementoIdGuardado) {
                    var id_gua = elementoIdGuardado.textContent || elementoIdGuardado.innerText;
                    console.log(id_gua);
                }

                $.post('', { ids: id_pedido, tips: names }, function(resposta) {
                    var promocoes = resposta.promocoes[0];
                    var extras = resposta.extras;
                    
                    if (promocoes.imagem == '') {
                        var exibicao = '<img src="sem_pizza.jpg" style="width: 450px; height: 450px;">';
                    } else {
                        var exibicao = '<img src="' + promocoes.imagem + '" style="width: 450px; height: 450px;">';
                    }
                    exibicao += '<p style="display:none;" id="id_guardado">' + id_pedido + '</p>';
                    exibicao += '<h1>' + promocoes.nome + '</h1>';
                    exibicao += '<div class="fase_final">';
                        for (var v = 1; v <= 3; v++) {
                            var tamanho;
                            
                            if (v === 1) {
                                tamanho = 'pequena';
                            } else if (v === 2) {
                                tamanho = 'media';
                            } else if (v === 3) {
                                tamanho = 'grande';
                            }

                            var Tam = tamanho.charAt(0).toUpperCase() + tamanho.slice(1);
                            if (promocoes[tamanho] != 0) {
                                exibicao += '<span name="valor_' + v + '"><input class="hidden" type="radio" name="valor_amostra" id="valor_' + v + '" value="' + promocoes[tamanho] + '">';
                                exibicao += '<label for="valor_' + v + '" onclick="esse(this)">€ ' + promocoes[tamanho] + '</label><p>' + Tam + '</p></span>';
                            } else {
                                exibicao += '<span></span>';
                            }
                        }

                    exibicao += '</div>';
                    
                    exibicao += '<div>';
                        exibicao += '<table id="table_extras">';
                        for (var i = 0; i < extras.length; i++) {
                            if (i % 3 === 0) {
                                exibicao += '<tr>';
                            }
                            var classeTd = (i % 3 === 0 || i % 3 === 1) ? 'classe-dos-primeiros' : 'classe-do-ultimo';
                            exibicao += '<td class="' + classeTd + '"><input class="hidden" type="checkbox" name="extras" id="' + extras[i].id + '" value="' + extras[i].id + '">';
                            exibicao += '<label for="' + extras[i].id + '" onclick="esse_prod(this)">' + extras[i].extra + '<br>€ ' + extras[i].valor + '</label></td>';

                            if (i === extras.length - 1 && i % 3 !== 2) {
                                for (var j = 0; j < 2 - (i % 3); j++) {
                                    exibicao += '<td></td>';
                                }
                            }   

                            if ((i % 3 === 2 || i === extras.length - 1) && i > 0) {
                                exibicao += '</tr>';
                            }
                        }

                        exibicao += '</table>';
                    exibicao += '</div>';
                    


                    exibicao += '<div style="display: flex; justify-content: space-between; align-items: center;"><h1>Total:</h1><h1 style="margin: 0;" id="valor_final">€ ╸╸╸</h1></div>'
                    
                    exibicao += '<div class="acoes_final"><button>Finalizar Pedido</button>  <button class="ult_bt" onclick="carrinho()">Adicionar ao Carrinho</button></div>';
                    $(".pedido_sele").html(exibicao);
                }, 'json')
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX:", textStatus, errorThrown);
                }); 
            }
        }
    }

    function abarecer() {
        document.getElementById("pedido_sele").classList.toggle('select');
        document.getElementById("lista_tds").classList.toggle('diminuir');
    }

    var valor_ = '';
    function esse(elemento) {
        var antigo = document.querySelector('.ativo');

        var rótulos = document.querySelectorAll('label');
        var valorFinalElement = document.getElementById('valor_final');
        rótulos.forEach(function(rótulo) {
            rótulo.classList.remove('ativo');
        });

        elemento.classList.add('ativo');
        
        var valorAtual = valorFinalElement.textContent;
        var matchs = valorAtual.match(/\d+(\.\d+)?/);
        var valorAtuals = parseFloat(matchs);
        var valorAtingo = 0;

        if (valorAtual != '€ ╸╸╸' && antigo != null) {
            var valorAntigo = antigo.textContent || antigo.innerText;
            var antigoMutado = valorAntigo.match(/\d+\.\d+/);
            valorAtingo = parseFloat(antigoMutado[0]);
        }

        var valorPizza = elemento.textContent || elemento.innerText;
        var mudando = valorPizza.match(/\d+\.\d+/);
        var valor = parseFloat(mudando[0]); 

        if (valorAtual != '€ ╸╸╸') {
            var conta = valorAtuals - valorAtingo + valor;
        } else {
            var conta = valor;
        }
        var valorFor = elemento.getAttribute('for');
        var spanSelecionado = document.querySelector('span[name="' + valorFor + '"] p');
        valor_ = spanSelecionado.textContent;
        
        atualizarTextoSoma(conta);
    }

    function esse_prod(elemento) {
        var classeAdicionada = elemento.classList.contains('maisex');
        elemento.classList.toggle('maisex');
        
        var valorFinalElement = document.getElementById('valor_final');
        var valorAtual = valorFinalElement.textContent;
        var matchs = valorAtual.match(/\d+(\.\d+)?/);
        var valorAtuals = parseFloat(matchs);

        var textoProduto = elemento.textContent || elemento.innerText;
        var match = textoProduto.match(/\d+(\.\d+)?/);
        var valor = parseFloat(match[0]);

        if (valorAtual == '€ ╸╸╸') {
            var conta = valor;
        } else {
            if (classeAdicionada) {
                var conta = valorAtuals - valor;
            } else {
                var conta = valorAtuals + valor;
            }
        }
        atualizarTextoSoma(conta);
    }

    function formatarNumeroParaDuasCasasDecimais(numero) {
        return parseFloat(numero).toFixed(2);
    }

    function atualizarTextoSoma(somaValores) {
        var elementoValorFinal = document.getElementById('valor_final');
        elementoValorFinal.textContent = '€ ' + formatarNumeroParaDuasCasasDecimais(somaValores);
    }

    function ajustarAlturaDaDiv() {
        var alturaTotal = window.innerHeight;

        var alturaUtil = alturaTotal - 150;
        document.getElementById("pedido_sele").style.height = alturaUtil + "px";
    }
    ajustarAlturaDaDiv();

    window.addEventListener("resize", ajustarAlturaDaDiv);


    function carrinho() {
        var opcoes = document.getElementsByName('valor_amostra');
        var checkboxes = document.getElementsByName('extras');

        var valoresSelecionados = [];

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                valoresSelecionados.push(checkbox.value);
            }
        });
        
        for (var i = 0; i < opcoes.length; i++) {
            if (opcoes[i].checked) {
                var pedido = opcoes[i].value
                var id_sel = document.getElementById('id_guardado').textContent;
                $.post('', { oque: names, id: id_sel, tamanho: valor_, extra: valoresSelecionados, }, function(resposta) {
                    console.log(resposta);
                });
                break;
            }
        }
    }
</script>
</body>
</html>
