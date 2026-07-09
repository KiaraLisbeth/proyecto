# 🎬 Guión para Video de Demostración
## Sistema de Gestión de Aprendizaje — I.E.P. Esther Carson
**Duración estimada: 6 a 7 minutos**

---

## ⏱️ SEGMENTO 1 — El Problema que Originó el Proyecto (0:00 – 1:00)

> *📸 Pantalla: Portada del proyecto, logo de la institución o una diapositiva con el título del sistema. No abras el sistema todavía.*

**LO QUE DICES:**

> "Buenas [días/tardes], mi nombre es [tu nombre]. Antes de mostrar el sistema que desarrollé, quiero explicar **el problema real que lo originó**.
>
> La Institución Educativa Privada **Esther Carson** contaba con un proceso completamente **manual** para gestionar las evidencias de trabajo de sus docentes. Cada bimestre, los docentes debían entregar físicamente sus sesiones de aprendizaje, programaciones curriculares y otros documentos en **carpetas físicas** al área de dirección.
>
> Esto generaba varios problemas: primero, **pérdida o deterioro de documentos** al no existir un respaldo digital. Segundo, el **administrador no tenía visibilidad inmediata** de qué docente había entregado y cuál no, debiendo revisar carpeta por carpeta. Tercero, era **imposible saber en qué bimestre** se encontraba cada archivo sin revisarlo manualmente. Y cuarto, si se necesitaba generar un **informe de cumplimiento**, el administrador tenía que hacerlo a mano.
>
> Frente a este problema, surgió la necesidad de desarrollar un **sistema web** que digitalizara y automatizara todo ese proceso: que los docentes suban sus archivos en línea, clasificados por bimestre y curso, y que el administrador pueda monitorear el cumplimiento en tiempo real y generar reportes con un solo clic.
>
> Ese sistema es el que voy a mostrar a continuación. Fue desarrollado con **Laravel**, aplicando la metodología ágil **Scrum** en **3 Sprints**, con un total de **21 historias de usuario** completadas."

---

## ⏱️ SEGMENTO 2 — Introducción al Sistema y Login (1:00 – 1:50)

> *📸 Pantalla: Formulario de inicio de sesión del sistema*

**LO QUE DICES:**

> "El sistema cuenta con **dos roles de usuario**: el **Administrador** y el **Docente**. Aquí vemos el formulario de inicio de sesión. El sistema utiliza encriptación **bcrypt** para las contraseñas y valida que el usuario esté activo antes de permitir el ingreso.
>
> Voy a iniciar sesión como **Administrador**."

*(Escribes las credenciales del admin y das clic en 'Ingresar')*

> "El sistema redirige automáticamente al panel correspondiente según el rol. Si iniciara sesión un docente, accedería a su propio panel con funciones diferentes. Este control por roles se implementó mediante **middlewares de Laravel**, garantizando que cada usuario solo vea lo que le corresponde."

---

## ⏱️ SEGMENTO 3 — Dashboard del Administrador (1:50 – 2:30)

> *📸 Pantalla: Dashboard con KPIs y gráficos*

**LO QUE DICES:**

> "Una vez dentro como administrador, lo primero que vemos es el **Dashboard general**. Este panel nos muestra de un vistazo el estado del sistema:
>
> - El **total de docentes activos** registrados,
> - El **total de archivos subidos** en el sistema,
> - El **promedio de archivos por docente**,
> - Los **archivos subidos en el año actual**.
>
> Además, contamos con gráficos generados con **Chart.js**: uno de barras que muestra la distribución de archivos por **bimestre**, un gráfico de dona con archivos por **nivel educativo**, y otro con los archivos por **tipo de documento**: PDF, Word, Excel y PowerPoint.
>
> También podemos ver el **top 10 de docentes** con más archivos subidos y los **últimos 8 archivos** registrados en el sistema."

---

## ⏱️ SEGMENTO 4 — Gestión de Docentes (2:30 – 3:40)

> *📸 Pantalla: Listado de docentes*

**LO QUE DICES:**

> "Ahora vamos al módulo de **Gestión de Docentes**. Aquí el administrador puede ver todos los docentes registrados, con su número de asignaciones y archivos subidos. También hay un buscador en tiempo real.
>
> Voy a registrar un nuevo docente."

*(Haces clic en 'Nuevo Docente')*

> "El formulario de registro tiene una característica especial: integración con la **API de RENIEC** a través de decolecta.com. Al ingresar el DNI del docente, el sistema consulta automáticamente el padrón nacional y autocompleta el **nombre y apellidos**, evitando errores de digitación."

*(Escribes un DNI y esperas el autocompletado)*

> "Como ven, el sistema llenó automáticamente los datos. Luego asignamos al docente sus **cursos, grados y secciones** que dictará. Esta es una relación de muchos a muchos: un docente puede tener múltiples asignaciones."

*(Guardas el docente)*

> "El docente queda registrado. También podemos **activar o desactivar** un docente sin borrarlo del sistema, conservando todo su historial de archivos. Esto se implementó con la técnica de **Soft Deletes**."

---

## ⏱️ SEGMENTO 5 — Panel del Docente: Subida de Archivos (3:40 – 4:40)

> *📸 Pantalla: Login → ingresar como docente*

**LO QUE DICES:**

> "Ahora vamos a ver el sistema desde el punto de vista del **Docente**. Cierro la sesión del administrador y entro con una cuenta de docente."

*(Cierras sesión y entras como docente)*

> "El docente tiene su propio panel con sus archivos. Para subir un nuevo archivo, el docente selecciona su **asignación** (curso, grado y sección), el **bimestre** correspondiente, y sube el archivo desde su dispositivo.
>
> El sistema acepta archivos en formato **PDF, Word, Excel y PowerPoint**, con un tamaño máximo de **50 MB** por archivo."

*(Subes un archivo de prueba)*

> "El archivo queda registrado en el sistema, asociado al año lectivo activo. El docente puede ver todos sus archivos subidos, con la posibilidad de **filtrarlos** por bimestre, curso o rango de fechas."

---

## ⏱️ SEGMENTO 6 — Previsualización, Descarga y Papelera (4:40 – 5:20)

> *📸 Pantalla: Lista de archivos del docente*

**LO QUE DICES:**

> "Desde su listado de archivos, el docente tiene varias opciones:
>
> Primero, puede **previsualizar** el documento directamente en el navegador usando el **Visor de Google Docs**, sin necesidad de descargarlo. El sistema genera una URL temporal firmada, válida por 10 minutos, para proteger el acceso.
>
> Segundo, puede **descargar** una copia local de su archivo cuando lo necesite.
>
> Y tercero, si el docente sube un archivo por error, puede enviarlo a la **Papelera de Reciclaje**, desde donde puede **restaurarlo** si se arrepiente, o **eliminarlo definitivamente** para liberar espacio."

*(Demuestras brevemente una de estas acciones)*

---

## ⏱️ SEGMENTO 7 — Reportes de Cumplimiento (5:20 – 6:20)

> *📸 Pantalla: Módulo de Reportes (volvemos al admin)*

**LO QUE DICES:**

> "Volvemos al panel del **Administrador** para ver uno de los módulos más importantes: los **Reportes de Cumplimiento**.
>
> El administrador selecciona el **año lectivo** y el **bimestre** que desea evaluar, y el sistema genera un reporte completo que muestra, docente por docente, cuántas de sus asignaciones tienen archivos subidos y cuántas están pendientes.
>
> Se calcula automáticamente el **porcentaje de cumplimiento** de cada docente y un **porcentaje global** de la institución."

*(Generas el reporte)*

> "Al hacer clic en el detalle de un docente, se abre un **modal** que muestra exactamente qué cursos, grados y secciones tienen archivos y cuáles están sin completar, lo que permite identificar rápidamente quién está incumpliendo con sus entregas.
>
> Este reporte se puede exportar en tres formatos: **PDF**, **Word** y **Excel**, para ser utilizado en informes institucionales."

*(Muestras brevemente la exportación)*

---

## ⏱️ SEGMENTO 8 — Papelera Global y Configuración (6:20 – 6:50)

> *📸 Pantalla: Papelera Global del Admin*

**LO QUE DICES:**

> "El administrador también tiene acceso a una **Papelera Global**, donde puede ver todos los archivos eliminados por cualquier docente, con la opción de **restaurarlos** o **eliminarlos definitivamente**. Esto le da control total sobre los archivos de la institución.
>
> Finalmente, en el módulo de **Configuración**, el administrador puede actualizar los **datos institucionales** como el nombre y logo de la institución, que aparecen en todos los reportes generados. También puede actualizar su **perfil** y **cambiar su contraseña** de forma segura."

---

## ⏱️ SEGMENTO 9 — Cierre (6:50 – 7:30)

> *📸 Pantalla: Dashboard del admin o logo del proyecto*

**LO QUE DICES:**

> "Para concluir, el **Sistema de Gestión de Aprendizaje** de la I.E.P. Esther Carson fue desarrollado con **Laravel 10**, **MySQL** como base de datos, y **Blade** para las vistas. Se utilizó **AWS S3** para el almacenamiento seguro de archivos en la nube.
>
> La metodología **Scrum** nos permitió entregar el sistema de forma incremental: en el **Sprint 1** implementamos la autenticación y la gestión académica básica; en el **Sprint 2**, la subida y gestión de archivos del docente; y en el **Sprint 3**, la auditoría, reportes y configuración del sistema.
>
> El sistema cumple con todas las **21 historias de usuario** planificadas, todas con estado **Hecho**. Gracias por su atención."

---

## 📋 RESUMEN DE TIEMPOS

| Segmento | Descripción | Tiempo |
|---|---|---|
| **1** | **🔴 El Problema que Originó el Proyecto** | 0:00 – 1:00 |
| 2 | Introducción al sistema y Login | 1:00 – 1:50 |
| 3 | Dashboard Admin (KPIs + gráficos) | 1:50 – 2:30 |
| 4 | Gestión de Docentes + API RENIEC | 2:30 – 3:40 |
| 5 | Panel Docente + Subida de archivos | 3:40 – 4:40 |
| 6 | Previsualizar, Descargar y Papelera | 4:40 – 5:20 |
| 7 | Reportes de cumplimiento + Exportar | 5:20 – 6:20 |
| 8 | Papelera global + Configuración | 6:20 – 6:50 |
| 9 | Cierre | 6:50 – 7:30 |

---

## 💡 CONSEJOS PARA GRABAR

- **Prepara datos de prueba** antes de grabar: ten docentes ya cargados, archivos subidos y al menos un bimestre con archivos para que los reportes muestren información real.
- **Usa OBS Studio** o la grabación de pantalla de Windows (Win + G) para grabar.
- **Graba a resolución 1080p** y sin notificaciones del sistema.
- **Habla despacio y con claridad.** No tienes que decir todo exactamente igual al guión; usa tus propias palabras si te sientes más cómodo.
- Si cometes un error al grabar, **pausa, respira y continúa** desde ese punto. Puedes editar luego.
- Silencia tu celular y cierra aplicaciones que generen notificaciones.
