

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

// const getCategoriesId = () => {
//     const id = 17;
//     console.log(id);

   
//     fetch(`/expenses/getExpensesCategoryId/${id}`)


//     .then((res) => {
//         return res.json();
//     })
//     .then((data) => console.log(data))

//     .catch((e) => {
//         console.log("Error", e);
//     })
// };

// getCategoriesId();

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

const checkCategory = async () => {
    let categoryId = document.getElementById('category').value;

    let categoryLimit;
    const jsoNData = await getLimitForTheCategory(categoryId).then(data => {
        categoryLimit = data;
        return categoryLimit;
    });

    console.log(categoryLimit);

    checkLimit(categoryLimit, categoryId);
};

const getLimitForTheCategory = async (id) => {
      
    // try {
    //     const response = await fetch(`/expenses/getCategoryLimit/${id}`);
    //     if (!response.ok) {
    //       throw new Error(`HTTP error: ${response.status}`);
    //     }
    //     const data = await response.json();
    //     return data[0].userLimit;
    //   }
    //   catch (error) {
    //     console.error(`Error: ${error}`);
    //   }

    try {
        const response = await fetch(`/expenses/getCategoryLimit/${id}`);
        const data = await response.json();
        return data[0].userLimit;
    }
    catch (error) {
    console.error(`Error: ${error}`);
    }











    // const promiseFetch = await fetch(`/expenses/getCategoryLimit/${id}`);
    // const datajson = await promiseFetch.json();
    // return datajson[0].userLimit;

};

const checkLimit = (categoryLimit, categoryId) => {

getSumOfExpensesForSelectedMonth(categoryId);

};

const getSumOfExpensesForSelectedMonth = (id) => {
    let date = document.getElementById('date').value;
    // userdate = "2ddfd022";
    console.log(date);
    console.log(id);

    fetch(`/expenses/getExpensesDate/${id}/${date}`)
    // fetch(`/expenses/getExpensesDate/${userdate}`)
    .then(res => res.json())
    .then(data => console.log(data))

    // fetch(`/expenses/getCategoryLimit/${id}`)
    // .then(res => res.json())
    // .then(date => console.log(date))

    // try {
    //     const response = await fetch(`/expenses/getExpenses/${id}`);
    //     if (!response.ok) {
    //       throw new Error(`HTTP error: ${response.status}`);
    //     }
    //     const data = await response.json();
    //     return data[0].userLimit;
    //   }
    //   catch (error) {
    //     console.error(`Error: ${error}`);
    //   }


};



