let limitButton = document.getElementById('exampleCheck1');
let amountInput = document.getElementById('disabledInput');
const fieldset = document.querySelector('fieldset');
const saveBtn = document.getElementById('save');
const limitSettingInfo = document.querySelector('p.limit');

const editCategory = ((id) => {
    limitButton.checked = false;
    fieldset.disabled = true;
    amountInput.value ="";
    limitSettingInfo.classList.add("limit");
    

        saveBtn.addEventListener('click', () => {
            if (amountInput.value !== "" && limitButton.checked){
            limitSettingInfo.classList.remove("limit");
            savelimit(id, amountInput.value);
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


const switchOnLimit = (()=> {
    if (limitButton.checked) {
        fieldset.disabled = false;
    } else {
        fieldset.disabled = true;
        amountInput.value ="";
    }
});



// event
limitButton.addEventListener('change', () =>{
    switchOnLimit();
})

