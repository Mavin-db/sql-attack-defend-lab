# Hardening Plan – SQL Attack & Defend Lab (Phase 2)

This document outlines the improvements required to secure the vulnerable login system
implemented in **Phase 1** of the SQL Attack & Defend Lab.

The goal is to demonstrate defensive skills by rewriting the vulnerable code using
industry best practices.

---

## 1. Objectives

- Prevent SQL Injection 
- Improve authentication security 
- Reduce database privileges 
- Remove information leakage 
- Provide a secure comparison to the vulnerable version 

Both the **vulnerable** and **secure** versions will remain in the repository for
educational comparison.

---

# 2. Planned Security Improvements

## 2.1 Replace vulnerable SQL with **Prepared Statements**

Current vulnerable code:

```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);
```

Secure version:

```php
$stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
```

Benefits:

- User input cannot break SQL structure 
- Prevents `' OR '1'='1` style injection 
- Recommended by OWASP

---

## 2.2 Implement Proper Password Hashing

Replace plain text storage with:

```php
password_hash()
password_verify()
```

Benefits:

- Passwords are one-way hashed 
- Prevents credential exposure 
- Industry standard 

---

## 2.3 Restrict Database User Privileges (Least Privilege)

Current lab user:

```sql
GRANT ALL PRIVILEGES ON shop_lab.* TO 'shop_vuln'@'localhost';
```

Planned secure user:

```sql
GRANT SELECT, INSERT, UPDATE ON shop_lab.users TO 'shop_secure'@'localhost';
```

Benefits:

- Reduces blast radius if compromised
- Protects schema 
- Prevents DROP/ALTER misuse 

---

## 2.4 Remove Debug Output

Remove these from the secure version:

- Raw SQL queries 
- Plaintext username/password debug 
- MariaDB error messages 

Replace with:

- Generic “Invalid credentials” message 
- Error logging to a secure file 

---

## 2.5 Error Handling

Add safe error statements:

```php
if (!$stmt) {
    // log error internally
    die("An error occurred. Please try again later.");
}
```

Never expose internal SQL errors to users.

---

## 2.6 Create `login_secure.php`

The secure version will live alongside the vulnerable one:

```
login.php           → vulnerable version
login_secure.php    → secure version (prepared statements)
```

This allows recruiters to compare the two directly.

---

## 3. Deliverables for Phase 2

- `login_secure.php` 
- `docs/03_hardened_login_comparison.md` 
- Updated SQL script for secure DB user 
- Updated README.md to reflect secure version work 

---

## 4. Status

- Vulnerable version complete 
- Planning stage complete 
- Secure version: **pending implementation** 

---

_End of Hardening Plan (Phase 2)_
