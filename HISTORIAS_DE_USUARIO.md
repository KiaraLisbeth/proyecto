# Historias de Usuario - Sistema de Gestión Escolar

<table border="1" style="width:100%; border-collapse: collapse; text-align: left; font-size: 0.9em;">
  <thead>
    <tr style="background-color: #1f3864; color: white; text-align: center;">
      <th colspan="4">ÉPICA</th>
      <th colspan="4">HISTORIA DE USUARIO</th>
      <th colspan="7">OTROS DATOS DE LA ÉPICA O HISTORIA DE USUARIO</th>
    </tr>
    <tr style="background-color: #d9e1f2; text-align: center;">
      <th>ID Épica</th>
      <th>Como (Rol)</th>
      <th>Deseo...</th>
      <th>Para...</th>
      <th>ID Historia de Usuario</th>
      <th>Como (Rol)...</th>
      <th>Deseo</th>
      <th>Para</th>
      <th>Criterios de Aceptación (ID)</th>
      <th>Prioridad</th>
      <th>Estimación (Story Points)</th>
      <th>Dependencias</th>
      <th>Sprint</th>
      <th>Estado</th>
      <th>Comentarios</th>
    </tr>
  </thead>
  <tbody>
    <!-- EPICA 1 -->
    <tr>
      <td rowspan="3" style="text-align: center; font-weight: bold;">EP-01</td>
      <td rowspan="3">Usuario registrado</td>
      <td rowspan="3">gestionar mi sesión y acceso al sistema</td>
      <td rowspan="3">proteger la información y acceder a las funciones de mi rol</td>
      <td style="font-weight: bold;">HU-01</td>
      <td>Usuario registrado (Administrador o Docente)</td>
      <td>iniciar sesión en el sistema usando mi usuario y contraseña</td>
      <td>poder acceder a las funcionalidades correspondientes a mi rol de forma segura</td>
      <td>RNF1.1, RNF2.2, RF-01-4</td>
      <td>Alta</td>
      <td>3</td>
      <td>Ninguna</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Manejo de encriptación y estado de actividad</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-02</td>
      <td>Administrador del sistema</td>
      <td>que el sistema restrinja las vistas según los roles definidos</td>
      <td>asegurar que cada usuario solo acceda a la información y opciones permitidas para su perfil</td>
      <td>CA-01, CA-02</td>
      <td>Alta</td>
      <td>5</td>
      <td>HU-01</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Middlewares y redirecciones</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-03</td>
      <td>Usuario logueado</td>
      <td>poder cerrar sesión de forma segura</td>
      <td>proteger mis datos cuando termino de usar el sistema</td>
      <td>RF-01.3, RF-01-4</td>
      <td>Alta</td>
      <td>2</td>
      <td>HU-01</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Invalidación de sesión en servidor</td>
    </tr>
    
    <!-- EPICA 2 -->
    <tr>
      <td rowspan="4" style="text-align: center; font-weight: bold; background-color: #fce4d6;">EP-02</td>
      <td rowspan="4" style="background-color: #fce4d6;">Administrador</td>
      <td rowspan="4" style="background-color: #fce4d6;">gestionar la estructura académica (docentes, años lectivos, asignaciones)</td>
      <td rowspan="4" style="background-color: #fce4d6;">mantener la organización institucional y permitir el correcto registro de archivos</td>
      <td style="font-weight: bold;">HU-04</td>
      <td>Administrador</td>
      <td>registrar, modificar y cambiar el estado (activo/inactivo) de los docentes</td>
      <td>mantener actualizada la plantilla de personal sin perder su historial de archivos</td>
      <td>RNF3.2, RNF2.3, RNF3.1</td>
      <td>Alta</td>
      <td>5</td>
      <td>HU-01, HU-02</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Soft deletes para docentes</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-05</td>
      <td>Administrador</td>
      <td>poder activar un año lectivo</td>
      <td>que las operaciones del sistema se registren en el periodo correcto</td>
      <td>RF-02.2</td>
      <td>Alta</td>
      <td>3</td>
      <td>Ninguna</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Validar año único activo</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-06</td>
      <td>Administrador</td>
      <td>asociar a cada docente los cursos, grados y secciones que le corresponden dictar</td>
      <td>organizar la carga de trabajo y permitir que los docentes suban archivos en sus áreas correspondientes</td>
      <td>CA-01, CA-02</td>
      <td>Alta</td>
      <td>5</td>
      <td>HU-04, HU-05</td>
      <td>2</td>
      <td>Por hacer</td>
      <td>Relación M:N</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-07</td>
      <td>Administrador</td>
      <td>visualizar un dashboard general</td>
      <td>obtener una vista rápida del estado del sistema y accesos directos a las funciones principales</td>
      <td>RNF2.1</td>
      <td>Media</td>
      <td>3</td>
      <td>HU-01, HU-02</td>
      <td>1</td>
      <td>Por hacer</td>
      <td>Diseño responsivo</td>
    </tr>

    <!-- EPICA 3 -->
    <tr>
      <td rowspan="4" style="text-align: center; font-weight: bold; background-color: #e2efda;">EP-03</td>
      <td rowspan="4" style="background-color: #e2efda;">Docente</td>
      <td rowspan="4" style="background-color: #e2efda;">gestionar mis archivos y evidencias de trabajo</td>
      <td rowspan="4" style="background-color: #e2efda;">cumplir con mis entregas durante el año escolar activo de forma organizada</td>
      <td style="font-weight: bold;">HU-08</td>
      <td>Docente</td>
      <td>subir archivos digitales asociados a mis asignaciones vigentes</td>
      <td>mantener un registro de mis evidencias de trabajo durante el año escolar activo</td>
      <td>RNF2.2, RNF2.3</td>
      <td>Alta</td>
      <td>8</td>
      <td>HU-01, HU-05, HU-06</td>
      <td>2</td>
      <td>Por hacer</td>
      <td>Validación de tipos de archivos</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-09</td>
      <td>Docente</td>
      <td>ver la lista de todos mis archivos cargados durante el año escolar activo</td>
      <td>llevar un control de lo que he reportado en el sistema</td>
      <td>RNF3.1</td>
      <td>Alta</td>
      <td>5</td>
      <td>HU-08</td>
      <td>2</td>
      <td>Por hacer</td>
      <td>Filtrado por usuario autenticado</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-10</td>
      <td>Docente</td>
      <td>previsualizar mis documentos subidos en línea</td>
      <td>verificar su contenido sin necesidad de descargarlos en mi dispositivo</td>
      <td>CA-01, CA-02</td>
      <td>Media</td>
      <td>5</td>
      <td>HU-08</td>
      <td>2</td>
      <td>Por hacer</td>
      <td>Apertura en nueva pestaña</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-11</td>
      <td>Docente</td>
      <td>poder enviar mis archivos a una Papelera de Reciclaje temporal y restaurarlos</td>
      <td>corregir errores de subida sin perder la información de forma permanente</td>
      <td>RNF3.1, RNF2.3</td>
      <td>Media</td>
      <td>5</td>
      <td>HU-08</td>
      <td>3</td>
      <td>Por hacer</td>
      <td>Implementar Soft Deletes en archivos</td>
    </tr>

    <!-- EPICA 4 -->
    <tr>
      <td rowspan="3" style="text-align: center; font-weight: bold; background-color: #fff2cc;">EP-04</td>
      <td rowspan="3" style="background-color: #fff2cc;">Administrador</td>
      <td rowspan="3" style="background-color: #fff2cc;">auditar y generar reportes del cumplimiento de entregas</td>
      <td rowspan="3" style="background-color: #fff2cc;">controlar y documentar el estado de las evidencias de toda la institución</td>
      <td style="font-weight: bold;">HU-12</td>
      <td>Administrador</td>
      <td>visualizar y buscar todos los archivos subidos por todos los docentes de la institución</td>
      <td>realizar auditorías y controlar el cumplimiento de las entregas</td>
      <td>RNF3.1</td>
      <td>Alta</td>
      <td>5</td>
      <td>HU-08</td>
      <td>3</td>
      <td>Por hacer</td>
      <td>Múltiples filtros de búsqueda</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-13</td>
      <td>Administrador</td>
      <td>descargar o previsualizar en el navegador cualquier archivo del sistema</td>
      <td>revisar a detalle las evidencias subidas por los docentes</td>
      <td>RF-04-3, RNF1.2</td>
      <td>Media</td>
      <td>5</td>
      <td>HU-12</td>
      <td>3</td>
      <td>Por hacer</td>
      <td>URLs temporales / firmadas</td>
    </tr>
    <tr>
      <td style="font-weight: bold;">HU-14</td>
      <td>Administrador</td>
      <td>generar reportes de cumplimiento filtrando por año lectivo y bimestre, y descargarlos</td>
      <td>documentar y presentar el estado de entrega de evidencias de la institución</td>
      <td>RF-04.6</td>
      <td>Alta</td>
      <td>8</td>
      <td>HU-12</td>
      <td>3</td>
      <td>Por hacer</td>
      <td>Exportación PDF, Word, Excel</td>
    </tr>
  </tbody>
</table>
