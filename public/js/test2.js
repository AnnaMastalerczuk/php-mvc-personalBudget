const getCategories = () => {
    fetch("/expenses/getExpensesCategory")

    .then((res) => {
        return res.json();
    })
    .then((data) => console.log(data))

    .catch((e) => {
        console.log("Error", e);
    })
};

getCategories();