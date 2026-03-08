<?php
/**
 * Error Log Viewer
 * A secure PHP page to view and manage error logs
 */

// Security - Change this to a strong password
$admin_password = 'admin1GIG+cM}haL?123';  // ⚠️ CHANGE THIS!

// Log file paths - Add your log files here
$log_files = [
    'Admin Log' => ini_get('error_log') ?: '/var/log/php-errors.log',
    'Log User' => '/home/amallmpt/WEB/rayglobals.org/error_log',
    
];

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

// Handle actions
$selected_log = isset($_GET['log']) ? $_GET['log'] : (array_key_first($log_files) ?: '');
$action = isset($_GET['action']) ? $_GET['action'] : '';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_level = isset($_GET['level']) ? $_GET['level'] : '';
$tail_lines = isset($_GET['tail']) ? max(10, (int)$_GET['tail']) : 100;

$log_content = '';
$log_info = [];
$filtered_content = '';
$total_lines = 0;
$error_counts = [];

if ($is_authenticated && isset($log_files[$selected_log])) {
    $log_path = $log_files[$selected_log];
    
    // Check if file exists and is readable
    if (file_exists($log_path) && is_readable($log_path)) {
        // Get file info
        $log_info = [
            'path' => $log_path,
            'size' => filesize($log_path),
            'size_mb' => round(filesize($log_path) / 1024 / 1024, 2),
            'modified' => date('Y-m-d H:i:s', filemtime($log_path)),
            'writable' => is_writable($log_path),
        ];
        
        // Handle clear log
        if ($action === 'clear' && $log_info['writable']) {
            file_put_contents($log_path, '');
            $success_msg = "✅ Log file cleared successfully";
            $log_info['size'] = 0;
            $log_info['size_mb'] = 0;
        }
        
        // Handle download
        if ($action === 'download') {
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . basename($log_path) . '.txt"');
            header('Content-Length: ' . $log_info['size']);
            readfile($log_path);
            exit;
        }
        
        // Read log file (last N lines for large files)
        if ($log_info['size'] > 10485760) { // 10MB
            // For large files, read from the end
            $handle = fopen($log_path, 'r');
            fseek($handle, -min(1048576, $log_info['size']), SEEK_END); // Read last 1MB
            $log_content = fread($handle, 1048576);
            fclose($handle);
        } else {
            $log_content = file_get_contents($log_path);
        }
        
        // Process log content
        $lines = array_reverse(explode("\n", $log_content));
        $total_lines = count($lines);
        
        // Filter and search
        $filtered_lines = [];
        $error_level_map = [];
        
        foreach ($lines as $line) {
            if (empty($line)) continue;
            
            // Detect error level
            $level = 'OTHER';
            if (stripos($line, 'error') !== false) {
                $level = 'ERROR';
            } elseif (stripos($line, 'warning') !== false) {
                $level = 'WARNING';
            } elseif (stripos($line, 'notice') !== false) {
                $level = 'NOTICE';
            } elseif (stripos($line, 'fatal') !== false) {
                $level = 'FATAL';
            } elseif (stripos($line, 'deprecated') !== false) {
                $level = 'DEPRECATED';
            }
            
            $error_level_map[$line] = $level;
            if (!isset($error_counts[$level])) {
                $error_counts[$level] = 0;
            }
            $error_counts[$level]++;
            
            // Apply filters
            $matches_search = empty($search_term) || stripos($line, $search_term) !== false;
            $matches_level = empty($filter_level) || $level === $filter_level;
            
            if ($matches_search && $matches_level) {
                $filtered_lines[] = $line;
            }
        }
        
        // Limit display
        $displayed_lines = array_slice($filtered_lines, 0, $tail_lines);
        
        // Format output
        $filtered_content = '';
        foreach ($displayed_lines as $line) {
            $level = $error_level_map[$line];
            $level_class = strtolower($level);
            
            // Highlight search term
            if (!empty($search_term)) {
                $line = preg_replace(
                    '/(' . preg_quote($search_term, '/') . ')/i',
                    '<span class="highlight">$1</span>',
                    $line
                );
            }
            
            $filtered_content .= '<div class="log-line log-' . $level_class . '"><span class="level">[' . $level . ']</span> ' . htmlspecialchars($line) . '</div>';
        }
    } else {
        $error_msg = "❌ Log file not found or not readable: " . $log_path;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Log Viewer</title>
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
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        h1 {
            color: #333;
            font-size: 28px;
        }
        
        .header-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        button, a.btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }
        
        button:hover, a.btn:hover {
            background: #5568d3;
        }
        
        button.danger {
            background: #e74c3c;
        }
        
        button.danger:hover {
            background: #c0392b;
        }
        
        button.secondary {
            background: #6c757d;
        }
        
        button.secondary:hover {
            background: #5a6268;
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
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
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
        
        .alert-info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        
        /* Log Selection */
        .log-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .log-selector label {
            margin: 0;
        }
        
        .log-selector select {
            margin: 0;
        }
        
        /* Log Info */
        .log-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        
        .log-info div {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .log-info strong {
            color: #333;
        }
        
        /* Filters */
        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        /* Error Counts */
        .error-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        
        .stat-card.error {
            border-left-color: #e74c3c;
        }
        
        .stat-card.warning {
            border-left-color: #f39c12;
        }
        
        .stat-card.notice {
            border-left-color: #3498db;
        }
        
        .stat-card.fatal {
            border-left-color: #c0392b;
        }
        
        .stat-card.deprecated {
            border-left-color: #9b59b6;
        }
        
        .stat-count {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Log Viewer */
        .log-viewer {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-height: 600px;
            overflow-y: auto;
            line-height: 1.6;
        }
        
        .log-line {
            padding: 5px;
            border-bottom: 1px solid #2d2d2d;
            word-break: break-all;
            white-space: pre-wrap;
        }
        
        .log-line:hover {
            background: #2d2d2d;
        }
        
        .level {
            font-weight: bold;
            margin-right: 10px;
            display: inline-block;
            min-width: 80px;
        }
        
        .log-error .level {
            color: #f48771;
        }
        
        .log-warning .level {
            color: #dcdcaa;
        }
        
        .log-notice .level {
            color: #9cdcfe;
        }
        
        .log-fatal .level {
            color: #ff6b6b;
        }
        
        .log-deprecated .level {
            color: #c586c0;
        }
        
        .log-other .level {
            color: #858585;
        }
        
        .highlight {
            background: #2d2d2d;
            color: #ffd700;
            padding: 2px 4px;
            border-radius: 3px;
        }
        
        .empty-log {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .info-box {
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
                <h2>🔐 Error Log Viewer</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="action" value="login">
                    <div class="form-group">
                        <label for="password">Admin Password:</label>
                        <input type="password" id="password" name="password" required autofocus>
                    </div>
                    <button type="submit" style="width: 100%;">Login</button>
                </form>
                
            </div>
        </div>
    <?php else: ?>
        <!-- Main Interface -->
        <header>
            <h1>📋 Error Log Viewer</h1>
            <div class="header-buttons">
                <a href="?logout=1" class="btn" style="background: #e74c3c;">Logout</a>
            </div>
        </header>
        
        <?php if (isset($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_msg)): ?>
            <div class="alert alert-error"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        
        <!-- Log Selection -->
        <div class="log-selector">
            <form method="GET" style="display: flex; gap: 10px; width: 100%; flex-wrap: wrap;">
                <label for="log" style="margin: 0; padding-top: 5px;">Select Log File:</label>
                <select id="log" name="log" onchange="this.form.submit()" style="margin: 0;">
                    <?php foreach ($log_files as $name => $path): ?>
                        <option value="<?php echo htmlspecialchars($name); ?>" <?php echo $selected_log === $name ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        
        <?php if (!empty($log_info)): ?>
            <!-- Log Info -->
            <div class="log-info">
                <div><strong>Path:</strong> <?php echo htmlspecialchars($log_info['path']); ?></div>
                <div><strong>Size:</strong> <?php echo $log_info['size_mb']; ?> MB (<?php echo number_format($log_info['size']); ?> bytes)</div>
                <div><strong>Last Modified:</strong> <?php echo $log_info['modified']; ?></div>
                <div><strong>Writable:</strong> <?php echo $log_info['writable'] ? '✅ Yes' : '❌ No'; ?></div>
                <div><strong>Total Lines:</strong> <?php echo number_format($total_lines); ?></div>
            </div>
            
            <!-- Error Statistics -->
            <?php if (!empty($error_counts)): ?>
                <div class="error-stats">
                    <?php foreach ($error_counts as $level => $count): ?>
                        <div class="stat-card <?php echo strtolower($level); ?>">
                            <div class="stat-count"><?php echo number_format($count); ?></div>
                            <div class="stat-label"><?php echo $level; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Filters -->
            <div class="filters">
                <div class="filter-group">
                    <label for="search">🔍 Search:</label>
                    <form method="GET" style="display: flex; gap: 5px;">
                        <input type="hidden" name="log" value="<?php echo htmlspecialchars($selected_log); ?>">
                        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="Search term...">
                        <button type="submit" class="secondary" style="padding: 8px 15px;">Search</button>
                        <a href="?log=<?php echo urlencode($selected_log); ?>" class="btn secondary" style="padding: 8px 15px; text-decoration: none;">Clear</a>
                    </form>
                </div>
                
                <div class="filter-group">
                    <label for="level">📊 Error Level:</label>
                    <form method="GET" style="display: flex; gap: 5px;">
                        <input type="hidden" name="log" value="<?php echo htmlspecialchars($selected_log); ?>">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                        <select id="level" name="level" onchange="this.form.submit()">
                            <option value="">-- All Levels --</option>
                            <option value="ERROR" <?php echo $filter_level === 'ERROR' ? 'selected' : ''; ?>>ERROR</option>
                            <option value="WARNING" <?php echo $filter_level === 'WARNING' ? 'selected' : ''; ?>>WARNING</option>
                            <option value="NOTICE" <?php echo $filter_level === 'NOTICE' ? 'selected' : ''; ?>>NOTICE</option>
                            <option value="FATAL" <?php echo $filter_level === 'FATAL' ? 'selected' : ''; ?>>FATAL</option>
                            <option value="DEPRECATED" <?php echo $filter_level === 'DEPRECATED' ? 'selected' : ''; ?>>DEPRECATED</option>
                        </select>
                    </form>
                </div>
                
                <div class="filter-group">
                    <label for="tail">📌 Show Lines:</label>
                    <form method="GET" style="display: flex; gap: 5px;">
                        <input type="hidden" name="log" value="<?php echo htmlspecialchars($selected_log); ?>">
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
                        <input type="hidden" name="level" value="<?php echo htmlspecialchars($filter_level); ?>">
                        <input type="number" id="tail" name="tail" value="<?php echo $tail_lines; ?>" min="10" max="10000">
                        <button type="submit" class="secondary" style="padding: 8px 15px;">Update</button>
                    </form>
                </div>
            </div>
            
            <!-- Log Viewer -->
            <div class="log-viewer">
                <?php if (!empty($filtered_content)): ?>
                    <?php echo $filtered_content; ?>
                    <div style="margin-top: 20px; text-align: center; color: #666;">
                        Showing <?php echo count($displayed_lines); ?> of <?php echo count($filtered_lines); ?> matching lines
                    </div>
                <?php else: ?>
                    <div class="empty-log">
                        📭 No log entries match your filters
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="?log=<?php echo urlencode($selected_log); ?>&action=download" class="btn" style="text-decoration: none;">⬇️ Download Log</a>
                <?php if ($log_info['writable']): ?>
                    <button onclick="if(confirm('Are you sure? This will clear the log file.')) window.location='?log=<?php echo urlencode($selected_log); ?>&action=clear';" class="btn danger">🗑️ Clear Log</button>
                <?php endif; ?>
            </div>
            
            <div class="info-box">
                ℹ️ <strong>Tips:</strong> 
                <ul style="margin: 10px 0 0 20px;">
                    <li>Search is case-insensitive</li>
                    <li>Errors are automatically color-coded by severity</li>
                    <li>Use the download button to export logs</li>
                    <li>Increase "Show Lines" for older entries in large files</li>
                </ul>
            </div>
        <?php endif; ?>
    
    <?php endif; ?>
</div>

</body>
</html>
