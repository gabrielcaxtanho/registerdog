<?php
require './../bd/conexao.php';

if (!$conexao) {
    die("Erro: A conexão com o banco de dados não foi estabelecida.");
}


$mensagem = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $raca = $_POST['raca'] ?? '';
    $nome_pet = $_POST['nomePet'] ?? '';
    $nome_tutor = $_POST['nomeTutor'] ?? '';
    $contato_tutor = $_POST['contatoTutor'] ?? '';
    $descricao_servico = $_POST['descricao'] ?? '';
    $observacao = $_POST['observacao'] ?? '';


    $conexao->begin_transaction();

    try {
        // Verifica se o tutor já existe no banco de dados
        $stmt = $conexao->prepare("SELECT id FROM donos WHERE contato = ?");
        $stmt->bind_param("s", $contato_tutor);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Tutor já existe, obtém o ID
            $row = $result->fetch_assoc();
            $dono_id = $row['id'];
        } else {
            // Insere um novo tutor no banco de dados
            $stmt = $conexao->prepare("INSERT INTO donos (nome, contato) VALUES (?, ?)");
            $stmt->bind_param("ss", $nome_tutor, $contato_tutor);
            $stmt->execute();
            $dono_id = $stmt->insert_id; // Obtém o ID do novo tutor
        }

        // Verifica se a raça já existe no banco de dados
        $stmt = $conexao->prepare("SELECT id FROM racas WHERE nome = ?");
        $stmt->bind_param("s", $raca);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Raça já existe, obtém o ID
            $row = $result->fetch_assoc();
            $raca_id = $row['id'];
        } else {
            // Insere uma nova raça no banco de dados
            $stmt = $conexao->prepare("INSERT INTO racas (nome) VALUES (?)");
            $stmt->bind_param("s", $raca);
            $stmt->execute();
            $raca_id = $stmt->insert_id; // Obtém o ID da nova raça
        }

        // Insere o pet no banco de dados
        $stmt = $conexao->prepare("INSERT INTO pets (nome, raca_id, dono_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $nome_pet, $raca_id, $dono_id);
        $stmt->execute();
        $pet_id = $stmt->insert_id; // Obtém o ID do novo pet

        // Insere a visita do pet no banco de dados
        $stmt = $conexao->prepare("INSERT INTO visitaspet (pet_id, servico_id, dataVisita, observacoes) VALUES (?, (SELECT id FROM servicos WHERE descricao = ?), CURDATE(), ?)");
        $stmt->bind_param("iss", $pet_id, $descricao_servico, $observacao);
        $stmt->execute();

        // Confirma a transação
        $conexao->commit();

        $mensagem = "<p style='color: green; text-align: center; font-size: 1.5em;'>Pet cadastrado com sucesso!</p>";
    } catch (Exception $e) {
        // Se houver algum erro, desfaz a transação
        $conexao->rollback();
        $mensagem = "<p style='color: red; text-align: center; font-size: 1.5em;'>Erro ao cadastrar pet: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Exibe a mensagem na página -->
<?php if ($mensagem) : ?>
<section class="mensagem">
    <?= $mensagem ?>
</section>
<?php endif; ?>
?>
