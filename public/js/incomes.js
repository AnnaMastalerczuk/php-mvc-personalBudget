const btnCancel = document.querySelector('#btnCancel');
const textA = document.querySelector('#comment');
const inputs = document.querySelectorAll('input');
const inputData = document.querySelector('#date');

function leadingZero(i) {
    return (i < 10)? "0"+i : i;
}

let nowData = new Date();
let year = nowData.getFullYear();
let month = leadingZero(nowData.getMonth()+1);
let day = leadingZero(nowData.getDate());

inputData.value = `${year}-${month}-${day}`;

btnCancel.addEventListener('click', function() {
    
    inputs.forEach(input => {
    input.value = "";
    });
    inputData.value = `${year}-${month}-${day}`;
    textA.value = "";
});