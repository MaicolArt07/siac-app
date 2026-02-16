SIAC App – Sistema de Gestión de Condominios

Sistema web desarrollado para la empresa SIAC (Servicios Integrales en Administración de Condominios), orientado a la automatización de procesos administrativos, gestión de copropietarios, control de pagos y manejo financiero de condominios.

📌 Descripción del Proyecto

SIAC App es una aplicación web que permite administrar de forma eficiente la información relacionada con:

Copropietarios

Pagos y deudas

Ingresos y egresos

Usuarios del sistema

Gestión administrativa general

El sistema fue desarrollado bajo la metodología Proceso Unificado de Desarrollo de Software (PUDS/RUP), utilizando modelado UML y arquitectura cliente-servidor.

🎯 Objetivo General

Desarrollar un sistema informático web que permita optimizar la gestión administrativa de condominios, proporcionando información clara, organizada y en tiempo real.

🧩 Módulos del Sistema

El sistema está compuesto por los siguientes módulos principales:

1️⃣ Gestión Persona

Registro de personas relacionadas con el condominio

Edición y actualización de datos

Eliminación de registros

2️⃣ Gestión Copropietario

Administración de información de propietarios

Asociación con departamentos o unidades

3️⃣ Gestión de Pago

Registro de pagos

Control de deudas

Seguimiento de expensas

Multas y penalizaciones

4️⃣ Gestión Cuenta Nominal

Registro de ingresos

Registro de egresos

Cálculo de totales acumulados por período

5️⃣ Gestión Usuario

Creación de cuentas de acceso

Asignación de roles

Control de permisos

🏗 Arquitectura

El sistema utiliza una arquitectura:

Cliente – Servidor

Aplicación Web

Base de datos relacional

Comunicación mediante HTTP

🛠 Tecnologías Utilizadas

PHP (Backend)

MySQL (Base de datos)

HTML5

CSS / Bootstrap

JavaScript

XAMPP (Servidor local)

UML (Modelado)

Git & GitHub (Control de versiones)

📊 Metodología de Desarrollo

Se utilizó el Proceso Unificado (RUP/PUDS) con las siguientes fases:

Inicio

Levantamiento de requerimientos

Identificación de casos de uso

Elaboración

Diseño de arquitectura

Modelado UML

Prototipo

Construcción

Desarrollo de módulos

Implementación de base de datos

Pruebas parciales

Transición

Pruebas finales

Corrección de errores

Implementación en entorno real

📋 Requisitos del Sistema
Requisitos de Software

PHP >= 7.x

MySQL >= 5.7

Apache

XAMPP o servidor equivalente

Navegador web moderno

⚙️ Instalación del Proyecto (Entorno Local)
1️⃣ Clonar el repositorio
git clone https://github.com/MaicolArt07/siac-app.git

2️⃣ Mover el proyecto a la carpeta del servidor

Copiar el proyecto dentro de:

xampp/htdocs/

3️⃣ Crear la Base de Datos

Abrir phpMyAdmin

Crear una nueva base de datos (ejemplo: siac_db)

Importar el archivo .sql del proyecto

4️⃣ Configurar conexión a base de datos

Editar el archivo de configuración (ejemplo: config.php) y colocar:

$host = "localhost";
$user = "root";
$password = "";
$database = "siac_db";

5️⃣ Ejecutar el sistema

Abrir en el navegador:

http://localhost/siac-app

🔐 Acceso al Sistema

El sistema cuenta con autenticación por usuario y contraseña.

Ejemplo (si aplica):

Usuario: admin
Contraseña: admin123


(Modificar según configuración real del proyecto)

🧪 Pruebas del Sistema

Se realizaron pruebas de:

Casos de uso

Gestión de persona

Gestión de pago

Validación de formularios

Pruebas de integración de módulos

📁 Estructura General del Proyecto
siac-app/
│
├── assets/
├── config/
├── controllers/
├── models/
├── views/
├── database/
└── index.php


(La estructura puede variar según implementación real.)

📈 Beneficios del Sistema

Automatización de procesos administrativos

Control financiero más preciso

Información en tiempo real

Adaptable a otros condominios

Escalable

👨‍💻 Autor

Proyecto desarrollado como Trabajo Final de Grado (TFG).

📜 Licencia

Este proyecto es de uso académico.
Puede adaptarse y mejorarse según necesidades de la empresa.
