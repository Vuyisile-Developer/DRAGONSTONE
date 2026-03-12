const products = [
    { id: 1, title: "Bamboo Utensil Set", price: 129.99, desc: "Sustainable bamboo utensils", image: "https://via.placeholder.com/600", carbon: 0.6 },
    { id: 2, title: "Beeswax Food Wrap", price: 69.50, desc: "Reusable food wrap", image: "https://via.placeholder.com/600", carbon: 0.2 },
    { id: 3, title: "Compostable Dish Sponge", price: 39.99, desc: "Biodegradable sponge", image: "https://via.placeholder.com/600", carbon: 0.05 }
];
const productListEl = document.getElementById('product-list');
const cart = JSON.parse(localStorage.getItem('cart') || '[]');

function renderProducts() {
    productListEl.innerHTML = '';
    products.forEach(p = & gt; {
        const col = document.createElement('div');
        col.className = 'col-12 col-sm-6 col-lg-4 mb-4';
        col.innerHTML = `
  &lt;div class="card product-card h-100"&gt;
   &lt;img src="${p.image}" class="card-img-top" alt="${p.title}"&gt;
   &lt;div class="card-body d-flex flex-column"&gt;
    &lt;h5 class="card-title"&gt;${p.title}
    &lt;p class="card-text text-muted"&gt;R ${p.price.toFixed(2)}<p></p>
    &lt;div class="mt-auto d-flex justify-content-between"&gt;
     &lt;button class="btn btn-outline-primary btn-sm" onclick="showProduct(${p.id})"&gt;View&lt;/button&gt;
     &lt;button class="btn btn-success btn-sm" onclick="addToCart(${p.id})"&gt;Add&lt;/button&gt;
    
   
  `;
        productListEl.appendChild(col);
    });
    document.getElementById('cart-count').innerText = cart.length;
}

function showProduct(id) {
    const p = products.find(x = & gt; x.id === id);
    document.getElementById('productTitle').innerText = p.title;
    document.getElementById('productImage').src = p.image;
    document.getElementById('productDesc').innerText = p.desc;
    document.getElementById('productPrice').innerText = p.price.toFixed(2);
    document.getElementById('productCarbon').innerText = p.carbon;
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
    document.getElementById('addToCartBtn').onclick = () = & gt; { addToCart(id);
        modal.hide(); };
}

function addToCart(id) {
    cart.push(id);
    localStorage.setItem('cart', JSON.stringify(cart));
    document.getElementById('cart-count').innerText = cart.length;
    alert('Added to cart');
}

document.getElementById('cartBtn').addEventListener('click', () = & gt; {
    renderCartPanel();
    new bootstrap.Offcanvas(document.getElementById('cartPanel')).show();
});

function renderCartPanel() {
    const el = document.getElementById('cartItems');
    el.innerHTML = '';
    if (cart.length === 0) { el.innerHTML = '<p>Your cart is empty.</p>'; return; }
    const counts = {};
    cart.forEach(id = & gt; counts[id] = (counts[id] || 0) + 1);
    Object.keys(counts).forEach(id = & gt; {
        const p = products.find(x = & gt; x.id == id);
        el.innerHTML += `&lt;div class="d-flex justify-content-between mb-2"&gt;
  <div><strong>${p.title}</strong>&lt;div class="text-muted"&gt;R ${p.price.toFixed(2)}</div>
  <div>${counts[id]} x R ${p.price.toFixed(2)}</div>
  `;
    });
}

renderProducts();