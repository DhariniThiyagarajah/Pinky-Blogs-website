# Anime Journal

A cozy blogging platform for anime lovers to write about reviews, characters, theories, and personal experiences. Built with PHP, MySQL, HTML, CSS, and vanilla JavaScript for a university assignment.

## Project Description

Anime Journal is a simple, secure blog application where registered users can create, read, update, and delete their own blog posts. The design draws inspiration from Studio Ghibli aesthetics, Japanese countryside cafés, and vintage journals — warm, peaceful, and focused on readability.

**Features included:**
- User registration, login, and logout
- Session-based authentication
- Create, read, update, and delete blog posts
- Ownership verification for edit and delete actions
- Responsive design for desktop, tablet, and mobile

## Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP (mysqli, prepared statements, sessions)
- **Database:** MySQL

## Installation Steps

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (includes Apache, PHP, and MySQL)
- A modern web browser

### 1. Clone or Copy the Project

Place the project folder inside your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\anime-journal\
```

### 2. Start XAMPP

1. Open the XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

### 3. Database Import

1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click **Import** in the top menu
3. Choose the `database.sql` file from the project root
4. Click **Go** to import

Alternatively, run via MySQL CLI:

```bash
mysql -u root -p < database.sql
```

### 4. Configure Database Connection

Open `includes/db.php` and update credentials if needed:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'anime_journal');
```

For free hosting (InfinityFree, 000WebHost), update these values with your hosting provider's database credentials.

### 5. Run the Application

Open your browser and visit:

```
http://localhost/anime-journal/
```

### Demo Accounts

The database includes sample users and 8 anime blog posts. You can log in with:

| Username | Password |
|----------|----------|
| sakura_writer | password |
| mochi_reviews | password |
| lantern_dreams | password |

Sample posts include reviews of Spirited Away, Your Name, A Silent Voice, One Piece, and more.

## Running Using XAMPP

1. Ensure Apache and MySQL are running in XAMPP
2. Navigate to `http://localhost/anime-journal/`
3. Register a new account
4. Log in and create your first blog post from the Dashboard

## Folder Structure

```
anime-journal/
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── script.js          # Form validation, delete confirmation, UI
├── includes/
│   ├── db.php             # Database connection
│   ├── auth.php           # Session and authorization helpers
│   ├── header.php         # Shared banner, navigation, page header
│   ├── footer.php         # Shared page footer
│   └── sidebar.php        # Right-column navigation widget
├── index.php              # Home page with all blog posts
├── register.php           # User registration
├── login.php              # User login
├── logout.php             # Session logout
├── dashboard.php          # User's own blog posts
├── create.php             # Create new blog
├── edit.php               # Edit own blog
├── delete.php             # Delete own blog
├── view.php               # View single blog post
├── database.sql           # Database schema
└── README.md              # This file
```

## Security

- Passwords hashed with `password_hash()` and verified with `password_verify()`
- All database queries use prepared statements
- Output escaped with `htmlspecialchars()`
- Blog ownership verified before edit and delete operations
- Protected pages require active PHP session

## Hosting Compatibility

This project works on free PHP hosting services such as **InfinityFree** and **000WebHost** without code changes. Only update the database credentials in `includes/db.php` to match your hosting environment.

## License

University assignment project. For educational use.
