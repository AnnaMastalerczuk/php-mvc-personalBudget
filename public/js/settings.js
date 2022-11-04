// EXPENSES //

//edit
let limitButton = document.getElementById('exampleCheck1');
let amountInput = document.getElementById('disabledInput');
let id = '';
const fieldset = document.querySelector('fieldset');
const saveBtn = document.getElementById('save');
const editBtns = document.getElementsByClassName('editCategoryBtn');

//remove
const removeBtn = document.getElementById('remove');
const limitSettingInfo = document.querySelector('p.limit');
let pRemoveInfo = document.querySelector('div.removeCat > p');
let pRemoveInfo2 = document.querySelector('div.removeCat > p.limit');
let numberOfTransaction = "";
let removeKind = '';

//add
const addBtn = document.getElementById('add');
let inputAddCategory = document.querySelector('div.addCat input');
const pAddInfoAdded = document.querySelector('div.addCat p');
const pAddInfoExist = document.querySelector('div.addCat p+p');
const addExpenseCategoryBtn = document.getElementById('addExpenseCategory');
const addIncomeCategoryBtn = document.getElementById('addIncomeCategory');
let addKind = '';

// password
const savePassBtn = document.getElementById('savePass');
const changePasswordBtn = document.getElementById('changePassword');
let inputChangePassword = document.querySelector('div.changePass input');
const pAddInfoChangedPass = document.querySelector('div.changePass p');
const pAddInfoNotChangedPass = document.querySelector('div.changePass p+p');

// 1. EDIT CATEGORY

const editCategoryExpenses = (idFromHtml) => {
    // id = e.target.id;
    id = idFromHtml;
    console.log(id);
    limitButton.checked = false;
    fieldset.disabled = true;
    amountInput.value ="";
    limitSettingInfo.classList.add("limit");
}

const saveCategoryLimit =(() => {
    if (amountInput.value !== "" && limitButton.checked){
        saveLimit();
        changeLimitOnDom();
    }
});

const saveLimit = () => {

    let amount = amountInput.value;
    
    $.ajax({
        type: 'POST',
        url: '/expenses/postLimit',
        dataType: 'json',
        data: {
            postCategoryId: id,
            postCategoryLimit: amount,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    });
       
};

const switchOnLimit = (() => {
    if (limitButton.checked) {
        fieldset.disabled = false;
    } else {
        fieldset.disabled = true;
        amountInput.value ="";
    }
});

const changeLimitOnDom = (() => {
    let limitP = document.getElementById(`pLimit${id}`);
    let limitN = format(amountInput.value,2);
    limitP.textContent = `Limit: ${limitN} zł`;
});

function format(liczba, lmpp) {
    ile = ""+Math.round(liczba*Math.pow(10,lmpp))/Math.pow(10,lmpp);
    if (ile.indexOf(".")<0) ile+=".0";
    while ((ile.length-ile.indexOf(".")-1)<lmpp) ile = ile+"0";
    return ile;
  }

// event
limitButton.addEventListener('change', () =>{
    switchOnLimit();
})
  
// for (let button of editBtns) {
//     button.addEventListener("click", editCategory);
// }

saveBtn.addEventListener("click", saveCategoryLimit);


// 2. REMOVE CATEGORY EXPENSES / INCOMES

const removeCategoryExpense = async (idFromHtml, idRemove) => {
    // let sum = 0;
    id = idFromHtml;
    removeKind = idRemove;
    pRemoveInfo2.classList.add("limit");
    const jsoNData = await getExpensesFromTheCategory(id).then(data => {
    expensesFromCategory= (data);
    });
    numberOfTransaction = countExpensesOrIncomes(expensesFromCategory);
    showOnDom(numberOfTransaction);
};

const removeCategoryIncome = async (idFromHtml, idRemove) => {
    id = idFromHtml;
    removeKind = idRemove;
    pRemoveInfo2.classList.add("limit");
    const jsoNData = await getIncomesFromTheCategory(id).then(data => {
    incomesFromCategory = (data);
    });
    numberOfTransaction = countExpensesOrIncomes(incomesFromCategory);
    showOnDom(numberOfTransaction);
};

removeBtn.addEventListener('click', () => {
    pRemoveInfo2.classList.remove("limit");
    
    if(removeKind === "removeExpense")
    {     
        if (numberOfTransaction != 0) {
        removeExpensesInCategory(id);
        }
        removeExpenseCategoryFromBase(id);
        
    } else if (removeKind === "removeIncome")
    {
        if (numberOfTransaction != 0) {
        removeIncomesInCategory(id);
        }
        removeIncomeCategoryFromBase(id);
    }
    refresh();
})

const getExpensesFromTheCategory = async (id) => {

    try {
        const response = await fetch(`/expenses/getExpensesFromCategory/${id}`);
        const data = await response.json();
        console.log(data);
        return data;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};

const countExpensesOrIncomes = (datas) =>{

    let sum = 0;
    datas.forEach(data=> {
        sum ++;
    })

    return sum;
};

const showOnDom = (number) => {

    if (number === 0) {
        pRemoveInfo.innerHTML = 'W kategorii nie ma zapisanych żadnych transakcji. <br> Kliknij usuń, jeżeli chcesz usunąć kategorię.';
    } else {
        pRemoveInfo.innerHTML = `W kategorii zapisano ${number} transakcji. <br> Jeżeli chcesz usunąć kategorię, kliknij usuń`;
    }
};

const removeExpensesInCategory = (id) => { 
    $.ajax({
        type: 'POST',
        url: '/expenses/deleteExpensesInCategory',
        dataType: 'json',
        data: {
            deleteCategoryId: id,
        },    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

const removeExpenseCategoryFromBase = (id) => { 
    $.ajax({
        type: 'POST',
        url: '/expenses/deleteCategory',
        dataType: 'json',
        data: {
            deleteCategoryId: id,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

const getIncomesFromTheCategory = async (id) => {

    try {
        const response = await fetch(`/incomes/getIncomesFromCategory/${id}`);
        const data = await response.json();
        console.log(data);
        return data;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};

const removeIncomesInCategory = (id) => { 
    $.ajax({
        type: 'POST',
        url: '/incomes/deleteIncomesInCategory',
        dataType: 'json',
        data: {
            deleteCategoryId: id,
        },    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

const removeIncomeCategoryFromBase = (id) => { 
    $.ajax({
        type: 'POST',
        url: '/incomes/deleteCategory',
        dataType: 'json',
        data: {
            deleteCategoryId: id,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

function refresh() {    
    setTimeout(function () {
        location.reload()
    }, 1000);
}

// 3. ADD CATEGORY EXPENSE / INCOME

const addExpenseCategory = ((e) => {
    addKind = e.target.id;
    console.log(addKind);

    pAddInfoExist.classList.add('limit');
    pAddInfoAdded.classList.add('limit');
    inputAddCategory.value = '';
});

const addIncomeCategory = ((e) => {
    addKind = e.target.id;
    console.log(addKind);

    pAddInfoExist.classList.add('limit');
    pAddInfoAdded.classList.add('limit');
    inputAddCategory.value = '';

});

const getNumberOfRowsExpense = async (newName) => {

    let name = newName;
    try {
        const response = await fetch(`/expenses/ifNewcategoryNameExists/${name}`);
        const data = await response.json();
        console.log(data);
        return data;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};

const addNewCategoryExpense = (newName) => { 
    $.ajax({
        type: 'POST',
        url: '/expenses/addNewCategory',
        dataType: 'json',
        data: {
            name: newName,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

const getNumberOfRowsIncome = async (newName) => {

    let name = newName;
    try {
        const response = await fetch(`/incomes/ifNewcategoryNameExists/${name}`);
        const data = await response.json();
        console.log(data);
        return data;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }
};

const addNewCategoryIncome = (newName) => { 
    $.ajax({
        type: 'POST',
        url: '/incomes/addNewCategory',
        dataType: 'json',
        data: {
            name: newName,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

addBtn.addEventListener('click', async () => {

    if (inputAddCategory.value !== "")
    {
        pAddInfoExist.classList.add('limit');
        pAddInfoAdded.classList.add('limit'); 
        if (addKind === "addExpenseCategory")
        {
            const jsoNData = await getNumberOfRowsExpense(inputAddCategory.value).then(data => 
            {
                if(data === 0) {
                    addNewCategoryExpense(inputAddCategory.value);
                    pAddInfoAdded.classList.remove('limit');
                    refresh();
                } else {
                    pAddInfoExist.classList.remove('limit');
                }
            });

        } else if (addKind === "addIncomeCategory")
        {
            const jsoNData = await getNumberOfRowsIncome(inputAddCategory.value).then(data => 
            {
                if(data === 0) {
                    addNewCategoryIncome(inputAddCategory.value);
                    pAddInfoAdded.classList.remove('limit');
                    refresh();
                } else {
                    pAddInfoExist.classList.remove('limit');
                }
            });
        }
    }
})

//event
addExpenseCategoryBtn.addEventListener('click', addExpenseCategory);
addIncomeCategoryBtn.addEventListener('click', addIncomeCategory);

//USER//

// 1. Change password //

const changePassword = (() => {
    pAddInfoNotChangedPass.classList.add('limit');
    pAddInfoChangedPass.classList.add('limit');
});

const validatePassword = ((password) => {
    if (password.length <6 || password.length > 20) {
        return false;
    } else return true;
})

const changePasswordBase = (password) => { 
    $.ajax({
        type: 'POST',
        url: '/userMenager/changePassword',
        dataType: 'json',
        data: {
            pass: password,
        },
    
        success: (result) => console.log(result),
        error: () => console.log('error'),
    }); 
};

//event

changePasswordBtn.addEventListener('click', changePassword);

savePassBtn.addEventListener('click', () => {
    pAddInfoNotChangedPass.classList.add('limit');
    pAddInfoChangedPass.classList.add('limit');

    if (validatePassword(inputChangePassword.value)) {
        changePasswordBase(inputChangePassword.value);
        pAddInfoChangedPass.classList.remove('limit');
    } else {
        pAddInfoNotChangedPass.classList.remove('limit');            
    }
    inputChangePassword.value = '';
})
