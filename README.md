# 📁 SITE 2: Dynamic Website – Expense Tracker

## 📌 Project Overview

The **Expense Tracker Web Application** is a dynamic web system that allows users to:

- Register and manage accounts  
- Log daily expenses  
- Categorize spending  
- Upload invoice images  
- View expense history  

All data is dynamically generated and stored in a MySQL database, enabling real-time interaction between users and the system.

---

## 🛠️ Tech Stack

- Frontend: HTML, CSS  
- Backend: PHP  
- Database: MySQL (via phpMyAdmin)  
- Web Server: Apache (XAMPP)  
- Environment: Windows (VirtualBox / VMware)

---

## 🧱 System Architecture

- **Frontend:** User interface (forms, dashboard, views)
- **Backend:** PHP logic for authentication and processing
- **Database:** Stores users, expenses, and invoices
- **Server:** Apache handles requests and responses

---

## 🚀 Deployment Guide

### Step 1: Prepare the Environment

1. Install VirtualBox or VMware Workstation  
2. Create a new Virtual Machine:
   - Name: `Windows-XAMPP-Server`
   - OS: Windows 10 / 11 (64-bit)
   - RAM: 4GB minimum
   - Storage: 40GB virtual disk  
3. Install Windows OS inside the VM  
4. Install VirtualBox Guest Additions (for performance)

---

### Step 2: Install XAMPP

1. Download XAMPP: https://www.apachefriends.org/  
2. Install with default components:
   - Apache
   - MySQL
   - PHP  
3. Install to: `C:\xampp`  
4. Start XAMPP Control Panel  
5. Start:
   - Apache
   - MySQL  
6. Verify by visiting: