//Login/Register form 
const wrapper = document.querySelector('.wrapper');
//Change login and register page
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
//Click on login button and pop up the login form
const btnPopup = document.querySelector('.btnLogin-popup');
const iconClose = document.querySelector('.icon-close');

//Change login and register page
registerLink.addEventListener('click', ()=>{
    wrapper.classList.add('active');
});

loginLink.addEventListener('click', ()=>{
    wrapper.classList.remove('active');
});

//Click on login button and pop up the login form
btnPopup.addEventListener('click', ()=>{
    wrapper.classList.add('active-popup');
});

iconClose.addEventListener('click', ()=>{
    wrapper.classList.remove('active-popup');
});
