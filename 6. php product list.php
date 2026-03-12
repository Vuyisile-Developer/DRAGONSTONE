&lt;?php
// === Database Connection ===
$host = 'localhost'; // Update for production
$db = 'dragonstone';
$user = 'dbuser';
$pass = 'dbpass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
 PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION,
 PDO::ATTR_DEFAULT_FETCH_MODE =&gt; PDO::FETCH_ASSOC,
];
try {
 $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
 error_log($e-&gt;getMessage());
 exit('Database connection error.');
}

// === Handle Product Creation (Admin) ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $title = trim($_POST['title'] ?? '');
 $desc = trim($_POST['description'] ?? '');
 $price = floatval($_POST['price'] ?? 0);
 $stock = intval($_POST['stock'] ?? 0);
 $category = intval($_POST['category_id'] ?? 0);
 $carbon = floatval($_POST['carbon_kg'] ?? 0);
 if ($title &amp;&amp; $price &gt; 0) {
  $sql = "INSERT INTO products (title, description, price, stock, category_id, carbon_kg)
   VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $pdo-&gt;prepare($sql);
  $stmt-&gt;execute([$title, $desc, $price, $stock, $category, $carbon]);
  $message = "Product added successfully.";
 } else {
  $message = "Please provide a valid title and price.";
 }
}

// === Fetch Products ===
$stmt = $pdo-&gt;query("
 SELECT p.product_id, p.title, p.price, p.stock, p.carbon_kg, c.name AS category, p.image
 FROM products p
 LEFT JOIN categories c ON p.category_id = c.category_id
 ORDER BY p.title
");
$products = $stmt-&gt;fetchAll();
?&gt;
&lt;!doctype html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
 &lt;meta charset="UTF-8"&gt;
 &lt;title&gt;Products - Dragonstone&lt;/title&gt;
 &lt;link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"&gt;
&lt;/head&gt;
&lt;body class="bg-light"&gt;
&lt;div class="container py-4"&gt;
 &lt;h1 class="mb-4"&gt;Product List
 &lt;?php if (!empty($message)): ?&gt;
 &lt;div class="alert alert-info"&gt;&lt;?= htmlspecialchars($message) ?&gt;
 &lt;?php endif; ?&gt;
 &lt;div class="row"&gt;
 &lt;?php foreach ($products as $p): ?&gt;
 &lt;div class="col-12 col-md-4 mb-4"&gt;
 &lt;div class="card h-100"&gt;
 &lt;img src="&lt;?= htmlspecialchars($p['image'] ?? 'placeholder.jpg') ?&gt;" class="card-img-top" alt="Product Image"&gt;
 &lt;div class="card-body"&gt;
 &lt;h5 class="card-title"&gt;&lt;?= htmlspecialchars($p['title']) ?&gt;
 &lt;p class="card-text"&gt;Price: R &lt;?= number_format($p['price'], 2) ?&gt;<p></p>
 &lt;p class="card-text"&gt;
 &lt;small&gt;Stock: &lt;?= intval($p['stock']) ?&gt; | CO₂: &lt;?= number_format($p['carbon_kg'], 2) ?&gt; kg&lt;/small&gt;
 <p></p>
 &lt;p class="card-text text-muted"&gt;
 &lt;small&gt;Category: &lt;?= htmlspecialchars($p['category'] ?? 'Uncategorized') ?&gt;&lt;/small&gt;
 <p></p>
 
 
 
 &lt;?php endforeach; ?&gt;
 
 &lt;hr class="my-5"&gt;
 <h2>Add New Product</h2>
 &lt;form method="post" class="row g-3 mt-3"&gt;
 &lt;div class="col-md-6"&gt;
 &lt;label class="form-label"&gt;Title&lt;/label&gt;
 &lt;input type="text" name="title" class="form-control" required&gt;
 
 &lt;div class="col-md-6"&gt;
 &lt;label class="form-label"&gt;Category ID&lt;/label&gt;
 &lt;input type="number" name="category_id" class="form-control" min="0"&gt;
 
 &lt;div class="col-md-4"&gt;
 &lt;label class="form-label"&gt;Price (R)&lt;/label&gt;
 &lt;input type="number" step="0.01" name="price" class="form-control" required&gt;
 
 &lt;div class="col-md-4"&gt;
 &lt;label class="form-label"&gt;Stock&lt;/label&gt;
 &lt;input type="number" name="stock" class="form-control" min="0"&gt;
 
 &lt;div class="col-md-4"&gt;
 &lt;label class="form-label"&gt;Carbon (kg)&lt;/label&gt;
 &lt;input type="number" step="0.01" name="carbon_kg" class="form-control"&gt;
 
 &lt;div class="col-12"&gt;
 &lt;label class="form-label"&gt;Description&lt;/label&gt;
 &lt;textarea name="description" class="form-control" rows="3"&gt;&lt;/textarea&gt;
 
 &lt;div class="col-12"&gt;
 &lt;button class="btn btn-success"&gt;Add Product&lt;/button&gt;
 
 &lt;/form&gt;

&lt;/body&gt;
&lt;/html&gt;