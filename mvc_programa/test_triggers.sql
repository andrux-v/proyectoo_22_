-- ============================================
-- SCRIPT DE PRUEBA PARA TRIGGERS DE ASIGNACIONES
-- ============================================

USE gestion_academica;

-- ============================================
-- PRUEBA 1: Inserción válida (debe funcionar)
-- ============================================
SET @usuario_actual = 'test@sena.edu.co';

INSERT INTO asignacion (
    instructor_inst_id,
    asig_fecha_ini,
    asig_fecha_fin,
    ficha_fich_id,
    ambiente_amb_id,
    competencia_comp_id
) VALUES (
    1,                          -- Instructor ID 1
    '2026-06-01 00:00:00',     -- Inicio
    '2026-06-29 00:00:00',     -- Fin (4 semanas)
    3115419,                    -- Ficha
    'A101',                     -- Ambiente
    2                           -- Competencia (40 horas / 4 semanas = 10 horas/semana) ✅
);

-- Verificar que se creó la auditoría
SELECT * FROM auditoria_asignaciones ORDER BY id_auditoria DESC LIMIT 1;

-- ============================================
-- PRUEBA 2: Inserción inválida - Supera 20 horas semanales
-- (debe fallar con error)
-- ============================================
-- Intentar asignar 40 horas en 1 semana = 40 horas/semana ❌
INSERT INTO asignacion (
    instructor_inst_id,
    asig_fecha_ini,
    asig_fecha_fin,
    ficha_fich_id,
    ambiente_amb_id,
    competencia_comp_id
) VALUES (
    1,
    '2026-07-01 00:00:00',
    '2026-07-08 00:00:00',     -- Solo 1 semana
    3115419,
    'A101',
    2                           -- 40 horas / 1 semana = 40 horas/semana ❌
);
-- Debe mostrar: Error: No se puede asignar una carga mayor a 20 horas semanales

-- ============================================
-- PRUEBA 3: Actualización (debe registrar en auditoría)
-- ============================================
SET @usuario_actual = 'coordinador@sena.edu.co';

-- Obtener el ID de la última asignación creada
SET @last_asig_id = (SELECT asig_id FROM asignacion ORDER BY asig_id DESC LIMIT 1);

-- Actualizar el ambiente
UPDATE asignacion 
SET ambiente_amb_id = 'B102'
WHERE asig_id = @last_asig_id;

-- Verificar auditoría de actualización
SELECT * FROM auditoria_asignaciones 
WHERE id_asignacion = @last_asig_id 
ORDER BY fecha_registro DESC;

-- ============================================
-- PRUEBA 4: Consultar carga horaria de un instructor
-- ============================================
SELECT 
    i.inst_nombres,
    i.inst_apellidos,
    a.asig_id,
    c.comp_nombre_corto,
    c.comp_horas as horas_totales,
    a.asig_fecha_ini,
    a.asig_fecha_fin,
    DATEDIFF(a.asig_fecha_fin, a.asig_fecha_ini) / 7 as semanas,
    ROUND(c.comp_horas / (DATEDIFF(a.asig_fecha_fin, a.asig_fecha_ini) / 7), 2) as horas_semanales
FROM asignacion a
INNER JOIN instructor i ON a.instructor_inst_id = i.inst_id
INNER JOIN competencia c ON a.competencia_comp_id = c.comp_id
WHERE i.inst_id = 1
ORDER BY a.asig_fecha_ini DESC;

-- ============================================
-- PRUEBA 5: Ver toda la auditoría
-- ============================================
SELECT 
    id_auditoria,
    id_asignacion,
    usuario_que_creo,
    fecha_registro,
    LEFT(detalles, 100) as detalles_resumen
FROM auditoria_asignaciones
ORDER BY fecha_registro DESC
LIMIT 10;

-- ============================================
-- LIMPIEZA (opcional - descomentar para limpiar datos de prueba)
-- ============================================
-- DELETE FROM asignacion WHERE asig_id = @last_asig_id;
-- DELETE FROM auditoria_asignaciones WHERE id_asignacion = @last_asig_id;

-- ============================================
-- VERIFICAR TRIGGERS INSTALADOS
-- ============================================
SHOW TRIGGERS WHERE `Table` = 'asignacion';
