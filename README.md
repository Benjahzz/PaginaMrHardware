# MrHardware 

MrHardware es una paginaWeb que ayuda a cotizar un setup y comparar precios de miles de productos de diferentes paginas web.

# Comunidad
Esta página cuenta con un apartado comunidad en la cual diferentes personas pueden seguir a otros, copiar setups y buscar a otros. Cada perfil muestra su informacion personal,
los comentarios que ha realizado esa cuenta, las calificaciones que les ha dado a los productos, los setups creados y copiados y sus seguidores.

# Productos

La pagina cuenta con miles de productos en los cuales puedes buscar por nombre, filtrar por tienda, menor precio y mayor precio. Cada producto cuenta con los detalles,
imagen y link a pagina del producto oficial(Tienda). En este apartado puedes agregar el producto seleccionado a tu setup y calificarlo de 1-5 estrellas para poder recomendarlo
a la gente.

# Comentarios

Cada producto cuenta con el apartado de comentarios en donde cada usuario puede dar su opinion acerca del producto. Cada comentario o respuesta puede tener respuestas, 
up-votes y un boton para reportar ese comentario(discriminación,spam...).

# Scraping

La pagina web muestra los productos gracias a un scraping que guarda datos en la base de datos, el scraping fue creado con python con ayuda de beautifulSoup y entre otras librerias,
este script de scraping busca de acuerdo a los links de las tiendas, por cada tienda va a buscar un tipo de producto y sacará lo importante de cada producto, despues se registrarán
los detalles e informacion del producto en la base de datos. Este scraping para un mejor desempeño funciona gracias a hilos.



