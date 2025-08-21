# 🎟️ Ticketing System (Laravel + React.js)

A full-stack ticketing system built with **Laravel** (backend, REST API) and **React.js** (frontend).  
This project allows users to create, view, and manage support tickets, while admins/agents can assign, update, and resolve them.

---

## 🚀 Features

### User
- Register and log in
- Create new tickets with title, description, and category
- View ticket status (open, in progress, resolved)
- Add comments to tickets
- Receive updates in real-time (via Laravel Echo / WebSockets)

### Admin/Agent
- Manage all tickets
- Assign tickets to agents
- Update ticket status
- Add internal notes
- View analytics (number of open/resolved tickets, etc.)

---

## 🛠️ Tech Stack

**Backend (API):**
- [Laravel 11](https://laravel.com/)
- Sanctum / Passport (Authentication)
- Eloquent ORM
- MySQL / PostgreSQL

**Frontend (Client):**
- [React.js](https://react.dev/)
- React Router (navigation)
- Axios (API calls)
- Tailwind CSS / Bootstrap (UI)
- Context API / Redux (state management)


---

## ⚙️ Installation

### 1. Clone the Repository
```bash
git clone git@github.com:vinceaintreadin-21/ticketing-system.git
cd ticketing-system
```
### 2. Install Laravel dependencies
```bash
composer install
```
