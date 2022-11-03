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

//add
const addBtn = document.getElementById('add');
let inputAddCategory = document.querySelector('div.addCat input');
const pAddInfoAdded = document.querySelector('div.addCat p');
const pAddInfoExist = document.querySelector('div.addCat p+p');

// password
const savePassBtn = document.getElementById('savePass');
let inputChangePassword = document.querySelector('div.changePass input');
const pAddInfoChangedPass = document.querySelector('div.changePass p');
const pAddInfoNotChangedPass = document.querySelector('div.changePass p+p');

// 1. EDIT CATEGORY

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

  const editCategory = e => {
    id = e.target.id;
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
})

// event
limitButton.addEventListener('change', () =>{
    switchOnLimit();
})
  
for (let button of editBtns) {
    button.addEventListener("click", editCategory);
}

saveBtn.addEventListener("click", saveCategoryLimit);


// 2. REMOVE CATEGORY

const removeCategory = async (id) => {
    // let sum = 0;
    pRemoveInfo2.classList.add("limit");
    const jsoNData = await getExpensesFromTheCategory(id).then(data => {
    expensesFromCategory= (data);
    });
    numberOfExpenses = countExpensesOrIncomes(expensesFromCategory);
    showOnDom(numberOfExpenses);

    removeBtn.addEventListener('click', () => {
        pRemoveInfo2.classList.remove("limit");

        if (numberOfExpenses != 0) {
            removeExpensesInCategory(id);
        }
        removeCategoryFromBase(id);
        refresh();
    })
};

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

const removeCategoryFromBase = (id) => { 
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

function refresh() {    
    setTimeout(function () {
        location.reload()
    }, 1000);
}

// 3. ADD CATEGORY

const addCategory = (() => {
    pAddInfoExist.classList.add('limit');
    pAddInfoAdded.classList.add('limit');
    inputAddCategory.value = '';

    addBtn.addEventListener('click', async () => {
        if (inputAddCategory.value !== ""){
            const jsoNData = await getNumberOfRows(inputAddCategory.value).then(data => {
                pAddInfoExist.classList.add('limit');
                pAddInfoAdded.classList.add('limit'); 
                if(data === 0) {
                    addNewCategory(inputAddCategory.value);
                    pAddInfoAdded.classList.remove('limit');
                    refresh();
                } else {
                    pAddInfoExist.classList.remove('limit');
                }
            });
        }
    })

});

const getNumberOfRows = async (newName) => {

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

const addNewCategory = (newName) => { 
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

//INCOMES//

//1. Remove category

const removeCategoryIncome = async (id) => {
    pRemoveInfo2.classList.add("limit");
    const jsoNData = await getIncomesFromTheCategory(id).then(data => {
    incomesFromCategory = (data);
    });
    numberOfIncomes = countExpensesOrIncomes(incomesFromCategory);
    showOnDom(numberOfIncomes);

    removeBtn.addEventListener('click', () => {
        pRemoveInfo2.classList.remove("limit");

        if (numberOfIncomes != 0) {
            removeIncomesInCategory(id);
        }
        removeIncomeCategoryFromBase(id);
        refresh();
    })
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

// 2. ADD CATEGORY

const addCategoryIncome = (() => {
    pAddInfoExist.classList.add('limit');
    pAddInfoAdded.classList.add('limit');
    inputAddCategory.value = '';

    addBtn.addEventListener('click', async () => {
        if (inputAddCategory.value !== ""){
            const jsoNData = await getNumberOfRowsIncome(inputAddCategory.value).then(data => {
                pAddInfoExist.classList.add('limit');
                pAddInfoAdded.classList.add('limit'); 
                if(data === 0) {
                    addNewCategoryIncome(inputAddCategory.value);
                    pAddInfoAdded.classList.remove('limit');
                    refresh();
                } else {
                    pAddInfoExist.classList.remove('limit');
                }
            });
        }
    })

});

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

//USER//

// 1. Change password //

const changePassword = (() => {
    pAddInfoNotChangedPass.classList.add('limit');
    pAddInfoChangedPass.classList.add('limit');

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
