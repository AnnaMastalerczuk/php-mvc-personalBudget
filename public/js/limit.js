let dateInput = document.getElementById('date');
let categoryInput = document.getElementById('category');
// let categoryId = document.getElementById('category').value;


const checkCategory = async () => {
    let categoryId = categoryInput.value;
    let date = dateInput.value;
    let categoryLimit;
    const jsoNData = await getLimitForTheCategory(categoryId).then(data => {
        categoryLimit = data;
        return categoryLimit;
    });

    console.log(categoryLimit);

    checkLimit(categoryLimit, categoryId, date);
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

const checkLimit = (categoryLimit, categoryId, date) => {

getSumOfExpensesForSelectedMonth(categoryId, date);

};

const getSumOfExpensesForSelectedMonth = (id, date) => {
    // let date = document.getElementById('date').value;
    // userdate = "2ddfd022";
    console.log(date);
    // console.log(id);

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

//eventListener

categoryInput.addEventListener('change', async () => {
    await checkCategory();
})

dateInput.addEventListener('change', async () => {
    await checkCategory();
})





