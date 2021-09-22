const products = Array.from(document.querySelectorAll('.choose-product'));
const persons = Array.from(document.querySelectorAll('.choose-person'));

const searchPersonsInput = document.querySelector('#personsSearch');
const searchPoductsInput = document.querySelector('#productsSearch');

const getPersons = [...document.querySelectorAll('#person-table tr')];
const getProducts = [...document.querySelectorAll('#product-table tr')]

const personTable = document.querySelector('#person-table');
const productTable = document.querySelector('#product-table');

const personLoginInput = document.querySelector('[data-person]');
const personIdInput = document.querySelector('[data-personId]');

const productNameInput = document.querySelector('[data-product]');
const productIdInput = document.querySelector('[data-productId]');

const searchPersonString = e => {
    const currentString = e.target.value;

    $.ajax({
        type: "GET",
        url: "searchPerson",
        data: {
            searchPerson: currentString
        },
        success: function(res) {
            showPersons(res.persons)
        }
    });
}

function showPersons(data) {
    let tab = '';
    for (let p of data) {
        tab += `<tr> 
                    <td>${p.id}</td>
                    <td id="person-login">${p.login}</td>     
                    <td>
                        <a id="choose-person" href="#" class="btn btn-primary choose-person" person-id=${p.id}>choose</a>
                    </td>    
                </tr>`;
    }
    personTable.innerHTML = '';
    personTable.innerHTML = tab;

}

searchPersonsInput.addEventListener('input', searchPersonString);


const searchProductString = e => {
    const currentString = e.target.value;
    $.ajax({
        type: "GET",
        url: "searchProduct",
        data: {
            searchProduct: currentString
        },
        success: function(res) {
            showProducts(res.products)
        }
    });
}

function showProducts(data) {
    let tab = '';
    for (let p of data) {
        tab += `<tr> 
                    <td>${p.id}</td>
                    <td id="product-name">${p.name}</td>     
                    <td>
                        <a id="choose-product" href="#" class="btn btn-primary choose-product" product-id=${p.id}>choose</a>
                    </td> 
                </tr>`;
    }
    productTable.innerHTML = '';
    productTable.innerHTML = tab;
}

searchPoductsInput.addEventListener('input', searchProductString);


let product_id = null;
let person_id = null;
let oldPerson = null;
let oldProduct = null;

let person;

document.addEventListener('click', (e)=> {
    if (e.target && e.target.id === "choose-person") {
        if (oldPerson) {
            oldPerson.classList.remove('bg-success');
        }
        let newPerson = e.target.parentElement.parentElement;
        newPerson.classList.add('bg-success');

        person_id = e.target.getAttribute('person-id');

        oldPerson  = newPerson;

        const personLogin = newPerson.children[1].innerHTML;

        personLoginInput.setAttribute('value', personLogin);
        personIdInput.setAttribute('value', person_id);
    }

    if (e.target && e.target.id === "choose-product") {
        if (oldProduct) {
            oldProduct.classList.remove('bg-success');
        }

        product_id = e.target.getAttribute('product-id');

        let newProduct = e.target.parentElement.parentElement;
        newProduct.classList.add('bg-success');
        oldProduct  = newProduct;

        const productName = newProduct.children[1].innerHTML;

        productNameInput.setAttribute('value', productName);
        productIdInput.setAttribute('value', product_id);
    }
});
