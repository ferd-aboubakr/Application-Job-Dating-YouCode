<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
        
        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #4CAF50;
            color: white;
        }
        
        .btn-secondary {
            background: #2196F3;
            color: white;
        }
        
        .btn-warning {
            background: #ff9800;
            color: white;
        }
        
        .btn-danger {
            background: #f44336;
            color: white;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .section {
            background: white;
            margin-bottom: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-header h2 {
            color: #333;
            font-size: 1.2rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        .table tr:hover {
            background: #f8f9fa;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .archived {
            opacity: 0.6;
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-archived {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Manage Announcements</h1>
        <div class="header-actions">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_user']['name']); ?></span>
            <a href="/admin/dashboard" class="btn btn-secondary">Dashboard</a>
            <a href="/admin/create-annonce" class="btn btn-primary">Create Announcement</a>
            <a href="/admin/logout" class="btn btn-danger">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="success">
                <?php
                if ($_GET['success'] === 'annonce_created') {
                    echo 'Announcement created successfully!';
                } elseif ($_GET['success'] === 'annonce_updated') {
                    echo 'Announcement updated successfully!';
                } elseif ($_GET['success'] === 'annonce_archived') {
                    echo 'Announcement archived successfully!';
                }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="section">
            <div class="section-header">
                <h2>Active Announcements</h2>
                <a href="/admin/archived-annonces" class="btn btn-secondary">View Archives</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Contract Type</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($annonces as $annonce): ?>
                    <?php if (!$annonce['deleted']): ?>
                    <tr>
                        <td><?php echo $annonce['id']; ?></td>
                        <td><?php echo htmlspecialchars($annonce['titre']); ?></td>
                        <td><?php echo htmlspecialchars($annonce['entreprise_name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($annonce['type_contrat']); ?></td>
                        <td><?php echo htmlspecialchars($annonce['localisation']); ?></td>
                        <td>
                            <span class="status-badge status-active">Active</span>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($annonce['created_at'])); ?></td>
                        <td>
                            <div class="actions">
                                <a href="/admin/edit-annonce/<?php echo $annonce['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                <form method="POST" action="/admin/archive-annonce/<?php echo $annonce['id']; ?>" style="display: inline;">
                                    <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>">
                                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to archive this announcement?')">Archive</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
