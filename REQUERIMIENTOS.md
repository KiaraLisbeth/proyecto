# Requerimientos del Sistema de Gestión Académica y Portafolio Docente

Este documento detalla los requerimientos esenciales del sistema de gestión académica y portafolio docente para la institución educativa, basado en el análisis de la base de datos, los controladores, las vistas y el flujo de trabajo del proyecto.

---

## 1. Visión General del Sistema

El sistema es una plataforma web desarrollada en **Laravel** que permite a una institución educativa organizar su estructura académica (niveles, grados, secciones y cursos), gestionar el personal docente con sus respectivas asignaciones académicas, y servir como un portafolio o repositorio de archivos didácticos. Los docentes suben materiales clasificados por bimestre y año lectivo, mientras que la administración supervisa, previsualiza y gestiona globalmente dichos archivos.

---

## 2. Requerimientos Funcionales (RF)

### 🔐 RF1: Gestión de Autenticación, Roles y Perfiles
El sistema debe proporcionar un acceso seguro y restringido según el rol del usuario autenticado.
*   **RF1.1 Autenticación:** Inicio de sesión único para usuarios registrados mediante nombre de usuario (`username`) y contraseña.
*   **RF1.2 Control de Estado de Usuario:** Si un usuario es marcado como "Inactivo" (`activo = false`) por el administrador, el sistema debe denegarle el acceso inmediatamente.
*   **RF1.3 Perfiles de Usuario:**
    *   **Administrador (`admin`):** Posee control total sobre la configuración de la institución, la estructura académica, el personal docente, los periodos académicos y la supervisión de archivos.
    *   **Docente (`docente`):** Puede ver únicamente sus asignaciones académicas y gestionar sus propios archivos.
*   **RF1.4 Actualización de Perfil:** Cada usuario debe poder modificar sus datos básicos (nombre, apellido, username) y actualizar su contraseña de forma segura.

---

### 🏫 RF2: Estructura Académica e Institucional
El administrador debe ser capaz de configurar la estructura física y curricular de la institución.
*   **RF2.1 Gestión de Niveles:** Registrar, actualizar y eliminar niveles de enseñanza (ej. *Inicial*, *Primaria*, *Secundaria*).
*   **RF2.2 Gestión de Grados:** Registrar y gestionar grados, vinculándolos obligatoriamente a un nivel educativo (ej. *1er Grado* en el nivel *Primaria*).
*   **RF2.3 Gestión de Secciones:** Crear y administrar secciones (ej. *A*, *B*, *C*, o *Única*) para subdividir los grados.
*   **RF2.4 Gestión de Cursos:** Crear, actualizar y eliminar los cursos/materias que forman parte de la currícula escolar.
*   **RF2.5 Gestión de Años Lectivos:**
    *   Registrar años escolares (ej. *Año Lectivo 2026*).
    *   Permitir activar un único año lectivo. Las operaciones de carga y consulta por defecto se realizan en base al año lectivo activo.
*   **RF2.6 Mapa Académico:** Mostrar una vista jerárquica de Niveles ➔ Grados ➔ Cursos con sus respectivos docentes asignados para tener una visión consolidada de la distribución del colegio.

---

### 👤 RF3: Gestión y Asignación de Docentes
El administrador controla el registro de los profesores y sus responsabilidades de enseñanza.
*   **RF3.1 Registro de Docentes:** Crear nuevos registros de profesores especificando nombre, apellido, usuario y contraseña. El correo electrónico se autogenera con el formato `{username}@docente.local`.
*   **RF3.2 Control de Acceso del Docente (Activar/Desactivar):** Habilitar o deshabilitar el estado activo del docente mediante un botón de alternancia (*toggle*), evitando eliminar físicamente al docente para mantener la integridad referencial de los archivos.
*   **RF3.3 Asignación de Cursos y Aulas:** Vincular a un docente con una o múltiples combinaciones de **[Curso + Grado + Sección]**.
*   **RF3.4 Sincronización de Asignaciones:** Permitir actualizar el perfil del docente y rehacer dinámicamente todas sus asignaciones en un único formulario (eliminando las previas y creando las nuevas).

---

### 📁 RF4: Gestión de Archivos y Portafolio Docente
El núcleo del sistema es la carga, organización y previsualización de documentos educativos.

#### Funciones del Docente:
*   **RF4.1 Carga de Archivos:** Subir documentos asociándolos a una de sus asignaciones académicas vigentes. Cada archivo requiere:
    *   Archivo físico (PDF, Word, imágenes, etc.).
    *   Asignación seleccionada (que determina el Curso, Grado y Sección).
    *   Bimestre correspondiente (I, II, III o IV Bimestre).
    *   Descripción opcional.
    *   El año se asocia automáticamente con el año actual en curso.
*   **RF4.2 Listado y Búsqueda de Archivos Propios:** Ver los archivos subidos, paginados y ordenados por bimestre. Se permite filtrar por año lectivo, bimestre, curso y rango de fechas de creación.
*   **RF4.3 Descarga y Stream Personal:** Descargar el archivo con su nombre original o abrirlo (*stream*) directamente en el navegador de forma segura.
*   **RF4.4 Papelera de Reciclaje (Docente):**
    *   Mover archivos a la papelera (Soft Delete), ocultándolos del listado principal.
    *   Restaurar archivos desde la papelera para devolverlos a su estado original.
    *   Eliminar permanentemente archivos de la papelera, borrando tanto el registro de la base de datos como el archivo físico almacenado en el disco.

#### Funciones del Administrador:
*   **RF4.5 Consola de Supervisión General:** Buscar y visualizar todos los archivos subidos por todos los docentes de la institución.
*   **RF4.6 Filtrado Avanzado:** Buscar documentos por docente, año lectivo, bimestre, curso, grado, sección, nivel y rango de fechas.
*   **RF4.7 Descarga y Stream Administrativo:** Descargar y visualizar cualquier archivo del sistema.
*   **RF4.8 Previsualización Externa con Firma Temporal:** Generar una **URL firmada criptográficamente** válida únicamente durante **10 minutos**. Esta URL permite que visores externos (como Google Docs Viewer) carguen y rendericen el documento de manera pública y segura sin exponer las sesiones de usuario.
*   **RF4.9 Papelera Administrativa:** Visualizar todos los archivos eliminados por los docentes, con la potestad de restaurarlos o forzar su eliminación definitiva del servidor.

---

### ⚙️ RF5: Configuración de la Institución
Permite personalizar la marca y datos informativos del centro educativo.
*   **RF5.1 Modificación de Información:** Registrar y editar datos de la escuela como Nombre, Dirección, Teléfono, Correo Electrónico y Sitio Web.
*   **RF5.2 Logotipo Institucional:** Subir un logotipo en formatos de imagen comunes (.jpg, .png, .webp) que se almacena y muestra dinámicamente en los layouts de administración y docentes.
*   **RF5.3 Persistencia en Archivo Plano:** Los datos de la institución se guardan localmente en un archivo JSON (`config/institucion.json`), optimizando el tiempo de respuesta y evitando lecturas innecesarias en base de datos.

---

## 3. Requerimientos No Funcionales (RNF)

*   **RNF1 Seguridad y Privacidad:**
    *   Las contraseñas de los usuarios deben guardarse encriptadas mediante algoritmos de hashing fuertes (bcrypt/Laravel Hash).
    *   Los archivos subidos por los docentes se guardan en el disco público de Laravel de forma estructurada (`storage/app/public/docentes/{user_id}/`) y el acceso por sesión se valida en el controlador para impedir que un docente descargue o borre archivos de otro.
    *   El acceso a archivos temporales para visores externos debe vencer a los 10 minutos para mitigar riesgos de filtración.
*   **RNF2 Usabilidad y Rendimiento:**
    *   Toda consulta masiva de datos (docentes, archivos, papelera) debe estar paginada (entre 15 y 20 elementos por página) para evitar problemas de memoria y lentitud en la carga.
    *   La interfaz debe ser amigable, intuitiva y responsiva, utilizando estilos coherentes y componentes HTML semánticos en conjunto con motores de plantillas Blade.
*   **RNF3 Confiabilidad e Integridad:**
    *   Uso de integridad referencial a nivel base de datos (claves foráneas) entre las tablas de usuarios, cursos, grados, secciones y archivos.
    *   Implementación de eliminaciones lógicas (`SoftDeletes`) en la tabla de archivos para posibilitar la recuperación en caso de borrado accidental por parte de los profesores.
*   **RNF4 Portabilidad y Mantenibilidad:**
    *   El backend debe estar desarrollado en PHP utilizando el framework Laravel, permitiendo una fácil escalabilidad de controladores y modelos.
    *   Los estilos deben estructurarse de manera modular en las vistas o mediante hojas de estilo CSS organizadas.
