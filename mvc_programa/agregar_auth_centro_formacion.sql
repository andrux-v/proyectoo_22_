-- Agregar campos de autenticación a centro_formacion
ALTER TABLE centro_formacion 
ADD COLUMN cent_correo VARCHAR(100) UNIQUE AFTER cent_nombre,
ADD COLUMN cent_password VARCHAR(255) AFTER cent_correo;

-- Actualizar centros existentes con credenciales por defecto
-- Cambiar estas contraseñas después del primer login
UPDATE centro_formacion 
SET cent_correo = CONCAT('centro', cent_id, '@sena.edu.co'),
    cent_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' -- password: password
WHERE cent_correo IS NULL;
