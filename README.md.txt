# 📊 Dynamic Expense Tracker Website

A complete dynamic web application built with **HTML, CSS, PHP, MySQL** and runs on **XAMPP**. This system allows users to register, log in, track daily expenses, upload invoices/receipts, and view total expenses and history.

🔗 **GitHub Repository:** [https://github.com/BryllUmangay/dynamicsite](https://github.com/BryllUmangay/dynamicsite)

---

## ✨ Features
- ✅ **User Registration & Login** (Secure password hashing)
- ✅ **Daily Expense Logging** (Date, Description, Amount, Category)
- ✅ **Total Expense Calculation** (Shows total sum at top)
- ✅ **Expense History Table**
- ✅ **Invoice / Receipt Upload** (PDF, JPG, PNG, JPEG)
- ✅ **Invoice Upload History** (View/Open uploaded files)
- ✅ **Linked Expenses & Invoices**
- ✅ **Clean & Responsive Design**

---

## 🛠️ Technologies Used
- **Frontend:** HTML5, CSS3
- **Backend:** PHP
- **Database:** MySQL
- **Server:** XAMPP (Apache & MySQL)

---

## 🚀 How to Run Locally (XAMPP)

### Step 1: Download & Setup
1. Install **XAMPP** (https://www.apachefriends.org/)
2. Clone or download this project into:  
   `C:\xampp\htdocs\butch-expense-web`
3. Create a folder named `uploads` inside the project folder:  
   `C:\xampp\htdocs\butch-expense-web\uploads`

### Step 2: Database Setup
1. Open XAMPP Control Panel → Start **Apache** and **MySQL**
2. Go to: `http://localhost/phpmyadmin`
3. Create new database: `expense_tracker`
4. Run this SQL query in the **SQL** tab:

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    expense_date DATE NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    expense_id INT DEFAULT NULL,
    invoice_file VARCHAR(255) NOT NULL,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (expense_id) REFERENCES expenses(id) ON DELETE SET NULL
);