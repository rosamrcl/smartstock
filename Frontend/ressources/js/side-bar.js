let header = document.querySelector('.header');

window.onscroll = () => {
    searchForm.classList.remove('active');
    if (window.scrollY > 0) {
        header.classList.add('active');
    } else {
        header.classList.remove('active');
    }
}
let menuBtn = document.querySelector('#menu-btn');
let closeBtn = document.querySelector('#close-btn');
let sideBar = document.querySelector('.side-bar');
let navbar = document.querySelector('.side-bar .navbar');

menuBtn.onclick = () => {
    sideBar.classList.add('active');
}
closeBtn.onclick = () => {
    sideBar.classList.remove('active');
}

window.onclick = (e) => {
    if (!navbar.contains(e.target) && e.target !== menuBtn) {
        sideBar.classList.remove('active');
    }
    if (!searchForm.contains(e.target) && e.target !== searchToggler) {
        searchForm.classList.remove('active');
    }

}