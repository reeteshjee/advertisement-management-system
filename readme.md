# Advertisement Management System

A PHP-based tool to create, manage, and track advertisements with real-time analytics for website integration.

## Demo

A live version of this system is deployed at: [sponsored.youthsforum.com](https://sponsored.youthsforum.com)

## Features

* Social login integration with Facebook, Twitter, and Gmail accounts
* Create and manage advertisements with custom names, URLs, and display periods
* Upload separate banner images for desktop and mobile devices
* One-click code snippet generation for easy website integration
* Real-time tracking of impressions and clicks
* Central control panel to update banners and URLs from one location
* User access control with regular and admin privileges

## Installation

1. Clone the repository to your web server directory:

```
git clone https://github.com/reeteshjee/advertisement-management-system.git
```

2. Create an uploads directory and ensure it's writable by the web server:

```
mkdir uploads
chmod 755 uploads
```

3. Configure your database connection by updating the `db.php` file:

```php
<?php
@session_start();
include_once('functions.php');
define('BASE_URL','https://yourdomain.com/');


// MySQL database configuration
define('DB_HOST', 'HOST');     // Your database host
define('DB_NAME', 'NAME'); // Your database name
define('DB_USER', 'USER'); // Your database username
define('DB_PASS', 'PASSWORD'); // Your database password

define('GOOGLE_CLIENT_ID','YOUR_GOOGLE_CLIENT_ID');
define('GOOGLE_CLIENT_SECRET','YOUR_GOOGLE_CLIENT_SECRET');
define('GOOGLE_CALLBACK_URL', 'YOUR_GOOGLE_CALLBACK_URL');

define('TWITTER_API_KEY','YOUR_TWITTER_API_KEY');
define('TWITTER_SECRET_KEY','YOUR_TWITTER_SECRET_KEY');
define('TWITTER_BEARER_TOKEN','YOUR_TWITTER_BEARER_TOKEN');
define('TWITTER_CALLBACK_URL','YOUR_TWITTER_CALLBACK_URL');

define('FACEBOOK_APP_ID','YOUR_FACEBOOK_APP_ID');
define('FACEBOOK_APP_SECRET','YOUR_FACEBOOK_APP_SECRET');
define('FACEBOOK_CALLBACK_URL','YOUR_FACEBOOK_CALLBACK_URL');


try {
    // Create MySQL connection
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        )
    );

    // Create ads table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS ads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        desktop_image VARCHAR(255) NOT NULL,
        mobile_image VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL,
        display_from DATETIME NOT NULL,
        display_to DATETIME NOT NULL,
        impressions INT DEFAULT 0,
        clicks INT DEFAULT 0,
        status TINYINT DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
```
4. Access the system through your web browser (e.g., `https://yourdomain.com/advertisement-management-system/`)

## Deployment

This project is set up with continuous deployment:
* Changes pushed to the main branch are automatically deployed to [sponsored.youthsforum.com](https://sponsored.youthsforum.com)
* No manual deployment steps are required after pushing to the main branch

## Usage

### Creating an Advertisement

1. Log in using your social media account
2. Click "Add New Ad" in the dashboard
3. Fill out the form with the following information:
   * **Ad Name**: A name for your advertisement
   * **Ad Link**: The URL where users will be directed when clicking the ad
   * **Desktop Image**: Banner image optimized for desktop viewing
   * **Mobile Image**: Banner image optimized for mobile viewing
   * **Display From/To**: The date range when the ad should be displayed
4. Click "Save Advertisement"

### Embedding Ads in Your Website

1. From the dashboard, find the ad you want to embed
2. Click the copy icon to copy the embed code
3. Paste the code into your website where you want the ad to appear
4. The system will automatically handle displaying the correct banner based on device type

### Managing Advertisements

* Toggle the status button to enable or disable ads
* Click the edit icon to modify existing ads
* Click the trash icon to delete ads
* View impression and click statistics directly in the dashboard

## System Requirements

* PHP 7.4 or higher
* MySQL 5.7 or higher
* Web server (Apache, Nginx, etc.)
* Modern web browser

## Security Considerations

* Ensure your uploads directory is properly secured
* Implement proper authentication for the admin area
* Keep your database credentials secure
* Use HTTPS for all connections
* Validate all user inputs

## Troubleshooting

If you encounter issues with the system:

1. Check that the uploads directory is writable by the web server
2. Verify database connection settings and facebook/twitter/google credentials in `db.php`
3. Ensure all required PHP extensions are enabled
4. Check the web server error logs for any PHP errors

