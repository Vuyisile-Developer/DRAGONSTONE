&lt;?php
header('Content-Type: application/json');
$host = 'localhost';
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
 echo json_encode(['success' =&gt; false, 'error' =&gt; 'Database connection failed']);
 exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? 0;

if ($id &lt;= 0) {
 echo json_encode(['success' =&gt; false, 'error' =&gt; 'Invalid product ID']);
 exit;
}

try {
 $stmt = $pdo-&gt;prepare("DELETE FROM products WHERE product_id = ?");
 $stmt-&gt;execute([$id]);
 echo json_encode(['success' =&gt; true]);
} catch (Exception $e) {
 echo json_encode(['success' =&gt; false, 'error' =&gt; $e-&gt;getMessage()]);
}