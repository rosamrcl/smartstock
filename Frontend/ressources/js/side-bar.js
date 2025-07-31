
document.addEventListener('DOMContentLoaded', function() {
    let menuBtn = document.querySelector('#menu-btn');
    let closeBtn = document.querySelector('#close-btn');
    let sideBar = document.querySelector('.side-bar');
    let navbar = document.querySelector('.side-bar .navbar');

    if (menuBtn && closeBtn && sideBar && navbar) {
        menuBtn.onclick = () => sideBar.classList.add('active');
        closeBtn.onclick = () => sideBar.classList.remove('active');

        window.onclick = (e) => {
            if (!navbar.contains(e.target) && e.target !== menuBtn) {
                sideBar.classList.remove('active');
            }
        };
    } else {
        console.error("Elementos do menu não encontrados!");
    }
});