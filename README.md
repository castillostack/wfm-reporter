# Sistema de Gestión de Horarios WFM - Call Center

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15+-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Sistema completo de gestión de horarios, asistencia y reportería para departamentos de Workforce Management**

[Características](#-características) • [Instalación](#-instalación) • [Documentación](#-documentación) • [Roadmap](#-roadmap)

</div>

---

## 📋 Tabla de Contenidos

- [Descripción](#-descripción)
- [Características](#-características)
- [Roles de Usuario](#-roles-de-usuario)
- [Stack Tecnológico](#-stack-tecnológico)
- [Requisitos Previos](#-requisitos-previos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Migraciones y Seeders](#-migraciones-y-seeders)
- [Testing](#-testing)
- [Roadmap](#-roadmap)
- [Contribución](#-contribución)
- [Licencia](#-licencia)

---

## 📖 Descripción

Sistema integral de gestión de horarios diseñado específicamente para centros de contacto (call centers), desarrollado para el departamento de **Workforce Management (WFM)**. 

Permite la administración centralizada de horarios de operadores, gestión de solicitudes de cambios de turno, registro de asistencia en tiempo real y generación de reportes detallados de cumplimiento y productividad.

### 🎯 Problema que Resuelve

- ❌ Gestión manual de horarios en Excel propensa a errores
- ❌ Procesos de aprobación lentos y sin trazabilidad
- ❌ Falta de visibilidad en tiempo real de la asistencia
- ❌ Generación manual de reportes que consume horas
- ❌ Dificultad para calcular métricas de cumplimiento

### ✅ Solución

- ✔️ Gestión automatizada con validaciones
- ✔️ Flujos de aprobación digitales con notificaciones
- ✔️ Dashboard en tiempo real
- ✔️ Reportes automáticos exportables
- ✔️ Cálculo automático de KPIs

---

## ✨ Características

### 🏢 Gestión Administrativa
- **Gestión de Usuarios** - CRUD completo con roles y permisos
- **Gestión de Empleados** - Perfiles completos con datos personales
- **Gestión de Departamentos** - Estructura organizacional jerárquica
- **Gestión de Equipos** - Agrupación de empleados por funciones
- **Sistema de Roles y Permisos** - RBAC completo con Spatie Laravel Permission
- **Configuración del Sistema** - Gestión de parámetros globales

### 🗓️ Gestión de Horarios
- **Plantillas reutilizables** - Crea turnos predefinidos (mañana, tarde, noche)
- **Asignación masiva** - Asigna horarios a equipos completos en segundos
- **Importación CSV** - Carga horarios masivos desde archivos
- **Duplicación inteligente** - Copia horarios de semanas anteriores
- **Vista calendario** - Visualización clara semanal/mensual

### 📝 Solicitudes y Aprobaciones
- **Cambios de turno** - Intercambio entre operadores con aprobación
- **Días libres y permisos** - Solicitud digital con workflow
- **Vacaciones** - Gestión con control de saldo disponible
- **Aprobación multinivel** - Coordinador → RRHH según tipo
- **Notificaciones automáticas** - Email en cada cambio de estado

### 👥 Gestión de Asistencia
- **Registro digital** - Marca entrada/salida desde web o móvil
- **Comparación automática** - Horario programado vs real
- **Cálculo de retrasos** - Minutos tarde con tolerancia configurable
- **Estados inteligentes** - Presente, tarde, ausente, justificado
- **Historial completo** - Consulta de asistencia histórica

### 📊 Reportes y Métricas
- **Dashboard ejecutivo** - KPIs en tiempo real
- **Reporte de asistencia** - Detallado por usuario/equipo/período
- **Reporte de cumplimiento** - Comparativa programado vs real
- **Tasa de puntualidad** - % de llegadas a tiempo
- **Índice de ausentismo** - % de ausencias sobre días laborables
- **Exportación múltiple** - PDF, Excel, CSV

### 👔 Multi-tenancy por Roles
- **6 roles diferentes** - Analista WFM, Director, Jefe, Coordinador, Operador, RRHH
- **Permisos granulares** - Control fino con Spatie Permission (25+ permisos)
- **Vistas personalizadas** - Cada rol ve solo lo relevante
- **Seguridad robusta** - Policies en cada acción crítica

---

## 👥 Roles de Usuario

### 🔧 Analista WFM (Administrador del Sistema)
- **Gestión Total del Sistema** - Control completo de todas las funcionalidades
- **Administración de Usuarios** - CRUD completo, asignación de roles y permisos
- **Gestión de Empleados** - Perfiles completos con datos personales y asignación a equipos
- **Administración de Departamentos** - Creación y gestión de estructura organizacional
- **Gestión de Equipos** - Creación de equipos y asignación de empleados
- **Sistema de Roles y Permisos** - Gestión completa del RBAC (25+ permisos)
- **Configuración del Sistema** - Parámetros globales, cachés, comandos de mantenimiento
- **Asignación Masiva de Horarios** - Horarios para equipos completos
- **Importación CSV** - Carga masiva de datos
- **Acceso a Todos los Reportes** - Reportes completos y exportación

### 👔 Director Nacional
- **Vista Ejecutiva Completa** - Dashboard estratégico de toda la operación
- **Reportes Consolidados** - KPIs y métricas de alto nivel
- **Acceso de Solo Lectura** - No puede modificar datos
- **Visibilidad Global** - Todos los departamentos y equipos

### 📈 Jefe de Departamento
- **Vista Completa de su Departamento** - Todos los equipos bajo su mando
- **Reportes Departamentales** - Métricas consolidadas por departamento
- **Comparativas entre Equipos** - Análisis de rendimiento
- **Acceso de Solo Lectura** - No puede modificar datos

### 👨‍💼 Coordinador
- **Gestión de su Equipo** - Empleados asignados directamente
- **Aprobación de Solicitudes** - Cambios de turno, permisos (excepto vacaciones)
- **Monitoreo de Asistencia** - Vista en tiempo real de su equipo
- **Vista de Horarios del Equipo** - Programación y modificaciones
- **Reportes de Equipo** - Métricas específicas de su grupo

### 👤 Operador
- **Consulta de su Horario** - Vista personal de turnos asignados
- **Solicitud de Cambios** - Permisos, cambios de turno, vacaciones
- **Marca de Asistencia** - Registro de entrada/salida
- **Historial Personal** - Consulta de asistencia y solicitudes
- **Auto-gestión** - Modificación de datos personales

### 🏢 Recursos Humanos (Opcional)
- **Gestión de Vacaciones** - Control de saldos y aprobaciones
- **Administración de Empleados** - Datos personales y contratos
- **Reportes de Personal** - Estadísticas de empleados
- **Gestión de Nómina** - Integración con sistemas de pago

---

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 11.x** - Framework PHP
- **PHP 8.2+** - Lenguaje
- **PostgreSQL 15+** - Base de datos
- **Spatie Laravel Permission** - Roles y permisos
- **Laravel Excel** - Importación/exportación
- **DomPDF** - Generación de PDFs

### Frontend
- **Blade Templates** - Motor de plantillas
- **Livewire 3.x** - Componentes reactivos
- **Alpine.js** - Interactividad ligera
- **Tailwind CSS** - Estilos
- **Chart.js** - Gráficos
- **FullCalendar.js** - Vista calendario

### Herramientas
- **Composer** - Gestor de dependencias PHP
- **NPM** - Gestor de dependencias JS
- **Vite** - Build tool
- **Redis** (opcional) - Cache

---

## 📋 Requisitos Previos

- PHP >= 8.2
- Composer >= 2.6
- PostgreSQL >= 15
- Node.js >= 18.x
- NPM >= 9.x
- Redis (opcional, para cache)

---

## 🚀 Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/wfm-schedule-system.git
cd wfm-schedule-system
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JavaScript

```bash
npm install
```

### 4. Configurar variables de entorno

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus credenciales:

```env
APP_NAME="WFM Schedule System"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=wfm_schedule
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@wfm.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generar clave de aplicación

```bash
php artisan key:generate
```

### 6. Crear base de datos

```bash
# Conéctate a PostgreSQL
psql -U postgres

# Crea la base de datos
CREATE DATABASE wfm_schedule;
```

### 7. Ejecutar migraciones

```bash
php artisan migrate
```

### 8. Ejecutar seeders (datos de prueba)

```bash
php artisan db:seed
```

Esto creará:
- Roles y permisos
- 5 departamentos
- 15 equipos
- 1 usuario administrador (analista WFM)
- 100 operadores de prueba
- Datos de ejemplo

**Credenciales de prueba:**
```
Email: admin@wfm.com
Password: password
```

### 9. Compilar assets

```bash
npm run dev
```

### 10. Iniciar servidor

```bash
php artisan serve
```

Accede a: `http://localhost:8000`

---

## ⚙️ Configuración

### Configuración de Tolerancia de Retraso

Edita `config/wfm.php`:

```php
<?php

return [
    'attendance' => [
        'late_tolerance_minutes' => 10, // Tolerancia de retraso
        'absent_threshold_minutes' => 30, // Minutos para marcar ausente
    ],
    
    'schedule' => [
        'break_default_duration' => 15, // Duración descanso (minutos)
        'lunch_default_duration' => 60, // Duración almuerzo (minutos)
    ],
    
    'notifications' => [
        'enabled' => true,
        'channels' => ['mail', 'database'],
    ],
];
```

### Configuración de Roles Inicial

El seeder `RolePermissionSeeder` crea automáticamente:

```php
- Analista WFM (admin total)
- Director Nacional (solo lectura)
- Jefe de Departamento (lectura departamento)
- Coordinador (gestión equipo)
- Operador (auto-gestión)
- Recursos Humanos (gestión vacaciones)
```

---

## 📚 Uso

### Crear un Usuario

```bash
php artisan make:user
```

O desde el panel de administración: `/admin/users/create`

### Asignar Horario a un Operador

1. Login como Analista WFM
2. Ir a **Horarios → Asignación Masiva**
3. Seleccionar equipo y rango de fechas
4. Elegir plantilla de horario
5. Confirmar asignación

### Importar Horarios desde CSV

Formato CSV esperado:

```csv
numero_empleado,fecha,entrada,descanso_inicio,descanso_duracion,almuerzo_inicio,almuerzo_duracion,salida
12345,2026-02-03,07:00,10:00,15,12:00,60,15:00
12346,2026-02-03,08:00,11:00,15,13:00,60,16:00
```

Ruta: **Horarios → Importar CSV**

### Aprobar Solicitudes (Coordinador)

1. Login como Coordinador
2. Ver badge de solicitudes pendientes en el menú
3. Ir a **Solicitudes → Pendientes de Aprobación**
4. Revisar detalle de solicitud
5. Aprobar o rechazar con notas

### Generar Reporte

1. Ir a **Reportes → [Tipo de Reporte]**
2. Configurar filtros:
   - Período
   - Departamento/Equipo
   - Formato (PDF/Excel/CSV)
3. Click en **Generar Reporte**
4. Descargar archivo

---

## 📁 Estructura del Proyecto

## 📁 Estructura del Proyecto

```
wfm-schedule-system/
├── app/
│   ├── Actions/
│   │   ├── Usuarios/
│   │   │   ├── CrearUsuarioAction.php
│   │   │   ├── ActualizarUsuarioAction.php
│   │   │   └── EliminarUsuarioAction.php
│   │   ├── Empleados/
│   │   │   ├── CrearEmpleadoAction.php
│   │   │   └── ActualizarEmpleadoAction.php
│   │   ├── Departamentos/
│   │   │   └── CrearDepartamentoAction.php
│   │   └── Equipos/
│   │       ├── CrearEquipoAction.php
│   │       └── AsignarEmpleadosAction.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── UserController.php
│   │   │   │   ├── EmployeeController.php
│   │   │   │   ├── DepartmentController.php
│   │   │   │   ├── TeamController.php
│   │   │   │   ├── RoleController.php
│   │   │   │   ├── PermissionController.php
│   │   │   │   └── SystemConfigController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ScheduleController.php
│   │   │   ├── TimeOffRequestController.php
│   │   │   ├── AttendanceController.php
│   │   │   └── ReportController.php
│   │   ├── Livewire/
│   │   │   ├── Admin/
│   │   │   │   ├── UserManagement.php
│   │   │   │   ├── RoleManagement.php
│   │   │   │   └── SystemConfig.php
│   │   │   ├── PendingRequestsTable.php
│   │   │   └── TeamScheduleCalendar.php
│   │   ├── Requests/
│   │   │   ├── Admin/
│   │   │   │   ├── CreateUserRequest.php
│   │   │   │   ├── UpdateUserRequest.php
│   │   │   │   └── CreateEmployeeRequest.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Employee.php
│   │   ├── Department.php
│   │   ├── Team.php
│   │   ├── Schedule.php
│   │   ├── ScheduleTemplate.php
│   │   ├── ScheduleActivity.php
│   │   ├── TimeOffRequest.php
│   │   ├── ShiftSwap.php
│   │   ├── Leave.php
│   │   └── AttendanceLog.php
│   ├── Services/
│   │   ├── ScheduleService.php
│   │   ├── MetricsCalculator.php
│   │   ├── ReportService.php
│   │   └── PermissionService.php
│   ├── Policies/
│   │   ├── UserPolicy.php
│   │   ├── EmployeePolicy.php
│   │   ├── DepartmentPolicy.php
│   │   └── TeamPolicy.php
│   ├── Helpers/
│   │   └── MenuHelper.php
│   └── Exports/
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2026_01_28_091108_create_employees_table.php
│   │   ├── 2026_01_28_114821_create_schedule_templates_table.php
│   │   ├── 2026_01_28_114850_create_schedules_table.php
│   │   ├── 2026_01_28_115035_create_exceptions_table.php
│   │   ├── 2026_01_28_120435_create_permission_tables.php
│   │   └── ... (migraciones adicionales)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── DepartmentSeeder.php
│       ├── EmployeeSeeder.php
│       ├── UserSeeder.php
│       ├── RolePermissionSeeder.php
│       └── TestDataSeeder.php
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── users/
│   │   │   ├── employees/
│   │   │   ├── departments/
│   │   │   ├── teams/
│   │   │   ├── roles/
│   │   │   ├── permissions/
│   │   │   └── config/
│   │   ├── dashboard/
│   │   ├── schedules/
│   │   ├── requests/
│   │   └── reports/
│   └── js/
├── routes/
│   ├── web.php
│   └── api.php
├── tests/
│   ├── Feature/
│   │   ├── Admin/
│   │   │   ├── UserManagementTest.php
│   │   │   ├── RolePermissionTest.php
│   │   │   └── SystemConfigTest.php
│   └── Unit/
├── config/
│   ├── permission.php
│   ├── wfm.php
│   └── ... (otros archivos de config)
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## 🗄️ Migraciones y Seeders

### Ejecutar Migraciones

```bash
# Todas las migraciones
php artisan migrate

# Migración específica
php artisan migrate --path=/database/migrations/2026_01_28_create_schedules_table.php

# Rollback última migración
php artisan migrate:rollback

# Resetear todo
php artisan migrate:fresh
```

### Ejecutar Seeders

```bash
# Todos los seeders
php artisan db:seed

# Seeder específico
php artisan db:seed --class=RolePermissionSeeder

# Fresh + Seed (resetear y poblar)
php artisan migrate:fresh --seed
```

### Seeders Disponibles

- `RolePermissionSeeder` - Roles y permisos
- `DepartmentSeeder` - Departamentos y equipos
- `UserSeeder` - Usuarios de prueba
- `ScheduleTemplateSeeder` - Plantillas de horarios
- `TestDataSeeder` - Datos completos de prueba

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Tests específicos
php artisan test --filter=ScheduleTest

# Con coverage
php artisan test --coverage
```

### Tests Incluidos

- ✅ Autenticación y roles
- ✅ Creación de horarios
- ✅ Aprobación de solicitudes
- ✅ Cálculo de métricas
- ✅ Registro de asistencia
- ✅ Generación de reportes
- ✅ **Gestión de usuarios** - CRUD y asignación de roles
- ✅ **Gestión de empleados** - Creación y actualización
- ✅ **Gestión de departamentos** - Jerarquía organizacional
- ✅ **Gestión de equipos** - Creación y asignación
- ✅ **Sistema de permisos** - RBAC y autorizaciones
- ✅ **Configuración del sistema** - Parámetros y mantenimiento

---

## 🗺️ Roadmap

### ✅ Versión 1.0 (MVP) - Completado
- [x] Sistema de autenticación
- [x] Gestión de horarios
- [x] Solicitudes y aprobaciones
- [x] Registro de asistencia
- [x] Reportes básicos
- [x] 5 roles de usuario

### ✅ Versión 1.1 (Administración Completa) - Completado
- [x] **Gestión de Usuarios** - CRUD completo con roles y permisos
- [x] **Gestión de Empleados** - Perfiles completos con datos personales
- [x] **Gestión de Departamentos** - Estructura organizacional jerárquica
- [x] **Gestión de Equipos** - Creación y asignación de empleados
- [x] **Sistema de Roles y Permisos** - RBAC avanzado (25+ permisos)
- [x] **Configuración del Sistema** - Parámetros globales y mantenimiento
- [x] **Actions Pattern** - Arquitectura limpia con separación de responsabilidades
- [x] **Policies y Autorización** - Control granular de acceso
- [x] **Form Requests** - Validación robusta en todas las operaciones
- [x] **Testing Suite** - Cobertura completa de funcionalidades administrativas

### 🚧 Versión 1.2 - En Desarrollo
- [ ] Integración biométrica
- [ ] App móvil (Flutter)
- [ ] Notificaciones push
- [ ] Dashboard mejorado con gráficos avanzados
- [ ] Exportación masiva programada
- [ ] API REST completa

### 📅 Versión 2.0 - Planeado
- [ ] Inteligencia artificial para predicción de ausentismo
- [ ] Optimización automática de horarios
- [ ] Integración con sistemas de nómina
- [ ] Multi-idioma (inglés, portugués)
- [ ] Análisis predictivo de rendimiento

---

## 🤝 Contribución

¡Las contribuciones son bienvenidas! Por favor sigue estos pasos:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add: nueva funcionalidad increíble'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

### Convenciones de Código

- PSR-12 para PHP
- ESLint para JavaScript
- Commits semánticos (Add, Fix, Update, Remove, Refactor)

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 👨‍💻 Autor

**Tu Nombre**
- GitHub: [@tu-usuario](https://github.com/tu-usuario)
- LinkedIn: [Tu Perfil](https://linkedin.com/in/tu-perfil)
- Email: tu-email@ejemplo.com

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP
- [Spatie](https://spatie.be) - Paquetes increíbles
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Livewire](https://livewire.laravel.com) - Componentes reactivos

---

## 📸 Screenshots

### Dashboard Analista WFM
![Dashboard](https://via.placeholder.com/800x400?text=Dashboard+Screenshot)

### Vista de Horarios
![Schedules](https://via.placeholder.com/800x400?text=Schedules+Screenshot)

### Aprobación de Solicitudes
![Requests](https://via.placeholder.com/800x400?text=Requests+Screenshot)

### Reportes
![Reports](https://via.placeholder.com/800x400?text=Reports+Screenshot)

---

<div align="center">

**⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub ⭐**

</div>
