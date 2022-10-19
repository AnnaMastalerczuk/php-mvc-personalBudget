let limitButton = document.getElementById('exampleCheck1');
let amountInput = document.getElementById('disabledInput');
const fieldset = document.querySelector('fieldset');
const saveBtn = document.getElementById('save');
const removeBtn = document.getElementById('remove');
const limitSettingInfo = document.querySelector('p.limit');
let pRemoveInfo = document.querySelector('div.removeCat > p');
let pRemoveInfo2 = document.querySelector('div.removeCat > p.limit');

// 1. EDIT CATEGORY

const editCategory = ((id) => {
    console.log(id);
    limitButton.checked = false;
    fieldset.disabled = true;
    amountInput.value ="";
    limitSettingInfo.classList.add("limit");

        saveBtn.addEventListener('click', () => {
            if (amountInput.value !== "" && limitButton.checked){
            limitSettingInfo.classList.remove("limit");
            savelimit(id, amountInput.value);
            changeLimitOnDom(amountInput.value, id);
            }
        })
});

const savelimit = (id, amount) => {

console.log(id);
console.log(amount);

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

    // fetch("/expenses/postLimit", {
    //     method: 'POST',        
    //     headers: {
    //         'Content-Type': 'application/json'
    //     },
    //     body: JSON.stringify({
    //         idCat: id,
    //         limit: amount,
    //     }),
    //   })
    //     .then((response) => {
    //         console.log(response);
    //         response.json();
    //     })
    //     .then((data) => {
    //       console.log('Success', data);
    //     })
    //     .catch((error) => {
    //       console.error('Error:', error);
    //     });

   
};


const switchOnLimit = (() => {
    if (limitButton.checked) {
        fieldset.disabled = false;
    } else {
        fieldset.disabled = true;
        amountInput.value ="";
    }
});

const changeLimitOnDom = ((limit, id) => {
    let limitP = document.getElementById(`pLimit${id}`);
    let limitN = format(limit,2);
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

// 2. REMOVE CATEGORY

const removeCategory = async (id) => {
    // let sum = 0;
    pRemoveInfo2.classList.add("limit");
    const jsoNData = await getExpensesFromTheCategory(id).then(data => {
    expensesFromCategory= (data);
    });
    numberOfExpenses = countExpenses(expensesFromCategory);
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

const countExpenses = (expensesFromCategory) =>{

    let sum = 0;
    expensesFromCategory.forEach(expense => {
        sum ++;
    })

    return sum;
};

const showOnDom = (numberOfExpenses) => {

    if (numberOfExpenses === 0) {
        pRemoveInfo.innerHTML = 'W kategorii nie ma zapisanych żadnych wydatków. <br> Kliknij usuń, jeżeli chcesz usunąć kategorię.';
    } else {
        pRemoveInfo.innerHTML = `W kategorii zapisano ${numberOfExpenses} wydatki. <br> Jeżeli chcesz usunąć kategorię, kliknij usuń`;
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

    console.log('ok');
};

function refresh() {    
    setTimeout(function () {
        location.reload()
    }, 1000);
}

