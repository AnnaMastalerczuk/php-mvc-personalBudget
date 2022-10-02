console.log('ok');
console.log('ccccc');

// const getCategories = () => {
//     fetch("/expenses/getExpensesCategory")

//     .then((res) => {
//         return res.json();
//     })
//     .then((data) => console.log(data))

//     .catch((e) => {
//         console.log("Error", e);
//     })
// };

const getCategoriesId = () => {
    const id = 17;
    console.log(id);

   
    fetch(`/expenses/getExpensesCategoryId/${id}`)


    .then((res) => {
        return res.json();
    })
    .then((data) => console.log(data))

    .catch((e) => {
        console.log("Error", e);
    })
};

getCategoriesId();

// const cos = (id) => {
//     fetch("/settings/getExpensesCategory")
//     .then((res) => {
//         return res.json();
//     })
//     .then((data) => console.log(data))

//     .catch((e) => {
//         console.log("Error", e);
//     })
// }

// getCategories();

// function getClickedEditId(clickedId) {
//     console.log(clickedId);
//     return clickedEditButtonId = document.getElementById(clickedId);    
// }

// console.log(clickedEditButtonId);

const checkCategory = () => {
    let categoryId = document.getElementById('category').value;
    getLimitForTheCategory(categoryId);
    
};

const getLimitForTheCategory = (id) =>{
    fetch(`/expenses/getCategoryLimit/${id}`)
    .then((res) => {
        return res.json();
    })
    .then((data) => console.log(data))

    .catch((e) => {
        console.log("Error", e);
    })
};

