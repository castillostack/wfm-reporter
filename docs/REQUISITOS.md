# Documento de Requisitos y Casos de Uso
## Sistema de Gestión de Horarios y Reportería WFM - Call Center

**Versión:** 1.0  
**Fecha:** Enero 2026  
**Proyecto:** WFM Schedule Management System

---

## 1. INTRODUCCIÓN

### 1.1 Propósito del Documento
Este documento define los requisitos funcionales y casos de uso del Sistema de Gestión de Horarios para el departamento de Workforce Management (WFM) de un centro de contactos.

### 1.2 Alcance del Sistema
El sistema permite:
- Gestión centralizada de horarios de operadores
- Solicitud y aprobación de cambios de turno, permisos y vacaciones
- Registro y monitoreo de asistencia
- Generación de reportes de cumplimiento y métricas de productividad
- Administración de equipos y departamentos

### 1.3 Definiciones y Acrónimos
- **WFM:** Workforce Management
- **Operador:** Agente de servicio al cliente
- **Coordinador:** Supervisor de equipo
- **Jefe:** Jefe de Departamento
- **Director:** Director Nacional
- **Analista WFM:** Administrador del sistema

---

## 2. STAKEHOLDERS Y ROLES

### 2.1 Matriz de Roles

| Rol                      | Descripción                     | Nivel de Acceso                       | Cantidad Estimada |
| ------------------------ | ------------------------------- | ------------------------------------- | ----------------- |
| **Analista WFM**         | Administrador total del sistema | Completo                              | 2-5 usuarios      |
| **Director Nacional**    | Supervisión ejecutiva           | Solo lectura (todos los datos)        | 1-2 usuarios      |
| **Jefe de Departamento** | Gestión de departamento         | Lectura y reportes de su departamento | 5-10 usuarios     |
| **Coordinador**          | Supervisión de equipo           | Gestión y aprobación de su equipo     | 15-30 usuarios    |
| **Operador**             | Usuario final                   | Auto-gestión limitada                 | 500+ usuarios     |

---

## 3. REQUISITOS FUNCIONALES POR ROL

---

## 3.1 ANALISTA WFM (Administrador)

### 3.1.1 Descripción del Rol
Responsable de la administración completa del sistema, gestión de horarios masivos, configuración de plantillas y generación de reportes estratégicos.

### 3.1.2 Requisitos Funcionales

#### RF-WFM-001: Gestión de Usuarios
**Prioridad:** Alta  
**Descripción:** El analista puede crear, editar y desactivar usuarios del sistema.

**Criterios de Aceptación:**
- Crear usuarios con asignación automática de credenciales
- Asignar roles (Director, Jefe, Coordinador, Operador)
- Asignar departamento y equipo
- Cambiar supervisor directo
- Desactivar usuarios sin eliminar historial
- Importar usuarios masivamente desde CSV/Excel
- Exportar listado de usuarios

**Campos requeridos:**
- Nombre completo
- Cédula (único)
- Email corporativo (único)
- Número de empleado (único)
- Fecha de contratación
- Departamento
- Equipo
- Supervisor directo
- Cargo

---

#### RF-WFM-002: Gestión de Departamentos y Equipos
**Prioridad:** Alta  
**Descripción:** Crear y administrar la estructura organizacional.

**Criterios de Aceptación:**
- Crear departamentos con código único
- Asignar jefe de departamento
- Crear equipos dentro de departamentos
- Asignar coordinador a cada equipo
- Ver organigrama visual
- Reasignar operadores entre equipos
- Mover equipos entre departamentos

---

#### RF-WFM-003: Gestión de Plantillas de Horarios
**Prioridad:** Alta  
**Descripción:** Crear plantillas reutilizables de horarios.

**Criterios de Aceptación:**
- Crear plantilla con nombre descriptivo (ej: "Turno Mañana 7-3")
- Definir hora de entrada y salida
- Definir duración de descanso (en minutos)
- Definir duración de almuerzo (en minutos)
- Definir hora de inicio de descanso
- Definir hora de inicio de almuerzo
- Calcular automáticamente horas trabajadas
- Activar/desactivar plantillas
- Duplicar plantillas existentes

**Ejemplo de plantilla:**
```
Nombre: Turno Mañana
Entrada: 07:00
Descanso: 10:00 (15 minutos)
Almuerzo: 12:00 (60 minutos)
Salida: 15:00
Horas trabajadas: 6.75 horas
```

---

#### RF-WFM-004: Asignación Masiva de Horarios
**Prioridad:** Alta  
**Descripción:** Asignar horarios a múltiples operadores simultáneamente.

**Criterios de Aceptación:**
- Seleccionar rango de fechas (semana, mes)
- Seleccionar usuarios (individual, por equipo, por departamento)
- Asignar plantilla de horario
- Vista previa antes de confirmar
- Aplicar horario con un clic
- Validar que no existan conflictos (usuario ya tiene horario)
- Notificar a usuarios afectados por email
- Registrar cambios en log de auditoría

**Flujo típico:**
1. Analista selecciona "Asignar horarios masivos"
2. Selecciona departamento "Ventas"
3. Selecciona equipo "Ventas A"
4. Selecciona semana del 3 al 9 de febrero
5. Selecciona plantilla "Turno Mañana"
6. Sistema muestra 20 operadores × 5 días = 100 horarios a crear
7. Analista confirma
8. Sistema crea horarios y notifica

---

#### RF-WFM-005: Importación de Horarios desde CSV
**Prioridad:** Alta  
**Descripción:** Cargar horarios masivamente desde archivo.

**Criterios de Aceptación:**
- Subir archivo CSV con formato específico
- Validar estructura del archivo
- Validar que usuarios existan
- Validar formato de fechas y horas
- Mostrar errores línea por línea
- Permitir corregir y re-procesar
- Crear horarios validados
- Generar reporte de importación (exitosos/fallidos)

**Formato CSV esperado:**
```csv
numero_empleado,fecha,entrada,descanso_inicio,descanso_duracion,almuerzo_inicio,almuerzo_duracion,salida
12345,2026-02-03,07:00,10:00,15,12:00,60,15:00
12346,2026-02-03,08:00,11:00,15,13:00,60,16:00
```

---

#### RF-WFM-006: Duplicar Horarios de Semana Anterior
**Prioridad:** Media  
**Descripción:** Copiar horarios de la semana pasada a la siguiente.

**Criterios de Aceptación:**
- Seleccionar departamento o equipo
- Seleccionar semana de origen
- Seleccionar semana de destino
- Sistema copia todos los horarios ajustando fechas
- Validar que no existan horarios en semana destino
- Opción de sobrescribir si existen
- Confirmar antes de ejecutar

---

#### RF-WFM-007: Gestión de Solicitudes (Vista Global)
**Prioridad:** Alta  
**Descripción:** Ver y gestionar todas las solicitudes del sistema.

**Criterios de Aceptación:**
- Ver todas las solicitudes (cambio turno, permisos, vacaciones)
- Filtrar por estado (pendiente, aprobado, rechazado)
- Filtrar por tipo
- Filtrar por departamento/equipo
- Filtrar por rango de fechas
- Aprobar/rechazar cualquier solicitud
- Aprobar múltiples solicitudes simultáneamente
- Agregar notas de aprobación
- Ver historial de aprobaciones

---

#### RF-WFM-008: Reportes Avanzados
**Prioridad:** Alta  
**Descripción:** Generar reportes ejecutivos y operativos.

**Criterios de Aceptación:**
- **Reporte de Asistencia:** Filtrar por usuario/equipo/departamento/período
- **Reporte de Cumplimiento:** Comparar horario programado vs real
- **Reporte de Puntualidad:** Tasa de llegadas a tiempo
- **Reporte de Ausentismo:** Días ausentes vs programados
- **Reporte de Horas Trabajadas:** Total por usuario/período
- **Reporte de Solicitudes:** Cantidad por tipo y estado
- Exportar a CSV, Excel, PDF
- Agendar reportes automáticos (diario, semanal, mensual)
- Enviar por email automáticamente

---

#### RF-WFM-009: Dashboard Analítico
**Prioridad:** Media  
**Descripción:** Vista consolidada de métricas clave.

**Criterios de Aceptación:**
- KPIs principales en tarjetas:
  - Total de operadores activos
  - Tasa de asistencia del día
  - Solicitudes pendientes
  - Operadores con retrasos hoy
- Gráficos:
  - Tendencia de puntualidad (últimos 30 días)
  - Ausentismo por departamento
  - Tipos de solicitudes más comunes
- Actualización en tiempo real
- Exportar dashboard como PDF

---

#### RF-WFM-010: Configuración del Sistema
**Prioridad:** Baja  
**Descripción:** Configurar parámetros operativos.

**Criterios de Aceptación:**
- Definir tolerancia de retraso (minutos para marcar "tarde")
- Definir horarios laborales (días de la semana operativos)
- Configurar días feriados
- Configurar notificaciones automáticas
- Configurar días de vacaciones por antigüedad
- Backup y restauración de datos

---

### 3.1.3 Casos de Uso - Analista WFM

#### CU-WFM-001: Crear Horario Semanal para Equipo
**Actor Principal:** Analista WFM  
**Precondiciones:**
- El analista está autenticado
- El equipo existe en el sistema
- La plantilla de horario está creada

**Flujo Principal:**
1. Analista accede a "Gestión de Horarios"
2. Selecciona "Asignación Masiva"
3. Selecciona departamento "Atención al Cliente"
4. Selecciona equipo "Equipo Mañana A"
5. Selecciona semana: 10-16 de febrero 2026
6. Selecciona plantilla: "Turno 7-3"
7. Sistema muestra preview: 25 operadores × 5 días = 125 horarios
8. Analista confirma
9. Sistema crea horarios y muestra mensaje: "125 horarios creados exitosamente"
10. Sistema envía notificación por email a los 25 operadores

**Flujo Alternativo 8a: Conflictos Detectados**
- Sistema detecta que 3 operadores ya tienen horario
- Muestra mensaje: "3 operadores ya tienen horario para estas fechas"
- Analista puede:
  - Excluir esos operadores
  - Sobrescribir horarios existentes
  - Cancelar operación

**Postcondiciones:**
- Horarios creados en base de datos
- Operadores notificados
- Log de auditoría registrado

---

#### CU-WFM-002: Importar Horarios desde CSV
**Actor Principal:** Analista WFM  
**Precondiciones:**
- Archivo CSV preparado con formato correcto

**Flujo Principal:**
1. Analista accede a "Importar Horarios"
2. Descarga plantilla CSV de ejemplo
3. Completa archivo con datos
4. Sube archivo CSV
5. Sistema valida formato
6. Sistema muestra preview de datos:
   - Total de registros: 500
   - Registros válidos: 485
   - Registros con error: 15
7. Sistema muestra errores por línea:
   - Línea 23: Usuario "12789" no existe
   - Línea 45: Formato de hora inválido "25:00"
8. Analista descarga reporte de errores
9. Corrige archivo
10. Re-sube archivo
11. Sistema valida: 500 registros válidos
12. Analista confirma importación
13. Sistema procesa y muestra: "500 horarios importados"

**Flujo Alternativo 5a: Archivo con formato incorrecto**
- Sistema detecta columnas faltantes
- Muestra error: "El archivo debe contener las columnas: numero_empleado, fecha, entrada, salida..."
- Analista debe corregir y subir nuevamente

---

#### CU-WFM-003: Aprobar Solicitudes Pendientes Masivamente
**Actor Principal:** Analista WFM  

**Flujo Principal:**
1. Analista accede a "Solicitudes Pendientes"
2. Aplica filtros:
   - Estado: Pendiente
   - Tipo: Vacaciones
   - Departamento: Todos
3. Sistema muestra 47 solicitudes
4. Analista revisa solicitudes
5. Selecciona 30 solicitudes con checkboxes
6. Click en "Aprobar Seleccionadas"
7. Sistema solicita confirmar: "¿Aprobar 30 solicitudes?"
8. Analista confirma
9. Sistema aprueba solicitudes
10. Sistema notifica a 30 usuarios por email
11. Muestra mensaje: "30 solicitudes aprobadas"

---

#### CU-WFM-004: Generar Reporte de Cumplimiento Mensual
**Actor Principal:** Analista WFM  

**Flujo Principal:**
1. Analista accede a "Reportes"
2. Selecciona "Reporte de Cumplimiento"
3. Configura filtros:
   - Período: Enero 2026
   - Departamento: Todos
   - Formato: Excel
4. Click en "Generar Reporte"
5. Sistema procesa (puede tomar 30-60 segundos)
6. Sistema descarga archivo Excel con:
   - Resumen ejecutivo
   - Detalle por departamento
   - Detalle por operador
   - Gráficos de tendencia
7. Analista abre archivo y revisa datos

**Contenido del reporte:**
- Tasa de cumplimiento general: 87.5%
- Departamento con mejor cumplimiento: Ventas (92.3%)
- Departamento con menor cumplimiento: Soporte (79.1%)
- Top 10 operadores más puntuales
- Operadores con cumplimiento < 80%

---

## 3.2 DIRECTOR NACIONAL

### 3.2.1 Descripción del Rol
Máxima autoridad ejecutiva con acceso a toda la información del sistema en modo solo lectura. Requiere visibilidad completa para toma de decisiones estratégicas.

### 3.2.2 Requisitos Funcionales

#### RF-DIR-001: Dashboard Ejecutivo
**Prioridad:** Alta  
**Descripción:** Vista consolidada de métricas estratégicas.

**Criterios de Aceptación:**
- KPIs consolidados:
  - Total de colaboradores activos
  - Tasa de asistencia general (hoy, semana, mes)
  - Tasa de cumplimiento de horarios
  - Índice de ausentismo
  - Solicitudes pendientes de aprobación
- Comparativas mes actual vs mes anterior
- Gráficos de tendencia (últimos 6 meses)
- Vista por departamento
- Alertas automáticas (ausentismo > 15%, cumplimiento < 80%)

---

#### RF-DIR-002: Reportes Estratégicos
**Prioridad:** Alta  
**Descripción:** Acceso a todos los reportes del sistema.

**Criterios de Aceptación:**
- Reporte ejecutivo mensual (automático)
- Comparativa de desempeño por departamento
- Análisis de tendencias de ausentismo
- Reporte de solicitudes aprobadas/rechazadas
- Reporte de horas trabajadas vs planificadas
- Todos los reportes de Analista WFM
- Exportar a PDF ejecutivo con gráficos

---

#### RF-DIR-003: Vista de Todos los Horarios
**Prioridad:** Media  
**Descripción:** Consultar horarios de cualquier operador.

**Criterios de Aceptación:**
- Buscar operador por nombre, cédula o código
- Ver horario semanal/mensual
- Filtrar por departamento/equipo
- Ver histórico de cambios
- Sin capacidad de edición

---

#### RF-DIR-004: Vista de Todas las Solicitudes
**Prioridad:** Media  
**Descripción:** Consultar solicitudes sin capacidad de aprobar.

**Criterios de Aceptación:**
- Ver todas las solicitudes del sistema
- Filtrar por estado, tipo, departamento
- Ver detalle completo de cada solicitud
- Ver historial de aprobaciones
- Sin capacidad de aprobar/rechazar

---

#### RF-DIR-005: Análisis de Asistencia
**Prioridad:** Media  
**Descripción:** Vista consolidada de asistencia.

**Criterios de Aceptación:**
- Ver asistencia en tiempo real (quién está presente ahora)
- Filtrar por departamento/equipo
- Ver histórico de asistencia
- Identificar patrones de ausentismo
- Ver operadores con retrasos frecuentes

---

### 3.2.3 Casos de Uso - Director Nacional

#### CU-DIR-001: Revisar Dashboard Ejecutivo Diario
**Actor Principal:** Director Nacional  

**Flujo Principal:**
1. Director inicia sesión
2. Sistema muestra dashboard ejecutivo automáticamente
3. Director revisa KPIs del día:
   - 487 colaboradores activos
   - Asistencia hoy: 94.2% (459/487)
   - 12 solicitudes pendientes
   - Cumplimiento de horarios: 88.5%
4. Director observa alerta: "Ausentismo Dpto. Soporte: 18.5% (alto)"
5. Director hace clic en alerta
6. Sistema muestra detalle del departamento Soporte
7. Director ve que hay 8 ausencias justificadas (permisos médicos)
8. Director toma nota mental para seguimiento con Jefe de Soporte

---

#### CU-DIR-002: Generar Reporte Mensual para Junta Directiva
**Actor Principal:** Director Nacional  

**Flujo Principal:**
1. Director accede a "Reportes Ejecutivos"
2. Selecciona "Reporte Mensual Consolidado"
3. Selecciona período: Enero 2026
4. Click en "Generar PDF Ejecutivo"
5. Sistema genera reporte de 15 páginas con:
   - Resumen ejecutivo (1 página)
   - Indicadores clave (2 páginas)
   - Análisis por departamento (5 páginas)
   - Tendencias y comparativas (4 páginas)
   - Recomendaciones automáticas (3 páginas)
6. Director descarga PDF
7. Director envía PDF a Junta Directiva

---

## 3.3 JEFE DE DEPARTAMENTO

### 3.3.1 Descripción del Rol
Responsable de supervisar uno o más equipos dentro de su departamento. Requiere visibilidad completa de su área y capacidad de generar reportes para gestión operativa.

### 3.3.2 Requisitos Funcionales

#### RF-JEFE-001: Dashboard de Departamento
**Prioridad:** Alta  
**Descripción:** Vista consolidada de su departamento.

**Criterios de Aceptación:**
- KPIs del departamento:
  - Total de operadores en el departamento
  - Asistencia del día
  - Tasa de cumplimiento del departamento
  - Solicitudes pendientes de sus equipos
- Vista de todos los equipos del departamento
- Gráfico de cumplimiento por equipo
- Lista de operadores ausentes hoy
- Lista de operadores con retrasos hoy

---

#### RF-JEFE-002: Vista de Horarios del Departamento
**Prioridad:** Alta  
**Descripción:** Consultar horarios de todos sus operadores.

**Criterios de Aceptación:**
- Ver horarios de todos los equipos
- Filtrar por equipo
- Vista semanal/mensual
- Ver horario individual de cualquier operador
- Exportar horarios a Excel/PDF
- Sin capacidad de edición

---

#### RF-JEFE-003: Vista de Solicitudes del Departamento
**Prioridad:** Alta  
**Descripción:** Consultar todas las solicitudes de su departamento.

**Criterios de Aceptación:**
- Ver todas las solicitudes de sus equipos
- Filtrar por estado (pendiente, aprobado, rechazado)
- Filtrar por tipo
- Filtrar por equipo
- Ver detalle de cada solicitud
- Ver quién aprobó/rechazó
- Sin capacidad de aprobar (eso lo hacen coordinadores)

---

#### RF-JEFE-004: Reportes de Departamento
**Prioridad:** Alta  
**Descripción:** Generar reportes de su área.

**Criterios de Aceptación:**
- Reporte de asistencia (departamento completo)
- Reporte de cumplimiento por equipo
- Reporte de puntualidad
- Reporte de ausentismo
- Comparativa entre equipos
- Exportar a Excel, PDF
- Programar envío automático semanal

---

#### RF-JEFE-005: Vista de Asistencia en Tiempo Real
**Prioridad:** Media  
**Descripción:** Ver quién está presente ahora.

**Criterios de Aceptación:**
- Ver todos los operadores del departamento
- Estado en tiempo real (presente, ausente, tarde)
- Hora de entrada de cada operador
- Filtrar por equipo
- Actualización automática cada 5 minutos

---

### 3.3.3 Casos de Uso - Jefe de Departamento

#### CU-JEFE-001: Revisar Asistencia del Día
**Actor Principal:** Jefe de Departamento  

**Flujo Principal:**
1. Jefe inicia sesión a las 8:00 AM
2. Sistema muestra dashboard de su departamento "Atención al Cliente"
3. Jefe ve resumen de asistencia:
   - Departamento: Atención al Cliente
   - Operadores totales: 120
   - Presentes: 108
   - Ausentes: 7
   - Tarde: 5
4. Jefe hace clic en "Ver ausentes"
5. Sistema muestra lista de 7 operadores ausentes:
   - 4 con permiso aprobado (vacaciones)
   - 2 con permiso médico
   - 1 sin justificación
6. Jefe identifica al operador sin justificación
7. Jefe contacta al coordinador del equipo para seguimiento

---

#### CU-JEFE-002: Generar Reporte Semanal de Cumplimiento
**Actor Principal:** Jefe de Departamento  

**Flujo Principal:**
1. Jefe accede a "Reportes"
2. Selecciona "Reporte de Cumplimiento"
3. Configura:
   - Período: Última semana
   - Departamento: Mi departamento (auto-seleccionado)
   - Agrupar por: Equipo
   - Formato: PDF
4. Click "Generar"
5. Sistema genera reporte mostrando:
   - Equipo A: 92% cumplimiento
   - Equipo B: 88% cumplimiento
   - Equipo C: 85% cumplimiento
   - Equipo D: 79% cumplimiento (bajo)
6. Jefe identifica que Equipo D tiene problemas
7. Jefe agenda reunión con Coordinador del Equipo D

---

#### CU-JEFE-003: Comparar Desempeño entre Equipos
**Actor Principal:** Jefe de Departamento  

**Flujo Principal:**
1. Jefe accede a dashboard
2. Revisa gráfico de "Cumplimiento por Equipo"
3. Observa tendencia decreciente en Equipo C
4. Hace clic en Equipo C
5. Sistema muestra detalle:
   - Cumplimiento mes actual: 85%
   - Cumplimiento mes anterior: 91%
   - Tendencia: ↓ 6 puntos
6. Jefe hace clic en "Ver operadores del equipo"
7. Sistema ordena operadores por cumplimiento ascendente
8. Jefe identifica 3 operadores con cumplimiento < 70%
9. Jefe solicita al coordinador plan de mejora

---

## 3.4 COORDINADOR (Supervisor de Equipo)

### 3.4.1 Descripción del Rol
Responsable directo de un equipo de operadores. Gestiona horarios de su equipo, aprueba/rechaza solicitudes y supervisa la asistencia diaria.

### 3.4.2 Requisitos Funcionales

#### RF-COORD-001: Dashboard de Equipo
**Prioridad:** Alta  
**Descripción:** Vista consolidada de su equipo.

**Criterios de Aceptación:**
- KPIs del equipo:
  - Total de operadores en su equipo
  - Presentes hoy / Total
  - Solicitudes pendientes de aprobación
  - Operadores con retrasos hoy
- Lista rápida de su equipo
- Acceso rápido a solicitudes pendientes
- Vista de horarios de la semana

---

#### RF-COORD-002: Gestión de Horarios del Equipo
**Prioridad:** Alta  
**Descripción:** Ver horarios de su equipo.

**Criterios de Aceptación:**
- Ver horarios semanales del equipo (vista calendario)
- Ver horario individual de cada operador
- Filtrar por fecha
- Exportar horarios a PDF
- Enviar horario por email a operador
- **NO puede crear ni editar horarios** (eso lo hace WFM)

---

#### RF-COORD-003: Aprobación de Solicitudes
**Prioridad:** Alta  
**Descripción:** Aprobar/rechazar solicitudes de su equipo.

**Criterios de Aceptación:**
- Ver solicitudes pendientes de su equipo
- Ver detalle completo de cada solicitud
- Aprobar solicitud con notas opcionales
- Rechazar solicitud con razón obligatoria
- Aprobación individual
- Aprobación masiva (seleccionar múltiples)
- Notificación automática al operador
- Ver historial de solicitudes aprobadas/rechazadas

**Tipos de solicitudes que aprueba:**
- Cambio de turno
- Día libre
- Permiso personal (< 3 días)
- **NO aprueba:** Vacaciones (lo hace RRHH)

---

#### RF-COORD-004: Vista de Asistencia del Equipo
**Prioridad:** Alta  
**Descripción:** Monitorear asistencia de su equipo.

**Criterios de Aceptación:**
- Ver asistencia del día en tiempo real
- Ver quién ha marcado entrada
- Ver quién llegó tarde
- Ver quién está ausente
- Ver justificaciones de ausencias
- Filtrar por fecha (histórico)
- Exportar reporte de asistencia

---

#### RF-COORD-005: Gestión de Cambios de Turno
**Prioridad:** Alta  
**Descripción:** Aprobar intercambios de turno entre operadores.

**Criterios de Aceptación:**
- Ver solicitud de cambio con ambos involucrados
- Ver horarios originales de ambos
- Ver horarios propuestos después del cambio
- Aprobar cambio (se intercambian horarios automáticamente)
- Rechazar cambio con justificación
- Validar que el intercambio sea equitativo
- Notificar a ambos operadores

---

#### RF-COORD-006: Reportes de Equipo
**Prioridad:** Media  
**Descripción:** Generar reportes básicos de su equipo.

**Criterios de Aceptación:**
- Reporte de asistencia semanal
- Reporte de puntualidad
- Reporte de solicitudes del mes
- Exportar a Excel/PDF
- Enviar por email

---

### 3.4.3 Casos de Uso - Coordinador

#### CU-COORD-001: Aprobar Solicitudes de Permiso
**Actor Principal:** Coordinador  

**Flujo Principal:**
1. Coordinador inicia sesión
2. Sistema muestra badge: "5 solicitudes pendientes"
3. Coordinador hace clic en badge
4. Sistema muestra lista de solicitudes:
   ```
   - María López - Día libre - 15 Feb 2026
   - Juan Pérez - Cambio de turno - 18 Feb 2026
   - Ana García - Permiso personal - 20-21 Feb 2026
   - Carlos Ruiz - Día libre - 22 Feb 2026
   - Laura Torres - Cambio de turno - 25 Feb 2026
   ```
5. Coordinador hace clic en primera solicitud
6. Sistema muestra detalle:
   ```
   Operador: María López
   Tipo: Día libre
   Fecha: 15 de febrero 2026
   Razón: Trámite personal urgente
   Fecha de solicitud: 28 enero 2026
   ```
7. Coordinador revisa que no afecte cobertura
8. Coordinador hace clic en "Aprobar"
9. Sistema muestra diálogo: "¿Agregar nota de aprobación? (opcional)"
10. Coordinador escribe: "Aprobado. Confirmar retorno el 16/02"
11. Coordinador confirma
12. Sistema aprueba solicitud
13. Sistema envía email a María López
14. Sistema muestra: "Solicitud aprobada exitosamente"
15. Coordinador revisa siguiente solicitud

**Flujo Alternativo 8a: Rechazar Solicitud**
- Coordinador hace clic en "Rechazar"
- Sistema solicita razón (obligatorio)
- Coordinador escribe: "No hay cobertura suficiente ese día. Proponer otra fecha"
- Coordinador confirma
- Sistema rechaza y notifica a operador

---

#### CU-COORD-002: Aprobar Cambio de Turno
**Actor Principal:** Coordinador  

**Flujo Principal:**
1. Coordinador ve solicitud de cambio de turno
2. Hace clic para ver detalle
3. Sistema muestra:
   ```
   SOLICITUD DE CAMBIO DE TURNO
   
   Operador A: Juan Pérez
   Fecha: 18 febrero 2026
   Horario actual: 7:00 AM - 3:00 PM
   3:00 PM
   
   Operador B: Pedro Sánchez
   Fecha: 18 febrero 2026
   Horario actual: 2:00 PM - 10:00 PM
   
   DESPUÉS DEL CAMBIO:
   Juan Pérez: 2:00 PM - 10:00 PM
   Pedro Sánchez: 7:00 AM - 3:00 PM
   
   Razón: Juan tiene cita médica en la mañana
   ```
4. Coordinador verifica que ambos operadores estén capacitados para ambos turnos
5. Coordinador hace clic en "Aprobar Cambio"
6. Sistema intercambia horarios automáticamente
7. Sistema notifica a Juan Pérez y Pedro Sánchez
8. Sistema muestra: "Cambio de turno aprobado. Horarios actualizados"

---

#### CU-COORD-003: Monitorear Asistencia del Día
**Actor Principal:** Coordinador  

**Flujo Principal:**
1. Coordinador accede a "Asistencia de Mi Equipo" a las 7:30 AM
2. Sistema muestra resumen:
   ```
   EQUIPO: Ventas Mañana A
   
   Total operadores: 25
   Presentes: 18
   Ausentes: 4 (3 justificados, 1 sin justificar)
   Tarde: 3
   
   Sin marcar entrada aún: 7 (horario 7:00 AM)
   ```
3. Coordinador hace clic en "Ver tarde"
4. Sistema muestra:
   ```
   - Ana Martínez: Entrada 7:15 AM (15 min tarde)
   - Luis Gómez: Entrada 7:22 AM (22 min tarde)
   - Sofia Ruiz: Entrada 7:08 AM (8 min tarde)
   ```
5. Coordinador toma nota de Luis Gómez (22 min)
6. Coordinador hace clic en "Ver ausentes sin justificar"
7. Sistema muestra:
   ```
   - Roberto Silva: Sin marcar entrada. Sin solicitud aprobada.
   ```
8. Coordinador llama a Roberto Silva para verificar situación

---

#### CU-COORD-004: Generar Reporte Semanal de Asistencia
**Actor Principal:** Coordinador  

**Flujo Principal:**
1. Coordinador accede a "Reportes"
2. Selecciona "Reporte de Asistencia"
3. Configura:
   - Período: Última semana (27 ene - 2 feb)
   - Equipo: Mi equipo (auto-seleccionado)
   - Formato: Excel
4. Click "Generar"
5. Sistema descarga Excel con:
   - Hoja 1: Resumen (asistencia por día)
   - Hoja 2: Detalle por operador
   - Hoja 3: Retrasos registrados
   - Hoja 4: Ausencias
6. Coordinador abre archivo
7. Coordinador lo adjunta a email para Jefe de Departamento

---

## 3.5 OPERADOR

### 3.5.1 Descripción del Rol
Usuario final del sistema. Consulta su horario, marca asistencia y solicita cambios de turno o permisos.

### 3.5.2 Requisitos Funcionales

#### RF-OP-001: Vista de Mi Horario
**Prioridad:** Alta  
**Descripción:** Consultar su horario asignado.

**Criterios de Aceptación:**
- Ver horario semanal (vista calendario)
- Ver horario mensual
- Ver detalle de cada día:
  - Hora de entrada
  - Hora de descanso
  - Hora de almuerzo
  - Hora de salida
- Recibir notificación por email cuando se asigne horario
- Descargar horario en PDF
- Vista mobile-friendly

---

#### RF-OP-002: Solicitar Cambio de Turno
**Prioridad:** Alta  
**Descripción:** Solicitar intercambio de turno con otro operador.

**Criterios de Aceptación:**
- Seleccionar fecha a cambiar
- Buscar operador disponible para cambio
- Sistema muestra solo operadores del mismo equipo
- Ver horario del otro operador
- Enviar solicitud al otro operador
- Otro operador debe aceptar
- Una vez aceptado, va a coordinador para aprobación
- Notificación del estado de la solicitud
- Puede cancelar antes de aprobación

---

#### RF-OP-003: Solicitar Día Libre / Permiso
**Prioridad:** Alta  
**Descripción:** Solicitar permiso o día libre.

**Criterios de Aceptación:**
- Seleccionar tipo de permiso:
  - Día libre
  - Permiso personal
  - Permiso médico
- Seleccionar fecha(s)
- Para permiso parcial: seleccionar hora inicio y fin
- Escribir razón (mínimo 20 caracteres)
- Adjuntar documento (opcional, ej: certificado médico)
- Enviar solicitud
- Recibir notificación cuando sea aprobada/rechazada
- Ver historial de solicitudes

---

#### RF-OP-004: Solicitar Vacaciones
**Prioridad:** Alta  
**Descripción:** Solicitar período de vacaciones.

**Criterios de Aceptación:**
- Ver saldo de días de vacaciones disponibles
- Seleccionar fecha de inicio
- Seleccionar fecha de fin
- Sistema calcula días a utilizar (días laborables)
- Validar que no exceda saldo disponible
- Escribir comentario opcional
- Enviar solicitud a RRHH
- Recibir notificación de aprobación/rechazo

---

#### RF-OP-005: Marcar Asistencia
**Prioridad:** Alta  
**Descripción:** Registrar entrada y salida del trabajo.

**Criterios de Aceptación:**
- Botón "Marcar Entrada" visible al llegar
- Sistema registra fecha y hora exacta
- Sistema compara con horario programado
- Si llega tarde, sistema calcula minutos de retraso
- Botón "Marcar Salida" cuando termina jornada
- Sistema calcula horas trabajadas
- Ver resumen del día (entrada, salida, horas trabajadas)
- Funciona en mobile

**Nota:** Esto puede ser manual (botón en el sistema) o automático (biométrico integrado).

---

#### RF-OP-006: Ver Historial de Asistencia
**Prioridad:** Media  
**Descripción:** Consultar su propio historial.

**Criterios de Aceptación:**
- Ver asistencia de últimos 30 días
- Ver detalle de cada día:
  - Hora de entrada (real)
  - Hora de salida (real)
  - Horas trabajadas
  - Estado (presente, tarde, ausente)
- Ver días con retraso
- Ver justificaciones
- Descargar historial en PDF

---

#### RF-OP-007: Ver Estado de Solicitudes
**Prioridad:** Media  
**Descripción:** Consultar solicitudes realizadas.

**Criterios de Aceptación:**
- Ver todas las solicitudes hechas
- Filtrar por estado:
  - Pendiente
  - Aprobada
  - Rechazada
- Ver detalle de cada solicitud
- Ver quién aprobó/rechazó
- Ver notas del aprobador
- Cancelar solicitud pendiente

---

#### RF-OP-008: Notificaciones
**Prioridad:** Alta  
**Descripción:** Recibir alertas importantes.

**Criterios de Aceptación:**
- Notificación cuando se asigna horario nuevo
- Notificación cuando solicitud es aprobada
- Notificación cuando solicitud es rechazada
- Notificación de cambio en horario
- Recordatorio de turno (1 día antes)
- Vía email
- Badge en sistema con contador

---

### 3.5.3 Casos de Uso - Operador

#### CU-OP-001: Consultar Horario de la Semana
**Actor Principal:** Operador  

**Flujo Principal:**
1. Operador inicia sesión
2. Sistema muestra dashboard del operador
3. Operador hace clic en "Mi Horario"
4. Sistema muestra calendario semanal:
   ```
   SEMANA: 3 - 9 de Febrero 2026
   
   Lunes 3:    7:00 AM - 3:00 PM (Descanso 10:00, Almuerzo 12:00)
   Martes 4:   7:00 AM - 3:00 PM (Descanso 10:00, Almuerzo 12:00)
   Miércoles 5: 7:00 AM - 3:00 PM (Descanso 10:00, Almuerzo 12:00)
   Jueves 6:   7:00 AM - 3:00 PM (Descanso 10:00, Almuerzo 12:00)
   Viernes 7:  7:00 AM - 3:00 PM (Descanso 10:00, Almuerzo 12:00)
   Sábado 8:   DÍA LIBRE
   Domingo 9:  DÍA LIBRE
   ```
5. Operador hace clic en "Descargar PDF"
6. Sistema genera PDF con horario
7. Operador descarga PDF

---

#### CU-OP-002: Solicitar Día Libre
**Actor Principal:** Operador  

**Flujo Principal:**
1. Operador accede a "Mis Solicitudes"
2. Hace clic en "Nueva Solicitud"
3. Selecciona tipo: "Día Libre"
4. Selecciona fecha: 15 de febrero 2026
5. Escribe razón: "Trámite personal urgente en oficina gubernamental"
6. Hace clic en "Enviar Solicitud"
7. Sistema valida:
   - Fecha es futura ✓
   - No tiene otra solicitud para esa fecha ✓
   - Razón tiene más de 20 caracteres ✓
8. Sistema crea solicitud con estado "Pendiente"
9. Sistema envía notificación a coordinador
10. Sistema muestra mensaje: "Solicitud enviada. Será revisada por tu coordinador"
11. Operador recibe email de confirmación

**Postcondiciones:**
- Solicitud creada en base de datos
- Coordinador notificado
- Operador puede ver solicitud en "Mis Solicitudes"

---

#### CU-OP-003: Solicitar Cambio de Turno
**Actor Principal:** Operador (Juan Pérez)  
**Actor Secundario:** Operador (Pedro Sánchez)

**Flujo Principal:**
1. Juan Pérez accede a "Mis Solicitudes"
2. Hace clic en "Solicitar Cambio de Turno"
3. Selecciona fecha: 18 de febrero 2026
4. Sistema muestra su horario ese día: 7:00 AM - 3:00 PM
5. Juan hace clic en "Buscar operador para cambiar"
6. Sistema muestra operadores de su mismo equipo con horario ese día:
   ```
   - Pedro Sánchez: 2:00 PM - 10:00 PM
   - María González: 7:00 AM - 3:00 PM (mismo horario, no sirve)
   - Luis Ramírez: Día libre (no disponible)
   ```
7. Juan selecciona a Pedro Sánchez
8. Juan escribe razón: "Tengo cita médica en la mañana"
9. Juan hace clic en "Enviar Solicitud"
10. Sistema envía notificación a Pedro Sánchez
11. Pedro Sánchez inicia sesión
12. Pedro ve notificación: "Juan Pérez quiere cambiar turno contigo"
13. Pedro hace clic en notificación
14. Sistema muestra detalle del cambio propuesto
15. Pedro hace clic en "Aceptar"
16. Sistema envía solicitud al coordinador para aprobación
17. Coordinador aprueba (ver CU-COORD-002)
18. Sistema intercambia horarios
19. Juan y Pedro reciben notificación: "Cambio de turno aprobado"

**Flujo Alternativo 15a: Pedro Rechaza**
- Pedro hace clic en "Rechazar"
- Sistema cancela solicitud
- Juan recibe notificación: "Pedro Sánchez rechazó el cambio de turno"

---

#### CU-OP-004: Marcar Entrada al Trabajo
**Actor Principal:** Operador  

**Precondiciones:**
- Operador tiene horario asignado para hoy
- Operador tiene acceso a internet

**Flujo Principal:**
1. Operador llega al trabajo a las 7:05 AM
2. Operador abre app en su teléfono
3. Operador hace clic en "Marcar Entrada"
4. Sistema registra:
   - Fecha: 3 febrero 2026
   - Hora: 7:05 AM
5. Sistema compara con horario programado:
   - Horario: 7:00 AM
   - Retraso: 5 minutos
6. Sistema marca status: "Tarde" (tolerancia es 10 min)
7. Sistema muestra mensaje: "Entrada registrada: 7:05 AM (5 min de retraso)"
8. Operador ve resumen del día:
   ```
   Entrada: 7:05 AM
   Descanso programado: 10:00 AM
   Almuerzo programado: 12:00 PM
   Salida programada: 3:00 PM
   ```

**Flujo Alternativo 1a: Operador llega muy tarde (> 15 min)**
- Sistema marca status: "Ausente"
- Notifica al coordinador automáticamente

---

#### CU-OP-005: Solicitar Vacaciones
**Actor Principal:** Operador  

**Flujo Principal:**
1. Operador accede a "Mis Solicitudes"
2. Hace clic en "Solicitar Vacaciones"
3. Sistema muestra saldo: "Tienes 15 días de vacaciones disponibles"
4. Operador selecciona:
   - Fecha inicio: 1 de marzo 2026
   - Fecha fin: 15 de marzo 2026
5. Sistema calcula: "Período solicitado: 11 días laborables (excluye sábados y domingos)"
6. Sistema valida: 11 días ≤ 15 días disponibles ✓
7. Operador escribe comentario: "Vacaciones anuales programadas"
8. Operador hace clic en "Enviar Solicitud"
9. Sistema crea solicitud
10. Sistema envía notificación a RRHH
11. Sistema muestra: "Solicitud enviada a Recursos Humanos"
12. RRHH revisa y aprueba
13. Operador recibe email: "Tu solicitud de vacaciones fue aprobada"
14. Sistema actualiza saldo: "Saldo actual: 4 días disponibles"

---

## 4. REQUISITOS NO FUNCIONALES

### 4.1 Rendimiento
- Tiempo de carga de dashboard: < 2 segundos
- Tiempo de generación de reportes (1000 registros): < 5 segundos
- Importación CSV (500 registros): < 30 segundos
- Soporte para 500+ usuarios concurrentes

### 4.2 Seguridad
- Autenticación mediante email/password
- Sesiones con timeout de 8 horas
- Contraseñas encriptadas (bcrypt)
- Logs de auditoría para cambios críticos
- Acceso basado en roles (Spatie Permission)
- HTTPS obligatorio en producción

### 4.3 Usabilidad
- Interfaz responsive (mobile, tablet, desktop)
- Soporte para navegadores modernos (Chrome, Firefox, Safari, Edge)
- Mensajes de error claros y en español
- Confirmaciones para acciones destructivas
- Tooltips y ayuda contextual

### 4.4 Disponibilidad
- Disponibilidad: 99.5% (permitiendo mantenimiento programado)
- Backup diario automático
- Recuperación ante desastres (RTO: 4 horas)

### 4.5 Escalabilidad
- Diseño modular para agregar nuevos departamentos
- Capacidad de crecimiento a 2000+ usuarios
- Base de datos optimizada con índices

---

## 5. MATRIZ DE PERMISOS

| Funcionalidad              | Analista WFM | Director | Jefe | Coordinador | Operador |
| -------------------------- | ------------ | -------- | ---- | ----------- | -------- |
| **Usuarios y Estructura**  |
| Crear/editar usuarios      | ✓            | ✗        | ✗    | ✗           | ✗        |
| Crear/editar departamentos | ✓            | ✗        | ✗    | ✗           | ✗        |
| Crear/editar equipos       | ✓            | ✗        | ✗    | ✗           | ✗        |
| Ver estructura completa    | ✓            | ✓        | Dpto | Equipo      | No       |
| **Horarios**               |
| Crear plantillas           | ✓            | ✗        | ✗    | ✗           | ✗        |
| Asignar horarios           | ✓            | ✗        | ✗    | ✗           | ✗        |
| Importar CSV               | ✓            | ✗        | ✗    | ✗           | ✗        |
| Ver horarios               | Todos        | Todos    | Dpto | Equipo      | Propios  |
| Editar horarios            | ✓            | ✗        | ✗    | ✗           | ✗        |
| Descargar horario PDF      | ✓            | ✓        | ✓    | ✓           | ✓        |
| **Solicitudes**            |
| Crear solicitud            | ✓            | ✗        | ✗    | ✗           | ✓        |
| Aprobar cambio turno       | ✓            | ✗        | ✗    | ✓           | ✗        |
| Aprobar día libre          | ✓            | ✗        | ✗    | ✓           | ✗        |
| Aprobar vacaciones         | ✓            | ✗        | ✗    | ✗           | ✗        |
| Ver solicitudes            | Todas        | Todas    | Dpto | Equipo      | Propias  |
| **Asistencia**             |
| Marcar entrada/salida      | ✓            | ✗        | ✗    | ✗           | ✓        |
| Crear asistencia manual    | ✓            | ✗        | ✗    | ✗           | ✗        |
| Ver asistencia             | Todos        | Todos    | Dpto | Equipo      | Propia   |
| **Reportes**               |
| Reportes estratégicos      | ✓            | ✓        | ✗    | ✗           | ✗        |
| Reportes departamento      | ✓            | ✓        | ✓    | ✗           | ✗        |
| Reportes equipo            | ✓            | ✓        | ✓    | ✓           | ✗        |
| Reportes personales        | ✓            | ✓        | ✓    | ✓           | ✓        |
| Exportar reportes          | ✓            | ✓        | ✓    | ✓           | ✓        |

---

## 6. FLUJOS DE APROBACIÓN

### 6.1 Cambio de Turno
```
1. Operador A solicita → 2. Operador B acepta → 3. Coordinador aprueba → 4. Horarios intercambiados
```

### 6.2 Día Libre / Permiso Personal
```
1. Operador solicita → 2. Coordinador aprueba/rechaza → 3. Sistema notifica
```

### 6.3 Vacaciones
```
1. Operador solicita → 2. RRHH verifica saldo → 3. RRHH aprueba/rechaza → 4. Sistema actualiza saldo
```

### 6.4 Permiso Médico
```
1. Operador solicita + adjunta certificado → 2. Coordinador aprueba → 3. Sistema marca asistencia como justificada
```

---

## 7. CRONOGRAMA DE DESARROLLO (MVP - 6 semanas)

### Semana 1-2: Base y Autenticación
- Setup Laravel + DB
- Migraciones
- Seeders
- Autenticación (Laravel Breeze)
- Roles y permisos (Spatie)

### Semana 3: Gestión de Horarios
- CRUD plantillas
- Asignación masiva
- Importación CSV
- Vista de horarios

### Semana 4: Solicitudes y Aprobaciones
- CRUD solicitudes
- Flujo de aprobación
- Notificaciones

### Semana 5: Asistencia y Registro
- Marcar entrada/salida
- Cálculo de cumplimiento
- Vista de asistencia

### Semana 6: Reportes y Dashboard
- Dashboards por rol
- Reportes básicos
- Exportación PDF/Excel
- Testing y ajustes finales

---

## 8. CRITERIOS DE ÉXITO

✅ 100% de operadores pueden ver su horario  
✅ 95% de solicitudes procesadas en < 24 horas  
✅ Reducción del 30% en tiempo de gestión de horarios (vs Excel)  
✅ Reportes automáticos generados sin intervención manual  
✅ 0 errores en nómina por horarios incorrectos  
✅ 90% de satisfacción de usuarios (encuesta post-implementación)  

---

**Fin del Documento**
