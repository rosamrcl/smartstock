//menu

let menu = document.querySelector('#menu-bars');
let navbar = document.querySelector('.navbar');

menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
}

function openTab(tabId) {
    // Esconde todas as tabelas
    var tabContents = document.getElementsByClassName('tab-content');
    for (var i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove('active');
    }

    // Remove a classe 'active' de todos os botões
    var tabButtons = document.getElementsByClassName('tab-button');
    for (var i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove('active');
    }

    // Mostra a tabela selecionada e marca o botão como ativo
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

function preencherForm(produto) {
    const form = document.querySelector('form');
    form.action = "../Backend/produtos.php";
    form.querySelector('input[name="acao"]').value = 'editar';
    form.querySelector('input[name="nome"]').value = produto.nome;
    form.querySelector('input[name="descricao"]').value = produto.descricao;
    form.querySelector('select[name="status"]').value = produto.status;
    form.querySelector('input[name="quantidade"]').value = produto.quantidade;

    let inputId = document.querySelector('input[name="id"]');
    if (!inputId) {
        inputId = document.createElement('input');
        inputId.type = "hidden";
        inputId.name = "id";
        form.appendChild(inputId);
    }
    inputId.value = produto.id_products;
}


function marcarConcluido(botao, idChamado) {
    fetch('../Backend/marcar_concluido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(idChamado)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const linha = botao.closest('tr');
            linha.classList.add('tr-concluida'); // aqui aplica o fundo verde
            botao.disabled = true;
            botao.innerHTML = '<i class="fa-solid fa-check-double"></i>';
        } else {
            alert("Erro ao marcar como concluído.");
        }
    })
    .catch(() => alert("Erro de comunicação com o servidor."));
}

