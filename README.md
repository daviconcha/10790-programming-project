<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1 align="center">🔗 LinkSnap</h1>
    <p align="center">
        A URL shortening and visitor analytics system for managing and monitoring link interactions.
      <img src="https://github.com/user-attachments/assets/e6ef414d-1d1a-4841-abb0-b19b8f1fc1c8" alt="LinkSnap Dashboard" width="600" />
    </p>
    <h2>✨ Features</h2>
    <ul>
        <li>Create shortened links quickly and easily.</li>
        <li>Track detailed statistics about link visitors, including:
            <ul>
                <li><strong>IP Address:</strong> Hover over to view full IP.</li>
                <li><strong>Language:</strong> Visitor's preferred language.</li>
                <li><strong>Browser Information:</strong> User agent details.</li>
                <li><strong>Referrer:</strong> Source of the visitor.</li>
                <li><strong>Country:</strong> Visitor's geolocation.</li>
                <li><strong>Date:</strong> When the visit occurred.</li>
            </ul>
        </li>
        <li>Copy links to clipboard with a single click.</li>
        <li>Export link visit data to <strong>XLS</strong> for offline analysis.</li>      
    <img src="https://github.com/user-attachments/assets/51138a9a-1f61-45cd-9664-110c23ee8f86" alt="LinkSnap Dashboard" width="600" />
    </ul>
    <h2>📂 Folder Structure</h2>
    <pre>
    /db         - Database configuration files.
    /assets     - CSS, JavaScript, and image resources.
    /linksnap   - Core application logic.
    </pre>
    <h2>⚙️ How to Use</h2>
    <ol>
        <li>Clone the repository:
            <pre><code>git clone https://github.com/daviconcha/10790-programming-project.git</code></pre>
        </li>
        <li>Set up the database using the provided SQL script in the <code>/db</code> folder.</li>
        <li>Configure your environment in <code>db/config.php</code> (adjust database credentials).</li>
        <li>Start the application using a local server (e.g., XAMPP, WAMP, or LAMP).</li>
        <li>Access the app in your browser: <a href="http://localhost:81/linksnap/">http://localhost:81/linksnap/</a>.</li>
    </ol>
  <h2>📥 Installation</h2>
<ol>
    <li><strong>Set up XAMPP:</strong>
        <ul>
            <li>Download and install <a href="https://www.apachefriends.org/index.html">XAMPP</a>.</li>
            <li>Start the Apache and MySQL modules from the XAMPP Control Panel.</li>
        </ul>
    </li>   
<li><strong>Create the Database:</strong>  
    <ul>
        <li>Open <a href="http://localhost/phpmyadmin">phpMyAdmin</a>.</li>
        <li>Create a new database named <code>lst_db</code>.</li>
        <li>Go to the "SQL" tab, and run the following SQL script to create the necessary tables:</li>
        <pre><code>
CREATE TABLE `links` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`original_url` text NOT NULL,
`short_code` varchar(10) NOT NULL,
`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
PRIMARY KEY (`id`),
UNIQUE KEY `short_code` (`short_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        </code></pre>
        <pre><code>
CREATE TABLE `link_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `referer` text DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL,
  `visited_at` datetime DEFAULT current_timestamp(),
  `country` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  CONSTRAINT `link_visits_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        </code></pre>
        <li>Execute the script to create the <code>links</code> and <code>link_visits</code> tables.</li>
    </ul>
    <img src="https://github.com/user-attachments/assets/60eaf5a6-1eb7-4dc8-b5f2-9abceef1fd2d" alt="LinkSnap DB" width="600" />
</li>
    <li><strong>Configure the Database Connection:</strong>
        <ul>
            <li>Open the file <code>db/config.php</code> in a text editor.</li>
            <li>Update the database credentials as per your XAMPP setup:</li>
            <pre><code>
$dbHost = 'localhost';
$dbUser = 'root'; // Default user in XAMPP
$dbPass = '';     // Default password in XAMPP is empty
$dbName = 'lst_db';
            </code></pre>
        </ul>
    </li>
    <li><strong>Host the Project:</strong>
        <ul>
            <li>Copy the project folder to the <code>htdocs</code> directory inside your XAMPP installation.</li>
            <li>Ensure the project path matches your intended URL (e.g., <code>http://localhost:81/linksnap</code>).</li>
        </ul>
    </li>
    <li><strong>Access the Application:</strong>
        <ul>
            <li>Open your browser and navigate to <a href="http://localhost:81/linksnap/">http://localhost:81/linksnap/</a>.</li>
            <li>Start creating and managing your shortened links!</li>
        </ul>
    </li>
</ol>    
<li><strong>Python Backoffice:</strong> This backoffice is built using Python to manage the database and interact with the data. It connects to the MySQL database <code>lst_db</code> using the <code>mysql.connector</code> library. The backoffice allows the administrator to:
    <ul>
        <li><strong>View data:</strong> View data from the <code>links</code> and <code>link_visits</code> tables.</li>
        <li><strong>Edit data:</strong> Update specific fields in the database based on conditions (e.g., update a URL in the <code>links</code> table).</li>
        <li><strong>Insert data:</strong> Insert new records into the <code>links</code> table.</li>
        <li><strong>Delete data:</strong> Delete records from the <code>links</code> table based on a specified condition.</li>
    </ul>    
    The backoffice interface includes a simple text-based menu system, implemented in Python, using the <code>colorama</code> library to add color to the terminal output for better readability. Below is the Python script that powers the backoffice.
    <img src="https://github.com/user-attachments/assets/d55a5db6-f9ab-4e39-a265-e7c3279fbf6b" alt="LinkSnap DB" width="600" />
</li>

    
<h2>🚀 Tech Stack</h2>
<ul>
    <li><strong>Backend:</strong> PHP (PDO for database interaction), Python (for admin backoffice with <code>admin/app.py</code>).</li>
    <li><strong>Frontend:</strong> HTML, CSS, JavaScript.</li>
    <li><strong>Database:</strong> MySQL.</li>
</ul>
    <h2>📊 Analytics</h2>
    <p>LinkSnap provides detailed analytics for each shortened link, including visitor insights and engagement statistics. Visit the "Link Details" page for each link to access the data.</p>
    <h2>🛠️ Development Notes</h2>
    <ul>
        <li>Ensure the <code>localhost:81</code> configuration is properly set in the application URLs.</li>
        <li>Use <code>create_link.php</code> for generating new shortened links.</li>
    </ul>
    <h2>📄 License</h2>
    <p>This project is open-source and available under the MIT License. Feel free to fork and contribute!</p>
    <h2>🤝 Contributing</h2>
    <p>Contributions are welcome! Submit a pull request or report issues in the <a href="https://github.com/daviconcha/10790-programming-project/issues">Issues</a> section.</p>
    <h2>🌟 Acknowledgments</h2>
    <p>Thanks to all contributors and testers for making this project possible!</p>
</body>
</html>
