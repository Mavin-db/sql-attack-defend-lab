# SQL Attack & Defend Lab – PHP/MySQL

This repository contains a deliberately vulnerable PHP/MySQL web application used to
demonstrate **SQL injection (SQLi)** attacks and then implement secure remediation.
The lab runs on a private Ubuntu "hack lab" server and is **never** exposed to the
public internet.

This project is part of a larger cybersecurity portfolio demonstrating both offensive
and defensive skills: how vulnerabilities are exploited, and how they are fixed.

---

## Project Overview

This lab simulates a simple login page for a fictional “shop” website.

The initial version is **intentionally vulnerable**:

- User input is directly inserted into SQL queries
- Passwords are stored in plaintext (educational only)
- The DB user is intentionally over-privileged
- SQL debug output is shown on the page

This allows you to:

- Perform a classic SQL Injection attack 
- Log in as admin without the correct password 
- Analyse vulnerable code 
- Write a pentest-style vulnerability report 
- Build a secure (hardened) version using industry best practices 

---

## 🔹 Features / Learning Outcomes

### ✔ Vulnerable Version
- Raw SQL string concatenation 
- SQL comment-based login bypass (`admin' -- `) 
- Debug SQL output 
- Plain-text passwords 
- Over-privileged DB user 

### ✔ Documentation
Located in the `docs/` directory:

- Full vulnerability write-up 
- Screenshots 
- Hardening plan 

### ✔ Planned Secure Version (Phase 2)
- Prepared statements (parameterised queries) 
- Sanitised user input 
- Proper password hashing 
- Least-privilege database user 
- Error-handling improvements 

---

## Repository Structure

```text
.
├── README.md
├── .gitignore
├── index.php                    # Vulnerable login form
├── login.php                    # Vulnerable login handler (SQLi)
├── sql
│   └── 01_create_shop_lab.sql   # Database & user creation script
└── docs
    ├── 01_sql_injection_login.md  # Full SQLi write-up
    ├── 02_hardening_plan.md       # Secure version planning
    └── screenshots/               # Screenshots referenced in docs
```

---

## Screenshots

```
docs/screenshots/
```

Recommended filenames:

- `01_login_form.png`
- `02_normal_login_success.png`
- `03_sqlinjection_payload.png`
- `04_sqlinjection_bypass.png`
- `05_database_table.png`
- `06_project_structure.png`

They are referenced inside:

`docs/01_sql_injection_login.md`

---

# 🔹 How to Run This Lab (Local Only)

> **IMPORTANT**
> This application is intentionally vulnerable.
> **Never expose it to the internet.** 
> Use only inside a private lab environment.

---

## **1. Install required packages (Ubuntu / Debian)**

```bash
sudo apt update
sudo apt install apache2 php php-mysql mariadb-server mariadb-client -y
```

---

## **2. Clone this repo into your web root**

```bash
cd /var/www/html
sudo git clone https://github.com/Mavin-db/sql-attack-defend-lab.git shop-lab
sudo chown -R $USER:$USER shop-lab
cd shop-lab
```

---

## **3. Create the database**

Run the SQL setup script:

```bash
mariadb -u root -p < sql/01_create_shop_lab.sql
```

This creates:

- `shop_lab` database 
- `users` table 
- Demo users:
  - `admin` / `admin123`
  - `brad` / `mypassword`
- Vulnerable DB user: 
  - Username: `shop_vuln` 
  - Password: `ShopVulnPass!` 

---

## **4. Access the web application**

Open a browser (same network):

```
http://<your-hacklab-ip>/shop-lab/index.php
```

Example:

```
http://192.168.1.55/shop-lab/index.php
```

You should see the login form.

---

# Demonstrating the SQL Injection Vulnerability

## Normal login (intended behaviour)

Use:

- Username: `admin`
- Password: `admin123`

Expected:

- Debug output 
- Successful login as admin 

---

## SQL Injection Login Bypass (comment-based attack)

Use:

```
Username: admin' -- 
Password: anything
```

This results in:

```sql
SELECT * FROM users
WHERE username = 'admin' -- ' AND password = 'anything';
```

The `--` starts a SQL comment, removing the password check.

Result: 
**Login succeeds as admin without knowing the real password.**

Full explanation + screenshots: 
`docs/01_sql_injection_login.md`

---

# Documentation

- `docs/01_sql_injection_login.md` 
  Full attack write-up, PoC, screenshots, impact analysis

- `docs/02_hardening_plan.md` 
  How the secure version will be implemented

---

# Roadmap

- ✔ Vulnerable login created 
- ✔ SQL injection PoC documented 
- ✔ Repository structure completed 
- ☐ Build secure login (`login_secure.php`) 
- ☐ Add search-based SQLi example 
- ☐ Add GET parameter SQLi (`view.php?id=1`) 
- ☐ Add logging to track SQLi attempts 
- ☐ Write comparison doc: vulnerable vs hardened code 

---

# Legal & Ethical Notice

This repository is for:

- personal education 
- cybersecurity training 
- job portfolio demonstration 
- interview discussion 

Use these techniques **only** on systems you own or have written permission to test.

---

# Author

**Bradley Mavin** 
Brisbane, Australia 
GitHub: https://github.com/Mavin-db
