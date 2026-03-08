<?php
/**
 * MySQL Script Runner
 * A secure PHP page to run MySQL scripts and queries
 */


// Database configuration
$host = 'localhost';
$username = 'amallmpt_rayglobals_usr';           // Change to your DB username
$password = '*&w30J1LIWCz';               // Change to your DB password
$database = 'amallmpt_rayglobals_db';               // Leave empty to run without selecting DB, or specify default DB

// Security - Change this to a strong password
$admin_password = 'admin1GIG+cM}haL?123';  // ⚠️ CHANGE THIS!

session_start();

// Check if user is authenticated
$is_authenticated = false;
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    $is_authenticated = true;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['authenticated'] = true;
        $is_authenticated = true;
    } else {
        $error = "❌ Invalid password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Database connection
$conn = null;
if ($is_authenticated) {
    try {
        $conn = new mysqli($host, $username, $password);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    } catch (Exception $e) {
        $conn = null;
    }
}

$success_message = '';
$error_message = '';
$query_result = null;
$execution_time = 0;

// Handle SQL execution
if ($is_authenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // Handle file upload
    if ($_POST['action'] === 'upload_file' && isset($_FILES['sql_file'])) {
        $file = $_FILES['sql_file'];
        
        // Validate file
        if ($file['size'] > 5242880) { // 5MB limit
            $error_message = "❌ File too large (max 5MB)";
        } elseif ($file['type'] !== 'application/x-sql' && pathinfo($file['name'], PATHINFO_EXTENSION) !== 'sql') {
            $error_message = "❌ Only .sql files allowed";
        } else {
            $sql_content = file_get_contents($file['tmp_name']);
            $_POST['sql_query'] = $sql_content;
            $_POST['action'] = 'execute';
        }
    }
    
    // Handle direct query execution
    if ($_POST['action'] === 'execute' && isset($_POST['sql_query'])) {
        $sql_query = trim($_POST['sql_query']);
        
        if (empty($sql_query)) {
            $error_message = "❌ SQL query is empty";
        } else {
            try {
                $start_time = microtime(true);
                
                // Select database if specified
                if (!empty($_POST['selected_db'])) {
                    $conn->select_db($_POST['selected_db']);
                }
                
                // Execute query
                $result = $conn->multi_query($sql_query);
                
                if ($result) {
                    $execution_time = round((microtime(true) - $start_time) * 1000, 2);
                    $success_message = "✅ Query executed successfully in {$execution_time}ms";
                    
                    // Try to fetch results from first result set
                    if ($conn->store_result()) {
                        $query_result = $conn->store_result();
                    }
                    
                    // Clear remaining result sets
                    while ($conn->next_result()) {
                        if ($res = $conn->store_result()) {
                            $res->free();
                        }
                    }
                } else {
                    $execution_time = round((microtime(true) - $start_time) * 1000, 2);
                    $error_message = "❌ Query failed: " . $conn->error . " ({$execution_time}ms)";
                }
            } catch (Exception $e) {
                $execution_time = round((microtime(true) - $start_time) * 1000, 2);
                $error_message = "❌ Error: " . $e->getMessage() . " ({$execution_time}ms)";
            }
        }
    }
}

// Get list of databases
$databases = [];
if ($is_authenticated && $conn) {
    $result = $conn->query("SHOW DATABASES");
    if ($result) {
        while ($row = $result->fetch_row()) {
            $databases[] = $row[0];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Script Runner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 900px;
            padding: 30px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        
        h1 {
            color: #333;
            font-size: 28px;
        }
        
        .auth-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .auth-btn:hover {
            background: #c0392b;
        }
        
        /* Login Form */
        .login-container {
            max-width: 400px;
            margin: 50px auto;
        }
        
        .login-form {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        
        .login-form h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        input[type="password"],
        input[type="text"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Courier New', monospace;
        }
        
        input[type="password"]:focus,
        input[type="text"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        textarea {
            resize: vertical;
            min-height: 200px;
            font-family: 'Courier New', monospace;
        }
        
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: background 0.3s;
        }
        
        button:hover {
            background: #5568d3;
        }
        
        /* Messages */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        
        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        
        /* Database Selection */
        .db-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .db-selector label {
            grid-column: 1;
        }
        
        .db-selector select {
            grid-column: 1 / -1;
        }
        
        /* File Upload */
        .file-upload {
            border: 2px dashed #667eea;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
            background: #f8f9fa;
        }
        
        .file-upload input[type="file"] {
            display: none;
        }
        
        .file-upload-label {
            display: block;
            cursor: pointer;
            color: #667eea;
            font-weight: 500;
        }
        
        .file-upload-label:hover {
            text-decoration: underline;
        }
        
        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .tab-btn {
            background: transparent;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            color: #999;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            width: auto;
            transition: all 0.3s;
        }
        
        .tab-btn.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Results Table */
        .results {
            margin-top: 20px;
        }
        
        .results h3 {
            color: #333;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: #f8f9fa;
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:hover {
            background: #f0f0f0;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        .button-group button {
            flex: 1;
        }
        
        .button-group button:nth-child(2) {
            background: #6c757d;
        }
        
        .button-group button:nth-child(2):hover {
            background: #5a6268;
        }
        
        .info {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 12px;
            margin-top: 15px;
            border-radius: 5px;
            color: #0c5aa0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (!$is_authenticated): ?>
        <!-- Login Form -->
        <div class="login-container">
            <div class="login-form">
                <h2>🔐 MySQL Script Runner</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="password">Admin Password:</label>
                        <input type="password" id="password" name="password" required autofocus>
                    </div>
                    <button type="submit">Login</button>
                </form>
                
            </div>
        </div>
    <?php else: ?>
        <!-- Main Interface -->
        <header>
            <h1>🗄️ MySQL Script Runner</h1>
            <a href="?logout=1" class="auth-btn">Logout</a>
        </header>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <!-- Database Selection -->
        <?php if (!empty($databases)): ?>
            <div class="form-group">
                <label for="selected_db">Select Database:</label>
                <select id="selected_db" name="selected_db" onchange="document.getElementById('selected_db_hidden').value = this.value">
                    <option value="">-- No database selected --</option>
                    <?php foreach ($databases as $db): ?>
                        <option value="<?php echo htmlspecialchars($db); ?>"><?php echo htmlspecialchars($db); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" id="selected_db_hidden" name="selected_db" value="">
            </div>
        <?php endif; ?>
        
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('query')">📝 SQL Query</button>
            <button class="tab-btn" onclick="switchTab('upload')">📤 Upload File</button>
        </div>
        
        <!-- Tab: Direct Query -->
        <div id="query" class="tab-content active">
            <form method="POST">
                <input type="hidden" name="action" value="execute">
                <input type="hidden" name="selected_db" id="db_input" value="">
                <div class="form-group">
                    <label for="sql_query">SQL Query:</label>
                    <textarea id="sql_query" name="sql_query" placeholder="Enter your SQL query here...&#10;&#10;Example:&#10;SELECT * FROM users;&#10;&#10;INSERT INTO users VALUES (...);&#10;&#10;You can run multiple queries separated by semicolons"></textarea>
                </div>
                <div class="button-group">
                    <button type="submit">▶️ Execute Query</button>
                    <button type="reset">🔄 Clear</button>
                </div>
            </form>
        </div>
        
        <!-- Tab: File Upload -->
        <div id="upload" class="tab-content">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="upload_file">
                <input type="hidden" name="selected_db" id="db_input_upload" value="">
                
                <div class="file-upload">
                    <label class="file-upload-label" for="sql_file">
                        📁 Click to select SQL file or drag & drop
                    </label>
                    <input type="file" id="sql_file" name="sql_file" accept=".sql" required>
                    <p id="file-name" style="margin-top: 10px; color: #999; font-size: 14px;"></p>
                </div>
                
                <div class="button-group">
                    <button type="submit">⬆️ Upload & Execute</button>
                </div>
            </form>
        </div>
        
        <!-- Results -->
        <?php if ($query_result && $query_result->num_rows > 0): ?>
            <div class="results">
                <h3>📊 Results (<?php echo $query_result->num_rows; ?> rows)</h3>
                <table>
                    <thead>
                        <tr>
                            <?php
                            $fields = $query_result->fetch_fields();
                            foreach ($fields as $field) {
                                echo "<th>" . htmlspecialchars($field->name) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $query_result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $cell) {
                                echo "<td>" . htmlspecialchars($cell ?? 'NULL') . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <div class="info">
            ℹ️ <strong>Tips:</strong> You can execute multiple queries at once. Use proper SQL syntax and separate queries with semicolons.
        </div>
    
    <?php endif; ?>
</div>

<script>
    function switchTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });
        document.querySelectorAll('.tab-btn').forEach(el => {
            el.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tab).classList.add('active');
        event.target.classList.add('active');
        
        // Update database selection in hidden input
        const dbSelect = document.getElementById('selected_db');
        if (dbSelect) {
            document.getElementById('db_input').value = dbSelect.value;
            document.getElementById('db_input_upload').value = dbSelect.value;
        }
    }
    
    // File input display name
    document.getElementById('sql_file')?.addEventListener('change', function() {
        const fileName = this.files[0]?.name || '';
        document.getElementById('file-name').textContent = fileName ? `Selected: ${fileName}` : '';
    });
    
    // Drag & drop
    const fileUpload = document.querySelector('.file-upload');
    if (fileUpload) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUpload.addEventListener(eventName, () => {
                fileUpload.style.background = '#e7f3ff';
                fileUpload.style.borderColor = '#2196F3';
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileUpload.addEventListener(eventName, () => {
                fileUpload.style.background = '#f8f9fa';
                fileUpload.style.borderColor = '#667eea';
            }, false);
        });
        
        fileUpload.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('sql_file').files = files;
            document.getElementById('file-name').textContent = `Selected: ${files[0]?.name || ''}`;
        }, false);
    }
</script>

</body>
</html>
