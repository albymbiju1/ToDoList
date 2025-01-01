# **Task Manager Application**

This project is a PHP-based Task Manager application that allows users to register, log in, and manage tasks effectively. Users can create, update, and delete tasks, and the application ensures secure authentication using hashed passwords.

---

## **Features**

- **User Registration**: Users can register with their name, email, and password.
- **User Login**: Secure login functionality for registered users.
- **Task Management**:
  - Add new tasks with descriptions.
  - Update the status of tasks (e.g., pending, completed).
  - Delete tasks as needed.
- **Secure Password Handling**: Passwords are hashed using PHP's `password_hash` function.
- **Responsive Design**: A simple and user-friendly interface.

---

## **Getting Started**

Follow these instructions to set up and run the project.

### **Prerequisites**

- A web server (e.g., Apache, Nginx).
- PHP (7.4 or later recommended).
- MySQL/MariaDB database.
- SSH client (if hosted on a cloud platform like AWS).

---

### **Installation**

1. **Clone or Download the Project**
   - Clone the repository or download the project files:
     ```bash
     git clone <repository_url>
     cd <project_directory>
     ```

2. **Set Up the Database**
   - Create the database and tables:
     ```sql
     CREATE DATABASE IF NOT EXISTS todolist;

     USE todolist;

     CREATE TABLE IF NOT EXISTS users (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(255) NOT NULL,
         email VARCHAR(255) NOT NULL UNIQUE,
         password VARCHAR(255) NOT NULL,
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     );

     CREATE TABLE IF NOT EXISTS tasks (
         id INT AUTO_INCREMENT PRIMARY KEY,
         user_id INT NOT NULL,
         task_name VARCHAR(255) NOT NULL,
         description TEXT NOT NULL,
         status ENUM('pending', 'completed') DEFAULT 'pending',
         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
     );
     ```

3. **Deploy Files to Server**
   - Place the project files in the web server's root directory (e.g., `/var/www/html`).

4. **Configure the Default Page**
   - Set `register.php` as the default page:
     - **Apache**: Add or update `.htaccess` with:
       ```apache
       DirectoryIndex register.php
       ```
     - **Nginx**: Modify `default.conf`:
       ```nginx
       index register.php;
       ```

5. **Set Permissions**
   - Ensure the project files have the correct permissions:
     ```bash
     chmod -R 755 <project_directory>
     ```

---

### **Usage**

1. Access the application in your browser:

2. **Register** a new user account on the default page.

3. Log in with the registered credentials.

4. Manage tasks:
- Add a new task with a name and description.
- Update the status of existing tasks.
- Delete tasks when no longer needed.

5. Log out when done.

---

### **Project Structure**

```plaintext
/project_directory
│
├── register.php      # Handles user registration
├── login.php         # Handles user login
├── task_manager.php  # Main task management page
├── database.sql      # SQL script to create database and tables
└── README.md         # Project documentation
