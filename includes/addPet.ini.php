    <?php
    require __DIR__ . '/../bd/conexao.php';
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $raca = $_POST['raca'] ?? '';
        $nome_pet = $_POST['nomePet'] ?? '';
        $nome_tutor = $_POST['nomeTutor'] ?? '';
        $contato_tutor = $_POST['contatoTutor'] ?? '';
        $descricao_servico = $_POST['descricao'] ?? '';
        $observacao = $_POST['observacao'] ?? '';
    
        $dbh->beginTransaction();
    
        try {
            $stmt = $dbh->prepare("SELECT id FROM donos WHERE contato = ?");
            $stmt->execute([$contato_tutor]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                $dono_id = $row['id'];
            } else {
                $stmt = $dbh->prepare("INSERT INTO donos (nome, contato) VALUES (?, ?)");
                $stmt->execute([$nome_tutor, $contato_tutor]);
                $dono_id = $dbh->lastInsertId();
            }
    
            $stmt = $dbh->prepare("SELECT id FROM racas WHERE nome = ?");
            $stmt->execute([$raca]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                $raca_id = $row['id'];
            } else {
                $stmt = $dbh->prepare("INSERT INTO racas (nome) VALUES (?)");
                $stmt->execute([$raca]);
                $raca_id = $dbh->lastInsertId();
            }
    
            $stmt = $dbh->prepare("INSERT INTO pets (nome, raca_id, dono_id) VALUES (?, ?, ?)");
            $stmt->execute([$nome_pet, $raca_id, $dono_id]);
            $pet_id = $dbh->lastInsertId();
    
            $stmt = $dbh->prepare("INSERT INTO visitaspet (pet_id, servico_id, dataVisita, observacoes) VALUES (?, (SELECT id FROM servicos WHERE descricao = ?), CURDATE(), ?)");
            $stmt->execute([$pet_id, $descricao_servico, $observacao]);
    
            $dbh->commit();
    
            $_SESSION['mensagem'] = "<p style='color: green; text-align: center; font-size: 1.5em;'>Pet cadastrado com sucesso!</p>";
        } catch (Exception $e) {
            $dbh->rollBack();
            $_SESSION['mensagem'] = "<p style='color: red; text-align: center; font-size: 1.5em;'>Erro ao cadastrar pet: " . $e->getMessage() . "</p>";
        }
    
        // Redireciona de volta para a página de cadastro
        header('Location: ./cadastro.php');
        exit();
    }
    ?>