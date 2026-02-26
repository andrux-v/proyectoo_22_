# Instrucciones para Implementar Triggers de Asignaciones

## 📋 Descripción

Este sistema incluye triggers de base de datos que automatizan:
1. **Validación de carga horaria** - Evita asignaciones que superen límites establecidos
2. **Auditoría automática** - Registra todas las operaciones en asignaciones

## 🚀 Instalación

### Paso 1: Ejecutar el archivo SQL

Abre phpMyAdmin o tu cliente MySQL y ejecuta el archivo:
```
mvc_programa/triggers_asignaciones.sql
```

O desde la línea de comandos:
```bash
mysql -u root -p gestion_academica < mvc_programa/triggers_asignaciones.sql
```

### Paso 2: Verificar la instalación

Ejecuta en MySQL:
```sql
SHOW TRIGGERS WHERE `Table` = 'asignacion';
```

Deberías ver 4 triggers:
- `before_asignacion_insert_check`
- `after_asignacion_insert_audit`
- `after_asignacion_update_audit`
- `after_asignacion_delete_audit`

## 📊 Funcionalidades

### 1. Validación de Carga Horaria

**Límites establecidos:**
- ✅ Máximo 20 horas semanales por competencia
- ✅ Máximo 40 horas semanales totales por instructor

**Cómo funciona:**
- Calcula automáticamente las horas semanales basándose en:
  - Horas totales de la competencia
  - Duración de la asignación (semanas)
- Verifica asignaciones existentes del instructor en el mismo período
- Bloquea la inserción si se superan los límites

**Ejemplo de error:**
```
Error: No se puede asignar una carga mayor a 20 horas semanales por competencia.
```

### 2. Auditoría Automática

**Qué se registra:**
- ✅ Creación de asignaciones
- ✅ Modificaciones (con detalle de cambios)
- ✅ Eliminaciones
- ✅ Usuario que realizó la operación
- ✅ Fecha y hora exacta

**Tabla de auditoría:**
```sql
SELECT * FROM auditoria_asignaciones ORDER BY fecha_registro DESC;
```

**Ejemplo de registro:**
```
NUEVA ASIGNACIÓN - Instructor ID: 1, Ficha: 3115419, Ambiente: B102, 
Competencia ID: 2, Fecha Inicio: 2026-03-02, Fecha Fin: 2026-03-20
```

## 🔧 Validaciones en PHP

El sistema también incluye validaciones en el código PHP (`AsignacionController.php`):

### Validaciones implementadas:

1. **Validación de carga horaria**
   - Método: `validarCargaHoraria()`
   - Se ejecuta antes de crear/actualizar asignaciones
   - Calcula horas semanales y verifica límites

2. **Establecimiento de usuario para auditoría**
   - Captura el usuario de la sesión actual
   - Lo establece en la variable `@usuario_actual`
   - Los triggers lo usan para registrar quién hizo la operación

### Mensajes de error personalizados:

```php
// Ejemplo 1: Supera 20 horas por competencia
"Esta asignación requiere 25.50 horas semanales, superando el límite de 20 horas 
por competencia. Considere extender el período de la asignación."

// Ejemplo 2: Supera 40 horas totales
"El instructor ya tiene 30.00 horas semanales asignadas en este período. 
Esta nueva asignación (15.00 horas/semana) superaría el límite de 40 horas semanales."
```

## 📈 Ejemplo de Uso

### Crear una asignación válida:

```php
// Competencia de 40 horas, asignación de 4 semanas = 10 horas/semana ✅
$data = [
    'instructor_inst_id' => 1,
    'competencia_comp_id' => 2,
    'asig_fecha_ini' => '2026-03-01',
    'asig_fecha_fin' => '2026-03-29', // 28 días = 4 semanas
    'ficha_fich_id' => 3115419,
    'ambiente_amb_id' => 'B102'
];
```

### Crear una asignación inválida:

```php
// Competencia de 40 horas, asignación de 1 semana = 40 horas/semana ❌
$data = [
    'instructor_inst_id' => 1,
    'competencia_comp_id' => 2,
    'asig_fecha_ini' => '2026-03-01',
    'asig_fecha_fin' => '2026-03-08', // 7 días = 1 semana
    'ficha_fich_id' => 3115419,
    'ambiente_amb_id' => 'B102'
];
// Error: Supera 20 horas semanales
```

## 🔍 Consultas Útiles

### Ver auditoría de una asignación específica:
```sql
SELECT * FROM auditoria_asignaciones 
WHERE id_asignacion = 5 
ORDER BY fecha_registro DESC;
```

### Ver todas las operaciones de un usuario:
```sql
SELECT * FROM auditoria_asignaciones 
WHERE usuario_que_creo = 'coordinador@sena.edu.co' 
ORDER BY fecha_registro DESC;
```

### Ver operaciones del último mes:
```sql
SELECT * FROM auditoria_asignaciones 
WHERE fecha_registro >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
ORDER BY fecha_registro DESC;
```

### Calcular carga horaria actual de un instructor:
```sql
SELECT 
    i.inst_nombres,
    i.inst_apellidos,
    a.asig_id,
    c.comp_nombre_corto,
    c.comp_horas,
    DATEDIFF(a.asig_fecha_fin, a.asig_fecha_ini) / 7 as semanas,
    c.comp_horas / (DATEDIFF(a.asig_fecha_fin, a.asig_fecha_ini) / 7) as horas_semanales
FROM asignacion a
INNER JOIN instructor i ON a.instructor_inst_id = i.inst_id
INNER JOIN competencia c ON a.competencia_comp_id = c.comp_id
WHERE i.inst_id = 1
AND NOW() BETWEEN a.asig_fecha_ini AND a.asig_fecha_fin;
```

## ⚠️ Notas Importantes

1. **Los triggers se ejecutan automáticamente** - No necesitas llamarlos manualmente
2. **Las validaciones en PHP son adicionales** - Proporcionan mensajes más amigables
3. **La auditoría es permanente** - No se puede desactivar sin eliminar los triggers
4. **Usuario de auditoría** - Se captura automáticamente de la sesión PHP

## 🐛 Solución de Problemas

### Error: "Trigger already exists"
```sql
DROP TRIGGER IF EXISTS before_asignacion_insert_check;
-- Luego vuelve a ejecutar el archivo triggers_asignaciones.sql
```

### Ver errores de triggers:
```sql
SHOW ERRORS;
SHOW WARNINGS;
```

### Desactivar temporalmente un trigger:
```sql
DROP TRIGGER before_asignacion_insert_check;
-- Para reactivarlo, ejecuta nuevamente el archivo SQL
```

## 📞 Soporte

Si encuentras problemas con los triggers:
1. Verifica que la tabla `auditoria_asignaciones` existe
2. Revisa los logs de PHP: `error_log()`
3. Consulta los logs de MySQL
4. Verifica que los triggers estén creados: `SHOW TRIGGERS`
