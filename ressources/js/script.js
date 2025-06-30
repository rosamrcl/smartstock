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