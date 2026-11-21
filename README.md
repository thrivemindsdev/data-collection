# 🕉 श्री आनंदम धाम — Bhakt Management System

A full-featured PHP Laravel web application for **Shri Anandham Dhaam Ashram** to manage Bhakt (devotee) registrations.

---

## ✅ Features

| Feature | Details |
|---|---|
| **Bilingual** | English + Hindi language switcher on every page |
| **Bhakt Registration Form** | 15+ fields including personal, address, diksha, family |
| **Unique Bhakt ID** | Auto-generated `SAD-000001` format after approval |
| **Admin Login** | Secure staff login with session |
| **Admin Dashboard** | Stats, state-wise & branch-wise distribution, recent registrations |
| **All Bhakts List** | Search, filter by status/state/branch/diksha, pagination |
| **Bhakt Profile** | Full detail view + diksha badge + WhatsApp consent |
| **Approve / Reject / Correction** | Admin review workflow with remarks |
| **CSV Export** | Download all bhakt data as Excel-compatible CSV |
| **Audit Log** | Full history of admin actions on each bhakt |
| **Application Status Check** | Bhakts can check their status by mobile + reg number |

---

## 🚀 Getting Started

### Requirements
- PHP 8.2+
- Composer 2+

### Installation

```bash
git clone https://github.com/thrivemindsdev/data-collection.git
cd data-collection
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Then open **http://127.0.0.1:8000**

---

## 🔐 Default Login Credentials

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@ashram.in` | `Admin@1234` |
| **Staff** | `staff@ashram.in` | `Staff@1234` |

> ⚠️ **Change these passwords immediately in production!**

---

## 📋 Registration Form Fields

| Field | Hindi | Required |
|---|---|---|
| Full Name | पूरा नाम | ✅ |
| Mobile (10 digits) | मोबाइल नंबर | ✅ |
| Email | ईमेल | Optional |
| Gender | लिंग | Optional |
| Date of Birth | जन्मतिथि | Optional |
| City | शहर | Optional |
| District | जिला | Optional |
| State | राज्य (dropdown) | Optional |
| Address | पता | Optional |
| Ashram Branch | आश्रम शाखा | Optional |
| Diksha Taken? | दीक्षा ली है? | Optional |
| Diksha Date | दीक्षा तिथि | Conditional |
| Diksha Place | दीक्षा स्थान | Conditional |
| Diksha Guru | दीक्षा गुरु | Conditional |
| Family Members | परिवार के सदस्य | Optional |
| Seva | सेवा/स्वयंसेवक | Optional |
| WhatsApp Consent | WhatsApp सहमति | Optional |
| Consent | सहमति | ✅ Required |

---

## 🗂 Admin Dashboard

- **URL**: http://127.0.0.1:8000/admin
- **Stats**: Total, Approved, Pending, Rejected, Dikshit, WhatsApp
- **State & Branch Distribution** tables
- **Recent registrations** table
- **Full bhakt list** with search + filters
- **Per-bhakt**: approve, reject, send for correction with remarks
- **CSV Export**: http://127.0.0.1:8000/admin/bhakts/export

---

## 🛠 Project Structure

```
app/
  Http/Controllers/
    AuthController.php       ← Login/Logout
    BhaktController.php      ← Public registration + status
    AdminController.php      ← Admin dashboard, list, approve, export
    LanguageController.php   ← EN/HI switcher
  Http/Middleware/
    SetLocale.php            ← Applies session locale
  Models/
    Bhakt.php                ← Main model (SAD-XXXXXX ID generator)
    AuditLog.php             ← Audit trail model

database/migrations/
  *_create_bhakts_table.php
  *_create_audit_logs_table.php
database/seeders/
  DatabaseSeeder.php         ← Admin + Staff users
  BhaktSeeder.php            ← 5 sample bhakts

resources/views/
  layouts/app.blade.php      ← Main layout (navbar, lang switcher)
  auth/login.blade.php
  bhakt/register.blade.php   ← Registration form
  bhakt/success.blade.php    ← Submission success
  bhakt/status.blade.php     ← Status checker
  admin/dashboard.blade.php  ← Stats + charts
  admin/bhakts/index.blade.php
  admin/bhakts/show.blade.php

lang/
  en/messages.php  hi/messages.php
  en/validation.php  hi/validation.php
  en/admin.php  hi/admin.php
  en/auth.php  hi/auth.php

routes/web.php               ← All routes
```

---

## 🌐 Language Switching

- Click **EN** or **हिं** badge in the top-right of the navbar
- Language is stored in session and persists across pages
- Full translations for all labels, buttons, messages, and errors

---

## 🔄 Bhakt Workflow

```
Public fills form → status: "pending"
         ↓
Admin reviews at /admin/bhakts/{id}
         ↓
  ┌──────────────────────────┐
  │ Approve → SAD-XXXXXX ID  │
  │ Reject  → with reason    │
  │ Correction → returned    │
  └──────────────────────────┘
         ↓
Bhakt checks status at /status
```
