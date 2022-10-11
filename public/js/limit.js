// Expenses

let dateInput = document.getElementById('date');
let categoryInput = document.getElementById('category');
const limitDiv = document.getElementById('limit');
let spanLimit = document.getElementById('spanLimit');
let spanMonthlySum = document.getElementById('spanMonthlySum');
let infoAboutLimit = document.getElementById('info');

// let categoryId = document.getElementById('category').value;

// check Category, get limit
const checkCategory = async () => {
    let categoryId = categoryInput.value;
    let date = dateInput.value;
    let categoryLimit;
    const jsoNData = await getLimitForTheCategory(categoryId).then(data => {
    categoryLimit = Number(data);
    });

    if (categoryLimit > 0){
        checkLimit(categoryLimit, categoryId, date);
    } else {
        limitDiv.classList.add("limit");
    }
};

// get limit for tge category
const getLimitForTheCategory = async (id) => {

    try {
        const response = await fetch(`/expenses/getCategoryLimit/${id}`);
        const data = await response.json();
        return data[0].userLimit;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};

// get sum of expenses and render result
const checkLimit = async (categoryLimit, categoryId, date) => {
let sumOfExpensesMonthly;
const jsoNData = await getSumOfExpensesForSelectedMonth(categoryId, date).then(data => {
    sumOfExpensesMonthly = data;
});

renderOnDom(categoryLimit, sumOfExpensesMonthly);

};

// get sum of expenses
const getSumOfExpensesForSelectedMonth = async (id, date) => {

    try {
        const response = await fetch(`/expenses/getExpensesDate/${id}/${date}`);
        const data = await response.json();
        let sum = 0;
        console.log(data);
        for (let i = 0; i < data.length; i++){
            sum += Number(data[i].amount);
        }
        return sum;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};


const renderOnDom = (categoryLimit, sumOfExpensesMonthly) => {
    limitDiv.classList.remove("limit");
    spanLimit.textContent = `${categoryLimit}`;
    spanMonthlySum.textContent = `${sumOfExpensesMonthly}`;
    let restAmount = categoryLimit - sumOfExpensesMonthly;
    // const infoAboutLimit = limitDiv.appendChild(document.createElement('p'));

    if (sumOfExpensesMonthly > categoryLimit) {
        // limitDiv.style.backgroundColor = "rgba(255, 0, 0, 0.5)";
        infoAboutLimit.style.color = "red";
        infoAboutLimit.textContent = "Limit na bieżący miesiąc jest już przekroczony";

    } else {
        // limitDiv.style.backgroundColor = "rgba(0, 255, 0, 0.5)";
        infoAboutLimit.style.color = "green";
        infoAboutLimit.textContent = `Aby nie przekroczyć limitu w bieżącym miesiącu możesz wydać jeszcze: ${restAmount} zł`;
    }

};

//eventListener

categoryInput.addEventListener('change', async () => {
    await checkCategory();
})

dateInput.addEventListener('change', async () => {
    await checkCategory();
})





