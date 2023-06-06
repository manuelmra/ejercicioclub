## Pasos para poder replicar en el entorno local el sitio

### 01.- Validar que tiene PHP 7.4 o mayor
- Ejecutar:
- **php -v**

### 02.- Validar que tiene MySql instalado
- Si está en entorno Windows instalar Xampp, Wampp o algo similar y ejecutarlo

### 03.- Instalar el aplicativo Postman
- Si está usando entorno Windows instalar el aplicativo Postman
- Lo puede bajar del sitio 
- https://www.postman.com/downloads/
-  .... y lo instala
----

### 04.- Bajar el repositorio a su entorno local
- Si está usando Xampp o Wamp ubicarse en la carpeta htdocs.
- Luego de eso, replicar el repositorio ejecutando:
- *** git clone https://github.com/manuelmra/laliga.git ***

### 05.- Instalar las librerías que usa el sitio
- Ir a la carpeta **laliga**, que es donde se ha creado el sitio ejecutando
- **cd laliga**
- Este comando lee las librerías del archivo composer.json y las instala en la carpeta vendor
- Ejecutar
- **composer install**

### 06.- Crear la BD, sus tablas y datos de prueba
- Ejecute los siguientes comandos

#### Crear la BD
- **php bin/console doctrine:database:create**
#### Actualizar la BD: agregando las tablas y la data de prueba
- **php bin/console doctrine:migrations:migrate**

#### Correr el sitio en su entorno local
- **symfony server:start**
----
### 07.- Instalar los APIs en su aplicativo Postman##
- En la carpeta apidata se encuentra el archivo:
- **La Liga.postman_collection.json**
- Abra Postman y vaya a la opción Import, en la parte superior y elija el archivo indicado en el punto anterior

### 08.- Ejecutar las APIs del sitio##

Existen 3 carpetas:
- Club
- Player
- Coach
<br />

En cada una de esas carpetas están las diferentes APIs de cada modelo

### Usar como modelo del request en cada API para hacer cada operación

**XXXXX = Club o Coach o Player**

- ### - Get All XXXXX
//Lista los registros de ese modelo con sus respectivos campos
- En este caso no necesita colocar ningún dato en el body
----

- ### **- Create XXXXX** 
//Crea un nuevo registro del modelo indicado
<br />

### ***Player o Coach***
- Se colocan datos de ejemplo en raw del body con un formato json
- No es necesario colocar el campo id
<br />


### ***Club***
- Tener en cuenta que se debe de usar el modelo indicado en el request: uso de índices de cada item a registrar
- Si se quiere agregar un Player existente se coloca el id 
- Si se quiere agregar un Player que todavía no está registrado no se pone el id pero si el resto de datos
- Se está validando si un Player ya está registrado en un club.
- También se valida si un Coach ya está registrado en un club.
----

- ### **- Edit XXXXX**
//Edita o modifica el registro del modelo
- Modifica el registro del id indicado en los campos correspondientes
- Si se omitiera algún campo obligatorio (como en el caso de la creación de nuevos players) le va a enviar un error
- El campo name y salary son obligatorios. Si no se ponen datos le va a enviar un mensaje de error.
----

- ### **- Show XXXXX**
//Muestra los datos del registro del modelo
- Se muestran los campos de un determinado modelo (Player, Coach, Club) el id indicado
----

- ### **- Drop XXXXX**
// Da de baja al Player o al Coach si estuviera registrado en algún club
- Coloca el valor nulo en el campo club_id haciendo que ya no pertenezca al club.
----






