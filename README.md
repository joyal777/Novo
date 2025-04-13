<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<h2 align="center">Novo 🎬</h2>

<p align="center">A movie listing & favorite system built with Laravel, Alpine.js, TailwindCSS, Bootstrap, and OMDB API.</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-Breeze-red" alt="Breeze"></a>
  <a href="#"><img src="https://img.shields.io/badge/OMDb%20API-integrated-blue" alt="OMDb API"></a>
  <a href="#"><img src="https://img.shields.io/badge/MySQL-Backend-green" alt="MySQL"></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License: MIT"></a>
</p>

---

## 🚀 Project Overview

**Novo** is a modern web app to explore movies using the OMDB API, built with Laravel and styled with TailwindCSS + Bootstrap. It features full authentication, favorite movie management, and a sleek dashboard interface.

---

## 🛠️ Built With

- **Laravel** - PHP framework for backend & routing
- **Laravel Breeze** - For authentication scaffolding
- **TailwindCSS** - For modern utility-first styling
- **Bootstrap** - For responsive components
- **Alpine.js** - For reactive UI functionality
- **MySQL** - Database management
- **OMDb API** - Fetching real-time movie data

---

## ✨ Features

- 🔐 Authentication (Laravel Breeze)
- 🔑 API Authentication support
- 🔍 Movie search using OMDb API
- ❤️ Add/remove favorites
- 📋 View favorites on Dashboard
- ✏️ Profile Edit functionality
- ⚙️ Basic CRUD operations
- 🔄 Fetch on search, store to DB
- 🖼️ Beautiful UI with Alpine & Tailwind

---


## 📸 Screenshots

<!-- Upload screenshots to GitHub or Imgur and replace the links below -->
![Dashboard](https://github.com/joyal777/Novo/blob/main/images/main.png?raw=true)
![Login](https://github.com/joyal777/Novo/blob/main/images/login.png?raw=true)
![Register](https://github.com/joyal777/Novo/blob/main/images/register.png?raw=true)
![API First](https://github.com/joyal777/Novo/blob/main/images/api-first.png?raw=true)
![Dashboard](https://github.com/joyal777/Novo/blob/main/images/dashboard.png?raw=true)
![Show Favorites](https://github.com/joyal777/Novo/blob/main/images/dashboard-showfav.png?raw=true)
![Favorites](https://github.com/joyal777/Novo/blob/main/images/favorites.png?raw=true)
![Profile](https://github.com/joyal777/Novo/blob/main/images/profile.png?raw=true)
![API Update](https://github.com/joyal777/Novo/blob/main/images/api-upd.png?raw=true)


---
## 🔧 Setup & Installation
requirements : 
    xampp supports php 8+
    composer 2

    extra setup : inside xampp/php/php.ini enable some extensions if not (openssl,pdo..ect)
Clone the repo:
   ```bash
   git clone https://github.com/your-username/novo.git
   cd novo
or
    download zip and extract it in xampp inside htdocs folder 
   

Install dependencies
    composer install
    
Install frontend dependencies
    npm install
    npm run dev
    
Copy .env file
    cp .env.example .env (optional)
    
Generate app key
    php artisan key:generate
    
Set up the database:
You may need to manually create the database if it doesn't exist:
    echo "Please create the 'novo' MySQL database manually or ensure it exists. get the .sql from project folder"
    echo "Update the .env file with the correct DB credentials (DB_USERNAME, DB_PASSWORD)"

