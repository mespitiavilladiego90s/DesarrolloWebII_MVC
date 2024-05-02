# DesarrolloWebII_Taller-1_IntroBasesDeDatos
En este repositorio se encuentra la solución al taller #1 enviado por el profesor Larry. 
![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/57fa3301-31eb-4f18-8537-9f6e469990d6)
**Solución al primer punto:** 
Para poder diseñar una base de datos que contenga las tablas anteriores, es necesario tener un contexto con anterioridad, ya sea ficticio o real, en este caso, me tomaré la molestia de diseñar mi propia situación a conveniencia de reducir el ejercicio a lo más simple posible.

**Situación problema:**
La empresa Terpel tiene una sede ubicada en la ciudad de Montería, cerca del centro comercial 'Nuestro', la cual presentó una falla en su sistema de información y se ha perdido por completo la aplicación que registra la información de cada compra de gasolina que haga un cliente. 
Afortunadamente, la empresa escuchó de un programador que puede es capaz de diseñar un nuevo sistema en tiempo récord, ante la impotencia de los vendedores de tener que registrar sus ventas manualmente, lo cual hace que sea un trabajo tedioso. Para dicho nuevo sistema, la compañía ha pedido que las condiciones sean las siguientes: 

**Condición #1:** Se debe poder llevar una referencia de cada cliente y cada vendedor, esto implica poder permitir que su información quede registrada en una base de datos.

**Condición #2:** Recordar que un cliente puede tanquear múltiples veces; un vendedor también puede generar múltiples facturas y que se puedan generar más de una orden de gasolina, ya que hay clientes que tanquean a nombre de la empresa razón por la cual necesitan llevar consigo más de una factura (una personal y otra para reportar a la empresa).

De esta manera, como programador tengo el siguiente modelo relacional de la base de datos que se va a crear:
![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/2f3ff836-349a-4b50-848d-590d7c1cdb5d)

La cual cumple tanto las condiciones anteriores, como las condiciones dada por nuestro docente.

Cabe aclarar que en el modelo relacional se pueden apreciar el primary key de cada tabla, el cual está subrayado con **negrilla**, junto con el foreign key de cada tabla, el cual está subrayado con _italic_ y a su vez, la tabla de factura que contiene argumentos el cual lleva tanto primary key como foreign key, indicados '**_así_**'.

Después de esto, hacemos uso de SQL para crear las tablas y asignar los respectivos primary y foreign key que permitan cumplir la condición de 1-N:
![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/3cce6f1f-f557-4651-a49d-6d85bcedc869)

Cabe destacar que todo lo relacionado con las ids de las tablas (ejemplo, id_vendedor) se cambió de nombre a 'id', esto con tal de facilitar la consulta mediante un patrón de diseño en el código backend.

El código backend está basado en los patrones de diseño Modelo-Vista-Controlador y ActiveRecord. En nuestro caso, usamos Modelo-Controlador ya que la vista no es necesaria, será POSTMAN quien envíe las solicitudes HTTP y reciba de la API las respuestas en formato .JSON.

**INSTRUCCIONES PARA EJECUTAR CORRECTAMENTE TODO EL EJERCICIO:**
Crea en tu escritorio una carpeta y luego nombrarla como quieras, en mi caso, cree una carpeta llamada desarrollowebII y dentro de esta carpeta, otra llamada "taller#1":

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/f76b494e-895b-4432-a57c-cbd62e2737b2)

Arrastra la carpeta a tu interfaz de visual studio code y luego abre una terminal, debe quedar algo por el estilo:
![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/ef79fd89-46e4-48c0-b53f-278de388c217)

Luego, clonar este repositorio, utilizando el comando **git clone https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos.git** :

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/698bdcac-af7c-4ff8-a096-fafbaf9a3e0e)

En los archivos que se clonaron, hay uno llamado **tablas.sql**:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/d2da5e5f-4b89-43a8-ba1c-aad8cd344347)

Creamos una base de datos que se llame 'terpel' y luego vamos ejecutando cada porción de código:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/742cb3c1-f885-4c85-b823-91a007f334eb)

Lo mejor es ir ejecutando el código por porciones, ya que así se evita problemas.
Luego, reemplazamos nuestras credenciales de la base de datos en el archivo db.php:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/cc9ef8fc-634b-4c7f-814a-437fa1d0d4f9)


Una vez clonado el repositorio y teniendo mi base de datos 'terpel' con mis tablas y procedimientos creados, abrir una nueva terminal de visual studio code e ingresar el comando **php -S localhost:3000* : 

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/b68d2302-bd2e-4309-844d-7f94f40fa7c0)

Después de eso, el servidor estará listo para recibir peticiones HTTP. Luego, en nuestro archivo **index.php** tenemos todas las rutas que vamos a probar:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/a241c452-6dbb-4c36-9946-fbc9786c6011)

En Postman, descargamos postman Agent para permitirle a postman conectar con nuestro servidor local PHP y así poder ver el tráfico de peticiones. Luego, utilizamos GET para probar la ruta **localhost:3000/obtener-vendedor/4** que debería mostrarnos la información del vendedor #4:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/e0339aa3-6bf5-4e5d-a141-4fabe534ff8f)

Ahora probamos una que lleve un .JSON y me cree un nuevo vendedor a través de POST, por ejemplo: ****localhost:3000/crear-vendedor/**, le insertamos el .JSON:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/b8a123a0-2e82-4343-9ebc-72c0c9458d27)

y luego testeamos, nos quedaría algo así:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/a70d0ad7-adf8-4a0c-8f90-d9a5b103e6c3)

Si rectificamos la información en la base de datos, veremos que se agregó el registro correctamente:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/b13f038a-32b9-469c-8dd8-1c2cf302aa09)

Así funciona para el resto de las rutas que se encuentran en index.php. Las rutas:
![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/23e59c77-d884-479f-9121-fca72d40b6bb)

Son correspondientes a las consultas que nuestro docente nos pidió aquí:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/c52d61ed-9907-4142-a587-24e465791aa7)

La parte del CRUD para cada una de las tablas ya se encuentra arriba en el código correspondiente al archivo APIController.php.

Si las ejecutamos, obtenemos los campos que nuestro docente nos pidió:

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/22c06fa3-4acb-488f-8f4b-bf66a4bc6692)

![image](https://github.com/mespitiavilladiego90s/DesarrolloWebII_Taller-1_IntroBasesDeDatos/assets/97072616/a7511fa8-9d5d-48df-a097-14bdcb36b81a)

Como podemos ver en la segunda imagen, me devuelve todos los registros relacionados a una orden de gasolina, es decir, todas las facturas que se crearon para una orden, cumpliendo así todas las condiciones que se pidieron.









