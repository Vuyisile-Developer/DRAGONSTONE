const products = [
    { id: 1, title: 'Bamboo Utensil Set', price: 129.99 },
    { id: 2, title: 'Beeswax Wrap', price: 69.50 }
];

function show(id) {
    document.querySelectorAll('.admin-pane').forEach(p = & gt; p.classList.add('d-none'));
    document.getElementById(id).classList.remove('d-none');
}

function populateProducts() {
    const tbody = document.querySelector('#productTable tbody');
    tbody.innerHTML = '';
    products.forEach(p = & gt; {
        const tr = document.createElement('tr');
        tr.innerHTML = `${p.id}${p.title}R ${p.price.toFixed(2)}
  &lt;button class="btn btn-sm btn-warning"&gt;Edit&lt;/button&gt; &lt;button class="btn btn-sm btn-danger"&gt;Delete&lt;/button&gt;`;
        tbody.appendChild(tr);
    });
    document.getElementById('stat-products').innerText = products.length;
}

function newProduct() {
    alert('Show product create form (prototype)');
}

populateProducts();