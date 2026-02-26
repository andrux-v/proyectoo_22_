-- Script para agregar campos de autenticación a centro_formacion
-- Ejecutar este script en la base de datos gestion_academica

-- Agregar campos de correo y password a centro_formacion
ALTER TABLE `centro_formacion` 
ADD COLUMN `cent_correo` VARCHAR(100) NULL AFTER `cent_nombre`,
ADD COLUMN `cent_password` VARCHAR(100) NULL AFTER `cent_correo`;

-- Agregar índice único para el correo
ALTER TABLE `centro_formacion` 
ADD UNIQUE INDEX `cent_correo_UNIQUE` (`cent_correo` ASC);

-- Agregar índice único para el correo de coordinacion (si no existe)
ALTER TABLE `coordinacion` 
ADD UNIQUE INDEX `coord_correo_UNIQUE` (`coord_correo` ASC);

-- Agregar índice único para el correo de instructor (si no existe)
ALTER TABLE `instructor` 
ADD UNIQUE INDEX `inst_correo_UNIQUE` (`inst_correo` ASC);
