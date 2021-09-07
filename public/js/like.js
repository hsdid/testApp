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
    let result = getPersons;
    result = result.filter(getPerson => getPerson.children[1].innerHTML.includes(currentString));
    personTable.innerHTML = '';
    result.forEach(name => personTable.appendChild(name));
}

searchPersonsInput.addEventListener('input', searchPersonString);


const searchProductString = e => {
    const currentString = e.target.value;
    let result = getProducts;
    result = result.filter(getProduct => getProduct.children[1].innerHTML.includes(currentString));
    productTable.innerHTML = '';
    result.forEach(name => productTable.appendChild(name));
}

searchPoductsInput.addEventListener('input', searchProductString);


let product_id = null;
let person_id = null;
let oldPerson = null;
let oldProduct = null;

products.map(product => product.addEventListener('click', e => {
    if (oldProduct) {
        oldProduct.classList.remove('bg-success');
    }
    product_id = e.target.getAttribute('product-id');

    let newProduct = product.parentElement.parentElement;
    newProduct.classList.add('bg-success');
    oldProduct  = newProduct;

    const productName = newProduct.children[1].innerHTML;

    productNameInput.setAttribute('value', productName);
    productIdInput.setAttribute('value', product_id);

}));

persons.map(person => person.addEventListener('click', e => {

    if (oldPerson) {
        oldPerson.classList.remove('bg-success');
    }

    let newPerson = person.parentElement.parentElement;
    newPerson.classList.add('bg-success');
    person_id = e.target.getAttribute('person-id');
    oldPerson  = newPerson;

    const personLogin = newPerson.children[1].innerHTML;

    personLoginInput.setAttribute('value', personLogin);
    personIdInput.setAttribute('value', person_id);

}));
