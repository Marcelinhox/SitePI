<?php
session_start();
include 'alert.php';

// Verifica se há mensagem de erro na sessão
if (isset($_SESSION['error'])) {
    $erro = $_SESSION['error'];
    exibirAlerta('error', 'Erro!', $erro);
    unset($_SESSION['error']); // Limpa a mensagem de erro após exibi-la
}


// Verifica se o usuário está logado e se é administrador
$isAdmin = isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 1;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja de Jogos</title>
    <link rel="stylesheet" href="css/style.css">
    <script src=”js/script.js”></script>
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
</head>
<body>
    <header>
        <h1>Loja de Jogos</h1>
        <!-- Menu de Navegação -->
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="gerenciar_jogos.php">Gerenciar Jogos</a></li> <!-- Aba para gerenciar jogos -->
                <?php endif; ?>
                <li>
                    <?php
                    if (isset($_SESSION['nome'])) {
                        echo "Bem-vindo, " . $_SESSION['nome'];
                        echo " <a href='logout.php'>Logout</a>";
                    } else {
                        echo "<button onclick=\"openModal('loginModal')\">Login</button> | ";
                        echo "<button onclick=\"openModal('cadastroModal')\">Cadastro</button>";
                    }
                    ?>
                </li>
            </ul>
        </nav>

    </header>

    <main>
        <h2>Jogos Disponíveis</h2>
        <div class="cards-container">
            <?php
            // Conexão com o banco de dados
            $conexao = new mysqli("localhost", "root", "", "loja_jogos");

            if ($conexao->connect_error) {
                die("Conexão falhou: " . $conexao->connect_error);
            }

            // Consulta para buscar os jogos
            $sql = "SELECT * FROM jogos";
            $result = $conexao->query($sql);

            if ($result->num_rows > 0) {
                // Exibe cada jogo como um card
                while ($jogo = $result->fetch_assoc()) {
                    echo "<div class='card'>";
                    echo "<h3>" . $jogo['titulo'] . "</h3>";
                    echo "<p>" . $jogo['descricao'] . "</p>";
                    echo "<p>Desenvolvedora: " . $jogo['desenvolvedora'] . "</p>";
                    echo "<p>Faixa Etária: " . $jogo['faixa_etaria'] . "</p>";
                    echo "<p>Valor: R$ " . $jogo['valor'] . "</p>";
                    
                    // Botão para ver mais detalhes
                    echo "<button onclick=\"document.getElementById('modal" . $jogo['id'] . "').style.display='block'\">Ver Detalhes</button>";

                    // Opções de edição para administradores
                    if ($isAdmin) {
                        echo "<div class='admin-options'>";
                        echo "<a href='editar_jogo.php?id=" . $jogo['id'] . "'>Editar</a> | ";
                        echo "<a href='deletar_jogo.php?id=" . $jogo['id'] . "' onclick='return confirm(\"Deseja realmente excluir este jogo?\");'>Excluir</a>";
                        echo "</div>";
                    }

                    echo "</div>";

                    // Modal específico para cada jogo
                    echo "<div id='modal" . $jogo['id'] . "' class='modal'>";
                    echo "<div class='modal-content'>";
                    echo "<span class='close' onclick=\"document.getElementById('modal" . $jogo['id'] . "').style.display='none'\">&times;</span>";
                    echo "<h3>" . $jogo['titulo'] . "</h3>";
                    echo "<p><strong>Descrição:</strong> " . $jogo['descricao'] . "</p>";
                    echo "<p><strong>Data de Lançamento:</strong> " . $jogo['data_lancamento'] . "</p>";
                    echo "<p><strong>Plataformas para Compra:</strong> " . $jogo['plataformas_compra'] . "</p>";
                    echo "<p><strong>Desenvolvedores:</strong> " . $jogo['desenvolvedores'] . "</p>";
                    echo "<p><strong>Requisitos Mínimos:</strong> " . $jogo['requisitos_minimos'] . "</p>";
                    echo "<p><strong>Sinopse/Jogabilidade:</strong> " . $jogo['sinopse_jogabilidade'] . "</p>";
                    echo "<p><strong>Gêneros:</strong> " . $jogo['generos'] . "</p>";
                    echo "<p><strong>Faixa Etária:</strong> " . $jogo['faixa_etaria'] . "</p>";
                    // Aqui você pode adicionar as fotos e vídeos
                    echo "</div>";
                    echo "</div>";
                    
                }
            } else {
                echo "<p>Nenhum jogo disponível no momento.</p>";
            }

            $conexao->close();
            ?>
        </div>

        
    </main>

    <footer>
        <p>&copy; 2024 Loja de Jogos</p>
    </footer>

    <script>
        
        // Função para abrir o modal
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        // Fecha o modal quando o usuário clica fora do conteúdo
        window.onclick = function(event) {
            const loginModal = document.getElementById('loginModal');
            const cadastroModal = document.getElementById('cadastroModal');
            if (event.target == loginModal) {
                loginModal.style.display = "none";
            }
            if (event.target == cadastroModal) {
                cadastroModal.style.display = "none";
            }
        }
  
    </script>
</body>
</html>

<!-- Modal de Login -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('loginModal').style.display='none'">&times;</span>
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit">Entrar</button>
            <button type="button" class="close-btn" onclick="document.getElementById('loginModal').style.display='none'">Fechar</button>
        </form>
    </div>
</div>

<!-- Modal de Cadastro -->
<div id="cadastroModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('cadastroModal').style.display='none'">&times;</span>
        <h2>Cadastro</h2>
        <form id="cadastroForm" action="cadastro.php" method="POST" onsubmit="return validarFormulario()">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($_SESSION['cadastro_data']['nome'] ?? ''); ?>" required>

            <label for="usuario">Usuário:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($_SESSION['cadastro_data']['usuario'] ?? ''); ?>" required>
            
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
            <div id="senhaRequirements" class="requirements"></div>

            <label for="confirmar_senha">Confirme a Senha:</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            
            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" value="<?php echo htmlspecialchars($_SESSION['cadastro_data']['telefone'] ?? ''); ?>">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['cadastro_data']['email'] ?? ''); ?>" required>

            <label for="confirmar_email">Confirme o Email:</label>
            <input type="email" id="confirmar_email" name="confirmar_email" required>

            <button type="submit">Cadastrar</button>
            <button type="button" class="close-btn" onclick="document.getElementById('cadastroModal').style.display='none'">Fechar</button>
        </form>
    </div>
</div>





