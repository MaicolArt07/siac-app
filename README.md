<!-- ============================= -->
<!--           HEADER              -->
<!-- ============================= -->

<h1 align="center">🚀 SIAC-APP</h1>

<p align="center">
  <strong>Sistema Integral de Administración y Control</strong><br>
  Plataforma web desarrollada con Laravel para la gestión administrativa,
  control de procesos y generación de reportes.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/Laravel-10+-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/Livewire-3.x-purple?style=for-the-badge" />
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge" />
</p>

---

## 📌 Descripción del Proyecto

**SIAC-APP** es una aplicación web desarrollada para optimizar la administración interna de una organización, permitiendo:

- Gestión estructurada de registros
- Control administrativo centralizado
- Automatización de procesos
- Generación dinámica de reportes
- Interfaz moderna con componentes dinámicos (Livewire)

El sistema está construido bajo arquitectura MVC utilizando el framework Laravel.

---

# 🏗️ Arquitectura del Sistema

SIAC-APP
│
├── app/
│ ├── Http/
│ ├── Livewire/
│ ├── Models/
│
├── database/
│ ├── migrations/
│ ├── seeders/
│
├── resources/
│ ├── views/
│
├── routes/
│
└── public/


### Patrón utilizado:
- MVC (Model - View - Controller)
- Componentes dinámicos con Livewire
- ORM Eloquent
- Migraciones versionadas

---

# 🧩 Módulos Principales

✔ Gestión de Usuarios  
✔ Administración de Registros  
✔ Notas de Recepción  
✔ Generación de Reportes  
✔ Panel Administrativo  
✔ Control de Estados y Procesos  
✔ Integración con IA (si aplica en reportes)  

---

# ⚙️ Requisitos del Sistema

Antes de instalar el proyecto necesitas:

- PHP >= 8.1
- Composer
- Node.js >= 18
- MySQL / MariaDB
- Git
- Servidor local (XAMPP, Laragon, etc.)

---

# 🚀 Instalación Paso a Paso

## 1️⃣ Clonar repositorio

```bash
git clone https://github.com/MaicolArt07/siac-app.git
cd siac-app
