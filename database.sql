-- database.sql
-- Script SQL para crear la base de datos, usuario y tabla TEST_CLIENTS

-- Crear la base de datos (si ya existe, no da error)
CREATE DATABASE IF NOT EXISTS pruebas_practicas
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- Crear el usuario (si ya existe, esta parte puede fallar,
-- pero normalmente se ejecuta solo la primera vez)
CREATE USER IF NOT EXISTS 'pruebajr'@'localhost'
  IDENTIFIED BY 'pruebajr';

-- Dar permisos al usuario sobre la base de datos
GRANT ALL PRIVILEGES ON pruebas_practicas.* TO 'pruebajr'@'localhost';
FLUSH PRIVILEGES;

-- Seleccionar la base de datos
USE pruebas_practicas;

-- Eliminar la tabla si existe (para poder recrearla sin error de "ya existe")
DROP TABLE IF EXISTS TEST_CLIENTS;

-- Crear la tabla TEST_CLIENTS
CREATE TABLE TEST_CLIENTS (
    ID INT AUTO_INCREMENT PRIMARY KEY,  -- Clave primaria, autoincremental
    NAME VARCHAR(255) NOT NULL,         -- NOT NULL no permite insertar NULL
    ADDRESS VARCHAR(255) NOT NULL,
    DESCRIPTION TEXT NOT NULL,
    TELF VARCHAR(255) NOT NULL,
    TYPE CHAR(1) NOT NULL               -- N o P, se podria hacer con TYPE ENUM para aceptar unicamente N o P, pero me ajustaré al guión
);
