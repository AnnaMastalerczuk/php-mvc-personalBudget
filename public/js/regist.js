const inputPassword = document.querySelector('#password');
const eyeBtn = document.querySelector('#togglePassword');
let flag = true;

eyeBtn.addEventListener('click', function() {

    if(flag){
        inputPassword.type = 'text';
        flag = false;
    } else {
        inputPassword.type = 'password';
        flag = true;
    }
    eyeBtn.classList.toggle('bi-eye');
});