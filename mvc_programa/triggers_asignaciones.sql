-- ============================================
-- TRIGGERS PARA ASIGNACIONES
-- Sistema de Gestión Académica SENA
-- ============================================

USE gestion_academica;

-- Eliminar triggers existentes si existen
DROP TRIGGER IF EXISTS before_asignacion_insert_check;
DROP TRIGGER IF EXISTS after_asignacion_insert_audit;
DROP TRIGGER IF EXISTS after_asignacion_update_audit;
DROP TRIGGER IF EXISTS after_asignacion_delete_audit;

DELIMITER //

-- ============================================
-- TRIGGER 1: Validación antes de insertar
-- Valida que la carga horaria no supere 20 horas semanales
-- No cuenta los domingos en el cálculo
-- ============================================
CREATE TRIGGER before_asignacion_insert_check
BEFORE INSERT ON asignacion
FOR EACH ROW
BEGIN
    DECLARE total_horas_semanales INT DEFAULT 0;
    DECLARE horas_competencia INT DEFAULT 0;
    DECLARE dias_totales INT DEFAULT 0;
    DECLARE dias_habiles INT DEFAULT 0;
    DECLARE semanas_asignacion DECIMAL(10,2) DEFAULT 0;
    DECLARE horas_por_semana DECIMAL(10,2) DEFAULT 0;
    
    -- Obtener las horas de la competencia
    SELECT comp_horas INTO horas_competencia
    FROM competencia
    WHERE comp_id = NEW.competencia_comp_id;
    
    -- Calcular días totales
    SET dias_totales = DATEDIFF(NEW.asig_fecha_fin, NEW.asig_fecha_ini);
    
    -- Calcular días hábiles (excluyendo domingos)
    -- Aproximación: 6 días hábiles por cada 7 días
    SET dias_habiles = FLOOR(dias_totales * 6 / 7);
    
    -- Calcular semanas hábiles (6 días = 1 semana hábil)
    SET semanas_asignacion = dias_habiles / 6;
    
    -- Evitar división por cero
    IF semanas_asignacion <= 0 THEN
        SET semanas_asignacion = 1;
    END IF;
    
    -- Calcular horas por semana de esta asignación
    SET horas_por_semana = horas_competencia / semanas_asignacion;
    
    -- Validar que no supere 20 horas semanales
    IF horas_por_semana > 20 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: No se puede asignar una carga mayor a 20 horas semanales por competencia. Esta asignación requiere aproximadamente horas por semana.';
    END IF;
    
    -- Calcular total de horas semanales del instructor en el mismo período
    SELECT COALESCE(SUM(
        (SELECT comp_horas FROM competencia WHERE comp_id = a.competencia_comp_id) / 
        ((FLOOR(DATEDIFF(a.asig_fecha_fin, a.asig_fecha_ini) * 6 / 7)) / 6)
    ), 0) INTO total_horas_semanales
    FROM asignacion a
    WHERE a.instructor_inst_id = NEW.instructor_inst_id
    AND (
        (NEW.asig_fecha_ini BETWEEN a.asig_fecha_ini AND a.asig_fecha_fin)
        OR (NEW.asig_fecha_fin BETWEEN a.asig_fecha_ini AND a.asig_fecha_fin)
        OR (a.asig_fecha_ini BETWEEN NEW.asig_fecha_ini AND NEW.asig_fecha_fin)
    );
    
    -- Validar que el total no supere 40 horas semanales (carga máxima del instructor)
    IF (total_horas_semanales + horas_por_semana) > 40 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: El instructor ya tiene asignaciones que suman más de 40 horas semanales en este período.';
    END IF;
END//

-- ============================================
-- TRIGGER 2: Auditoría después de insertar
-- Registra en la tabla de auditoría cuando se crea una asignación
-- ============================================
CREATE TRIGGER after_asignacion_insert_audit
AFTER INSERT ON asignacion
FOR EACH ROW
BEGIN
    DECLARE usuario VARCHAR(100);
    DECLARE detalles_texto TEXT;
    
    -- Obtener el usuario actual (puede ser del sistema o de la sesión)
    SET usuario = IFNULL(@usuario_actual, USER());
    
    -- Construir detalles de la asignación
    SET detalles_texto = CONCAT(
        'NUEVA ASIGNACIÓN - ',
        'Instructor ID: ', NEW.instructor_inst_id, ', ',
        'Ficha: ', NEW.ficha_fich_id, ', ',
        'Ambiente: ', NEW.ambiente_amb_id, ', ',
        'Competencia ID: ', NEW.competencia_comp_id, ', ',
        'Fecha Inicio: ', NEW.asig_fecha_ini, ', ',
        'Fecha Fin: ', NEW.asig_fecha_fin
    );
    
    -- Insertar en auditoría
    INSERT INTO auditoria_asignaciones (
        id_asignacion,
        usuario_que_creo,
        fecha_registro,
        detalles
    ) VALUES (
        NEW.asig_id,
        usuario,
        NOW(),
        detalles_texto
    );
END//

-- ============================================
-- TRIGGER 3: Auditoría después de actualizar
-- Registra cambios en asignaciones existentes
-- ============================================
CREATE TRIGGER after_asignacion_update_audit
AFTER UPDATE ON asignacion
FOR EACH ROW
BEGIN
    DECLARE usuario VARCHAR(100);
    DECLARE detalles_texto TEXT;
    
    SET usuario = IFNULL(@usuario_actual, USER());
    
    -- Construir detalles de los cambios
    SET detalles_texto = CONCAT(
        'ACTUALIZACIÓN - ',
        'Cambios: '
    );
    
    IF OLD.instructor_inst_id != NEW.instructor_inst_id THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Instructor (', OLD.instructor_inst_id, ' -> ', NEW.instructor_inst_id, '), ');
    END IF;
    
    IF OLD.ficha_fich_id != NEW.ficha_fich_id THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Ficha (', OLD.ficha_fich_id, ' -> ', NEW.ficha_fich_id, '), ');
    END IF;
    
    IF OLD.ambiente_amb_id != NEW.ambiente_amb_id THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Ambiente (', OLD.ambiente_amb_id, ' -> ', NEW.ambiente_amb_id, '), ');
    END IF;
    
    IF OLD.competencia_comp_id != NEW.competencia_comp_id THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Competencia (', OLD.competencia_comp_id, ' -> ', NEW.competencia_comp_id, '), ');
    END IF;
    
    IF OLD.asig_fecha_ini != NEW.asig_fecha_ini THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Fecha Inicio (', OLD.asig_fecha_ini, ' -> ', NEW.asig_fecha_ini, '), ');
    END IF;
    
    IF OLD.asig_fecha_fin != NEW.asig_fecha_fin THEN
        SET detalles_texto = CONCAT(detalles_texto, 
            'Fecha Fin (', OLD.asig_fecha_fin, ' -> ', NEW.asig_fecha_fin, ')');
    END IF;
    
    INSERT INTO auditoria_asignaciones (
        id_asignacion,
        usuario_que_creo,
        fecha_registro,
        detalles
    ) VALUES (
        NEW.asig_id,
        usuario,
        NOW(),
        detalles_texto
    );
END//

-- ============================================
-- TRIGGER 4: Auditoría después de eliminar
-- Registra cuando se elimina una asignación
-- ============================================
CREATE TRIGGER after_asignacion_delete_audit
AFTER DELETE ON asignacion
FOR EACH ROW
BEGIN
    DECLARE usuario VARCHAR(100);
    DECLARE detalles_texto TEXT;
    
    SET usuario = IFNULL(@usuario_actual, USER());
    
    SET detalles_texto = CONCAT(
        'ELIMINACIÓN - ',
        'Asignación ID: ', OLD.asig_id, ', ',
        'Instructor ID: ', OLD.instructor_inst_id, ', ',
        'Ficha: ', OLD.ficha_fich_id, ', ',
        'Ambiente: ', OLD.ambiente_amb_id, ', ',
        'Competencia ID: ', OLD.competencia_comp_id
    );
    
    INSERT INTO auditoria_asignaciones (
        id_asignacion,
        usuario_que_creo,
        fecha_registro,
        detalles
    ) VALUES (
        OLD.asig_id,
        usuario,
        NOW(),
        detalles_texto
    );
END//

DELIMITER ;

-- ============================================
-- Verificar que los triggers se crearon correctamente
-- ============================================
SHOW TRIGGERS WHERE `Table` = 'asignacion';

-- ============================================
-- NOTAS DE USO:
-- ============================================
-- Para establecer el usuario antes de una operación:
-- SET @usuario_actual = 'coordinador@sena.edu.co';
-- INSERT INTO asignacion (...) VALUES (...);
-- ============================================
