# Job Dating Platform

A comprehensive job dating platform built with a custom PHP MVC framework, connecting YouCode students with employment opportunities.

## Features

### Admin Dashboard
- **Authentication**: Secure login with CSRF protection and 2-hour session expiration
- **Statistics**: Real-time dashboard with active/archived announcements, companies, and students
- **Announcement Management**: Full CRUD operations with soft delete/archive functionality
- **Company Management**: Create and manage partner companies
- **Student Consultation**: View registered students with statistics
- **Security**: CSRF protection, XSS prevention, SQL injection protection

### Student Portal
- **Registration**: Student account creation with promotion and specialization
- **Authentication**: Secure login with session management
- **Job Search**: Real-time AJAX search across titles, descriptions, and skills
- **Filtering**: Filter by company and contract type
- **Responsive Design**: Mobile-friendly interface

## Technical Architecture

### Framework Components
- **MVC Architecture**: Clean separation of concerns
- **Custom Router**: URL routing with parameter support
- **Database Layer**: PDO with prepared statements
- **Security Class**: CSRF tokens, XSS protection, input sanitization
- **Session Management**: Secure sessions with expiration
- **Template System**: PHP views with reusable components

### Security Features
- **CSRF Protection**: Tokens on all forms
- **XSS Prevention**: Output escaping and input sanitization
- **SQL Injection Prevention**: Prepared statements only
- **Session Security**: Secure cookies, regeneration, and expiration
- **Password Security**: Bcrypt hashing

## Installation

### Prerequisites
- PHP 8.0+
- MySQL/MariaDB
- Apache/Nginx with mod_rewrite
- Composer

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Application-Job-Dating-YouCode-develop
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Database Setup**
   ```bash
   mysql -u root -p job_dating_v2 < database/schema.sql
   ```

4. **Configuration**
   - Copy `config/config.php` and update database credentials
   - Set up virtual host pointing to `public/` directory

5. **Create Admin User**
   ```sql
   INSERT INTO users (name, email, password_hash, ROLE) VALUES 
   ('Admin User', 'admin@jobdating.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
   ```

## Usage

### Admin Access
- **URL**: `/admin/login`
- **Default Credentials**: 
  - Email: `admin@jobdating.com`
  - Password: `password`

### Student Access
- **Registration**: `/student/register`
- **Login**: `/student/login`
- **Job Board**: `/student/annonces`

## Database Schema

### Tables
- **users**: Admin and student accounts with role-based access
- **entreprises**: Partner company information
- **annonces**: Job offers with soft delete support

### Relationships
- `annonces.entreprise_id` → `entreprises.id` (Foreign Key)
- `users.ROLE` enum('admin', 'apprenant') for role management

## API Endpoints

### Admin Routes
- `GET /admin/login` - Admin login page
- `POST /admin/login` - Admin authentication
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/annonces` - Manage announcements
- `GET /admin/archived-annonces` - View archived announcements
- `GET /admin/create-annonce` - Create announcement form
- `POST /admin/create-annonce` - Create announcement
- `GET /admin/edit-annonce/{id}` - Edit announcement form
- `POST /admin/edit-annonce/{id}` - Update announcement
- `POST /admin/archive-annonce/{id}` - Archive announcement
- `POST /admin/restore-annonce/{id}` - Restore announcement
- `GET /admin/create-entreprise` - Create company form
- `POST /admin/create-entreprise` - Create company
- `GET /admin/students` - View students
- `GET /admin/logout` - Admin logout

### Student Routes
- `GET /student/login` - Student login page
- `POST /student/login` - Student authentication
- `GET /student/register` - Student registration form
- `POST /student/register` - Student registration
- `GET /student/annonces` - Job board
- `GET /student/search` - AJAX search endpoint
- `GET /student/logout` - Student logout

## Security Considerations

### Implemented Protections
- **CSRF Tokens**: All forms include CSRF protection
- **Input Validation**: Server-side validation and sanitization
- **Output Escaping**: XSS prevention with htmlspecialchars
- **SQL Security**: Prepared statements prevent injection
- **Session Security**: Secure configuration with expiration
- **Password Security**: Bcrypt hashing for all passwords

### Best Practices
- Error handling without information disclosure
- Secure session configuration
- Regular security updates
- Input validation on all user data
- Principle of least privilege

## Project Structure

```
Application-Job-Dating-YouCode-develop/
├── app/
│   ├── controllers/          # Application controllers
│   ├── core/               # Framework core classes
│   ├── models/             # Data models
│   └── views/             # View templates
├── config/                # Configuration files
├── database/              # Database schema
├── public/                # Web root
├── vendor/                # Composer dependencies
└── README.md
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is part of the YouCode educational program.

## Support

For technical support or questions, please contact the development team.

---

**Note**: This project was developed as part of the YouCode Job Dating platform brief, focusing on security, best practices, and modern web development standards.
