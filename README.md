# Sistema de Gestión de Programas de Formación SENA

Sistema web desarrollado en PHP con arquitectura MVC para la gestión de programas de formación, ambientes, instructores y asignaciones del SENA.

## 🚀 Características

- **Arquitectura MVC**: Separación clara entre Modelo, Vista y Controlador
- **Control de Roles**: Sistema con dos roles (Coordinador e Instructor)
  - **Coordinador**: Acceso completo CRUD a todos los módulos
  - **Instructor**: Acceso de solo lectura
- **Módulos Implementados**:
  - Gestión de Ambientes
  - Gestión de Sedes
  - Gestión de Centros de Formación
  - Gestión de Coordinaciones
  - Gestión de Programas
  - Gestión de Fichas
  - Gestión de Instructores
  - Gestión de Competencias
  - Gestión de Asignaciones

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/XAMPP recomendado)
- phpMyAdmin (opcional, para gestión de base de datos)

## 🔧 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/andrux-v/proyectoo_22_.git
   cd proyectoo_22_
   ```

2. **Configurar la base de datos**
   - Crear una base de datos llamada `progsena` en MySQL
   - Importar el archivo `mvc_programa/progFormacion2.sql` en phpMyAdmin o mediante CLI:
     ```bash
     mysql -u root -p progsena < mvc_programa/progFormacion2.sql
     ```

3. **Configurar la conexión**
   - Editar el archivo `mvc_programa/Conexion.php` con tus credenciales:
     ```php
     private static $host = 'localhost';
     private static $dbname = 'progsena';
     private static $username = 'root';
     private static $password = '';
     ```

4. **Iniciar el servidor**
   - Si usas XAMPP, coloca el proyecto en `C:\xampp\htdocs\proyectoo_22_`
   - Accede a: `http://localhost/proyectoo_22_/mvc_programa/`

## 🎯 Uso

1. **Selección de Rol**
   - Al acceder al sistema, selecciona tu rol (Coordinador o Instructor)

2. **Panel de Coordinador**
   - Acceso completo para crear, editar, ver y eliminar registros
   - Gestión de todos los módulos del sistema

3. **Panel de Instructor**
   - Acceso de solo lectura a todos los módulos
   - Visualización de información sin permisos de modificación

## 📁 Estructura del Proyecto

```
proyectoo_22_/
├── mvc_programa/
│   ├── assets/
│   │   └── css/
│   │       └── styles.css
│   ├── controller/
│   │   └── AmbienteController.php
│   ├── model/
│   │   ├── AmbienteModel.php
│   │   ├── SedeModel.php
│   │   ├── CentroFormacionModel.php
│   │   ├── CoordinacionModel.php
│   │   ├── ProgramaModel.php
│   │   ├── FichaModel.php
│   │   ├── InstructorModel.php
│   │   ├── CompetenciaModel.php
│   │   ├── AsignacionModel.php
│   │   └── ...
│   ├── views/
│   │   ├── layout/
│   │   │   ├── header_coordinador.php
│   │   │   ├── header_instructor.php
│   │   │   ├── footer.php
│   │   │   └── rol_detector.php
│   │   ├── ambiente/
│   │   ├── sede/
│   │   ├── centro_formacion/
│   │   ├── coordinacion/
│   │   ├── programa/
│   │   ├── ficha/
│   │   ├── instructor/
│   │   ├── competencia/
│   │   └── asignacion/
│   ├── Conexion.php
│   ├── index.php
│   └── progFormacion2.sql
└── README.md
```

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Iconos**: Lucide Icons
- **Arquitectura**: MVC (Modelo-Vista-Controlador)

## 👥 Roles y Permisos

| Módulo | Coordinador | Instructor |
|--------|-------------|------------|
| Ver | ✅ | ✅ |
| Crear | ✅ | ❌ |
| Editar | ✅ | ❌ |
| Eliminar | ✅ | ❌ |

## 📝 Notas de Desarrollo

- El sistema utiliza PDO para la conexión a la base de datos
- Todas las consultas están preparadas para prevenir inyección SQL
- El sistema de roles se maneja mediante parámetros GET y detección de referer
- Los layouts son dinámicos según el rol del usuario

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## ✨ Autor

- **andrux-v** - [GitHub](https://github.com/andrux-v)

## 📞 Contacto

Para preguntas o sugerencias, por favor abre un issue en el repositorio.
