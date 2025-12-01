# SQL Injection Vulnerability – Login Bypass (Phase 1)

This document provides a full breakdown of the SQL injection (SQLi) vulnerability in the
deliberately insecure PHP/MySQL login system used in the **SQL Attack & Defend Lab**.

This write-up follows a simplified penetration testing structure.

---

## 1. Overview

The application contains a login form located at:

```
/shop-lab/index.php
```

User input is sent via POST to:

```
/shop-lab/login.php
```

The login handler contains **unsafe SQL string concatenation**, making it vulnerable
to SQL Injection.

This lab runs on a private Ubuntu “hack lab” environment and is **never exposed** to
the public internet.

---

## 2. Vulnerable Code

The critical insecure code in `login.php`:

```php
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);
```

### Why this is vulnerable:

- User-controlled data (`$username`, `$password`) is **directly inserted** into the SQL query 
- No prepared statements 
- No escaping or sanitisation 
- The query structure can be modified by injecting SQL syntax (`'`, `--`, `OR`, etc.)

This allows an attacker to **change the meaning** of the SQL query.

---

## 3. Impact (What an Attacker Can Do)

If deployed in a real-world environment, this vulnerability would allow:

### -[x] Authentication bypass  
Log in as any user — **including admin** — without knowing the password.

### -[x] Data exposure  
Extract or enumerate other users depending on additional endpoints.

### -[x] Data modification  
Potential to change records if other queries are similarly vulnerable.

### -[x] Full database compromise  
If the DB user is over-privileged (in this lab, it intentionally is).

---

## 4. Proof of Concept (PoC)

### **Attack Goal:**  
Log in as **admin** without knowing the admin password.

### Step-by-step:

1. Navigate to:
   ```
   http://<your-hacklab-ip>/shop-lab/index.php
   ```

2. Enter the following payload:

**Username field:**
```
admin' -- 
```

**Password field:** 
(anything)

### Result:

- The login succeeds 
- The page prints:
  - DEBUG username
  - DEBUG password
  - DEBUG SQL query 
  - Welcome message as **admin**

---

## 5. Why the Payload Works

The constructed SQL becomes:

```sql
SELECT * FROM users WHERE username = 'admin' -- ' AND password = 'abc';
```

Breaking it down:

- `'admin'` closes the intended string
- `--` starts a **SQL comment**
- Everything after `--` is discarded, including the password check

The database effectively executes:

```sql
SELECT * FROM users WHERE username = 'admin';
```

So authentication is bypassed completely.

---

## 6. Screenshots

Stored in:

```
docs/screenshots/
```

Recommended viewing order:

1. `01_login_form.png` 
2. `02_normal_login_success.png` 
3. `03_sql-injection.png` 
4. `04_sqlinjection_bypass.png` 
5. `05_database_table.png` 
6. `06_project_structure.png` 

---

## 7. Root Cause Summary

- Use of string concatenation to build SQL queries 
- Lack of prepared statements 
- No input validation 
- Excessive database permissions 
- Debug messages leaking SQL queries 

---

## 8. Remediation Summary (Detailed in 02_hardening_plan.md)

- Replace raw SQL with **prepared statements** 
- Hash passwords using `password_hash()` 
- Remove debug SQL output 
- Restrict database user privileges 
- Improve error handling 

---

## 9. Status

- Vulnerable login confirmed 
- SQL Injection PoC validated 
- Screenshots captured 
- Hardened version to be created (`login_secure.php`) 

---

_End of Report – Phase 1_
