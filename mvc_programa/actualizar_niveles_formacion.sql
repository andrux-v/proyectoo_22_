-- Script para actualizar los niveles de formación del SENA
-- Estos son valores fijos que no deben ser modificados por los usuarios

-- Limpiar la tabla (opcional, comentar si ya hay datos importantes)
-- TRUNCATE TABLE titulo_programa;

-- Insertar los niveles de formación oficiales del SENA
INSERT INTO titulo_programa (titpro_id, titpro_nombre) VALUES
(1, 'Auxiliar'),
(2, 'Operario'),
(3, 'Técnico'),
(4, 'Tecnólogo'),
(5, 'Especialización Tecnológica')
ON DUPLICATE KEY UPDATE titpro_nombre = VALUES(titpro_nombre);

-- Nota: Los niveles de formación del SENA son:
-- 1. Auxiliar - Formación complementaria básica
-- 2. Operario - Formación complementaria operativa
-- 3. Técnico - Formación titulada de nivel técnico profesional
-- 4. Tecnólogo - Formación titulada de nivel tecnológico
-- 5. Especialización Tecnológica - Formación de posgrado tecnológico
