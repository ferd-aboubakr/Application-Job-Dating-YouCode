<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Opportunities - Student Dashboard</title>
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
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
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
        
        .btn-danger {
            background: #f44336;
            color: white;
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
        
        .search-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr auto;
            gap: 1rem;
            align-items: end;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 2px solid #e1e1e1;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
        }
        
        .btn-primary {
            background: #4CAF50;
            color: white;
            padding: 0.75rem 1.5rem;
        }
        
        .annonces-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }
        
        .annonce-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .annonce-card:hover {
            transform: translateY(-5px);
        }
        
        .annonce-header {
            background: #f8f9fa;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .annonce-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .annonce-company {
            color: #666;
            font-size: 0.9rem;
        }
        
        .annonce-body {
            padding: 1rem;
        }
        
        .annonce-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .meta-item {
            background: #e9ecef;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
            color: #495057;
        }
        
        .annonce-description {
            color: #666;
            line-height: 1.5;
            margin-bottom: 1rem;
        }
        
        .annonce-skills {
            margin-top: 1rem;
        }
        
        .skills-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .skill-tag {
            background: #4CAF50;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            font-size: 0.8rem;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .annonces-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Job Opportunities</h1>
        <div class="header-actions">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['student_user']['name']); ?></span>
            <a href="/student/logout" class="btn btn-danger">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <div class="search-section">
            <form id="searchForm" class="search-form">
                <input type="hidden" name="_csrf" value="<?php echo htmlspecialchars($csrfToken ?? ''); ?>">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="q" placeholder="Search by title, description, or skills...">
                </div>
                
                <div class="form-group">
                    <label for="entreprise">Company</label>
                    <select id="entreprise" name="entreprise">
                        <option value="">All Companies</option>
                        <?php foreach ($entreprises as $entreprise): ?>
                            <option value="<?php echo $entreprise['id']; ?>">
                                <?php echo htmlspecialchars($entreprise['NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="contrat">Contract Type</label>
                    <select id="contrat" name="contrat">
                        <option value="">All Types</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                        <option value="Freelance">Freelance</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        
        <div id="annoncesContainer" class="annonces-grid">
            <div class="loading">Loading job opportunities...</div>
        </div>
    </div>
    
    <script>
        const annoncesContainer = document.getElementById('annoncesContainer');
        const searchForm = document.getElementById('searchForm');
        
       function displayAnnonces(annonces) {
    if (!annonces || annonces.length === 0) {
        annoncesContainer.innerHTML = '<div class="no-results">No job opportunities found matching your criteria.</div>';
        return;
    }
    
    annoncesContainer.innerHTML = annonces.map(annonce => {
        // Safety: Ensure description and competences aren't null
        const description = annonce.description || '';
        const competences = annonce.competences || '';
        const titre = annonce.titre || 'No Title';

        return `
            <div class="annonce-card">
                <div class="annonce-header">
                    <div class="annonce-title">${escapeHtml(titre)}</div>
                    <div class="annonce-company">${escapeHtml(annonce.entreprise_name || 'N/A')}</div>
                </div>
                <div class="annonce-body">
                    <div class="annonce-meta">
                        <span class="meta-item">${escapeHtml(annonce.type_contrat || 'N/A')}</span>
                        <span class="meta-item">${escapeHtml(annonce.localisation || 'N/A')}</span>
                        <span class="meta-item">${formatDate(annonce.created_at)}</span>
                    </div>
                    <div class="annonce-description">
                        ${escapeHtml(description.substring(0, 200))}${description.length > 200 ? '...' : ''}
                    </div>
                    ${competences ? `
                        <div class="annonce-skills">
                            <div class="skills-label">Required Skills:</div>
                            <div class="skills-list">
                                ${competences.split(',').map(skill => 
                                    `<span class="skill-tag">${escapeHtml(skill.trim())}</span>`
                                ).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }).join('');
}
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        }
        
        function searchAnnonces() {
            const formData = new FormData(searchForm);
            const params = new URLSearchParams();
            
            for (let [key, value] of formData.entries()) {
                if (value) params.append(key, value);
            }
            
            annoncesContainer.innerHTML = '<div class="loading">Searching...</div>';
            
            fetch(`/student/search?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!Array.isArray(data)) {
                    annoncesContainer.innerHTML = '<div class="no-results">Error loading results.</div>';
                    return;
                }
                displayAnnonces(data);
            })
            .catch(() => {
                annoncesContainer.innerHTML = '<div class="no-results">Error loading results.</div>';
            });
        }
        
        // Load initial annonces via AJAX
        searchAnnonces();
        
        // Handle form submission
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            searchAnnonces();
        });
        
        // Live search (debounced) on input and filter changes
        let searchTimeout;
        const triggerLiveSearch = () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchAnnonces, 300);
        };

        document.getElementById('search').addEventListener('input', triggerLiveSearch);
        document.getElementById('entreprise').addEventListener('change', triggerLiveSearch);
        document.getElementById('contrat').addEventListener('change', triggerLiveSearch);
    </script>
</body>
</html>
