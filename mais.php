<?php
    include 'conect.php';
    include 'header.php';

    $sele_opt = $pdo->prepare('SELECT DISTINCT "tipo" FROM "cardapio" WHERE "tipo" != \'\'');
    $sele_opt->execute();
    $resultados = $sele_opt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .cardapio {
        margin-top: 140px;
        margin-bottom: 40px;
        width: 100%;
        display: flex;
        justify-content: center;
    }
    .cardapio form{
        display: flex;
        flex-direction: column;
    }
    .central {
        width: 30%;
        height: fit-content;
    }
    .central div{
        display: flex;
        justify-content: space-between;
    }
    .central span{
        display: flex;
        flex-direction: column;
        margin: 10px 0px;
    }
    .central input{
        border-radius: 10px;
        border: 1px solid #aaa;
        height: 25px;
        padding: 10px;
    }
    .central select{
        border-radius: 10px;
        border: 1px solid #aaa;
        padding: 10px;
    }
    .label_img {
        background-color: #ddd;
        width: max-content;
        padding: 15px 25px;
    }
    #img, #imagemSelecionada {
        display: none;
    }
    #imagemSelecionada {
        width: 100%;
        margin-bottom: 10px;
    }

    .lado_lado span{
        width: 30%;
    }
    .lado_data span{
        width: 48%;
    }
    
    #add {
        width: 100%;
        font-size: 20px;
        font-weight: bold;
        cursor: pointer;
        height: 50px;
        margin-top: 40px;
        padding: 0;
        background-color: #5c8bc9;
        border-radius: 10px;
        border: none;
    }
</style>
<body>
    <div class="cardapio">
        <div class="central">
            <form id="pizzaForm" method="post" action="mais.php" enctype="multipart/form-data">
                <span>
                    <img src="" id="imagemSelecionada" alt="Imagem Selecionada">
                    <label for="img" class="label_img">Escolher Imagem</label>
                    <input type="file" id="img" name="img" onchange="exibirImagemSelecionada()">
                </span>
                <span>
                    <label>Sabor Pizza</label>
                    <input type="text" id="sabor" name="sabor" required>
                </span>
                <span>
                    <label>Ingredientes</label>
                    <input type="text" id="itens" name="itens" required>
                </span>
                <div class="lado_lado">
                    <span>
                        <label>Pequena</label>
                        <input type="text" id="pequena" name="peq" placeholder="9.99" required>
                    </span>
                    <span>
                        <label>Medio</label>
                        <input type="text" id="medio" name="med" placeholder="9.99" required>
                    </span>
                    <span>
                        <label>Grande</label>
                        <input type="text" id="grande" name="gra" placeholder="9.99" required>
                    </span>
                </div>
                <span>
                    <select name="tipo" id="tipo" onchange="inserir_pro(this)" required>
                        <option value="">O que eu sou?</option>
                        <option value="Promocao">Promoção</option>
                        <?php
                            foreach ($resultados as $resultado) {
                                $tipoEncontrado = $resultado['tipo'];
                                echo "<option value='" . $tipoEncontrado . "'> " . $tipoEncontrado . "</option>";
                            }
                        ?>
                        <option value="Novo">Lista nova</option>
                    </select>
                    <span id="s_new_t" style="display: none;">
                        <label>Nova Separador</label>
                        <input type="text" id="new_tipo" name="new_tipo">
                    </span>
                </span>
                
                <div class="lado_data" id="datas" style="display: none;">
                    <span>
                        <label>Data de inicio</label>
                        <input type="date" id="data_in" name="ini" required>
                    </span>
                    <span>
                        <label>Data de Fim</label>
                        <input type="date" id="data_out" name="out" required>
                    </span>
                </div>
                <button type="submit" id="add" name="adicionar">Adicionar</button>
            </form>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function exibirImagemSelecionada() {
        var inputImagem = document.getElementById('img');
        var imagemSelecionada = document.getElementById('imagemSelecionada');
            
        // Verifica se algum arquivo foi selecionado
        if (inputImagem.files && inputImagem.files[0]) {
            var leitor = new FileReader();
            imagemSelecionada.style.display = 'flex';

            // Define o evento de carregamento da imagem
            leitor.onload = function (e) {
                imagemSelecionada.src = e.target.result;
            };

            // Lê o conteúdo do arquivo como URL de dados
            leitor.readAsDataURL(inputImagem.files[0]);
        }
    }

    function inserir_pro(option) {
        
        if (option.value == 'Promocao') {
            document.getElementById('datas').style.display = 'flex';
        } else {
            document.getElementById('datas').style.display = 'none';
        }

        if (option.value == 'Novo') {
            document.getElementById('s_new_t').style.display = 'flex';
        } else {
            document.getElementById('s_new_t').style.display = 'none';
        }
        toggleRequired();
    }

    $(document).ready(function(){
        $('#pizzaForm').submit(function(e){
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('adicionar', true); 

            $.ajax({
                type: 'POST',
                url: 'execute_mais.php',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);
                    if(response == 'success'){
                        Swal.fire({
                            icon: "success",
                            title: "Adicionado",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response,
                        });
                    }
                },
                error: function(){
                    alert('Erro ao enviar a requisição Ajax.');
                }
            });
        });
    });

    function toggleRequired() {
        var datasDiv = document.getElementById('datas');
        var inputs = datasDiv.getElementsByTagName('input');

        for (var i = 0; i < inputs.length; i++) {
            if (datasDiv.style.display === 'none') {
                inputs[i].removeAttribute('required');
            } else {
                inputs[i].setAttribute('required', 'required');
            }
        }
    }
</script>