const products = Array.from(document.querySelectorAll('.choose-product'));
const persons = Array.from(document.querySelectorAll('.choose-person'));
const likeBtn = document.querySelector('#add-like')

const searchPersonsInput = document.querySelector('#personsSearch');
const searchPoductsInput = document.querySelector('#productsSearch');

const getPersons = [...document.querySelectorAll('#person-table tr')];
const getProducts = [...document.querySelectorAll('#product-table tr')]

const personTable = document.querySelector('#person-table');
const productTable = document.querySelector('#product-table');

const searchPersonString = e => {
    const currentString = e.target.value
    let result = getPersons
    result = result.filter(getPerson => getPerson.children[1].innerHTML.includes(currentString));
    personTable.innerHTML = '';
    result.forEach(name => personTable.appendChild(name));
}

searchPersonsInput.addEventListener('input', searchPersonString);


const searchProductString = e => {
    const currentString = e.target.value
    let result = getProducts
    result = result.filter(getProduct => getProduct.children[1].innerHTML.includes(currentString));
    productTable.innerHTML = '';
    result.forEach(name => productTable.appendChild(name));
}

searchPoductsInput.addEventListener('input', searchProductString);


let product_id = null;
let person_id = null;

products.map(product => product.addEventListener('click', e => {
    product_id = e.target.getAttribute('product-id');
    console.log(product_id);
}))

persons.map(person => person.addEventListener('click', e => {
    person_id = e.target.getAttribute('person-id');
    console.log(person_id);
}))

likeBtn.addEventListener('click', e => {
    if(person_id && product_id)
    {
        e.preventDefault();
            fetch(`/personLikeProduct/add`, {
                    method: 'POST',
                    body: JSON.stringify({
                            person: person_id,
                            product: product_id
                        }),
                        headers: {
                            "Content-type": "application/json; charset=UTF-8"
                        }
                }).then(window.location.replace('/personLikeProduct'));

        product_id = null;
        person_id = null;
    } else {

    }
})
