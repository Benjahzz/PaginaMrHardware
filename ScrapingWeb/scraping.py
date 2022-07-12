# -*- coding: utf-8 -*-
from array import array
from asyncio.log import logger
from asyncio.windows_events import NULL

from codecs import utf_8_decode
from concurrent.futures import ThreadPoolExecutor
from copyreg import constructor
from ctypes import sizeof
from dataclasses import replace
from distutils.log import error
from encodings.utf_8 import decode
from operator import index
from pickle import FALSE
import unicodedata
from bs4 import BeautifulSoup
import mysql.connector
import requests
import time
import sys

conexion = mysql.connector.connect(

    user='root',
    password='',
    host='localhost',
    database='mrhardware',
    
    charset='utf8'
)


cursorSelectSpdigital = conexion.cursor(buffered = True)
cursorSelectPcfactory = conexion.cursor(buffered = True)
cursorUpdate = conexion.cursor(buffered = True)

SQL_SELECT_SPDIGITAL = "select * from producto p inner join tiendas t on p.Tiendas_idTiendas = 1 "
SQL_SELECT_FALABELLA = "select * from producto p inner join tiendas t on p.Tiendas_idTiendas = 1 "
SQL_SELECT_PCFACTORY = "select * from producto p inner join tiendas t on p.Tiendas_idTiendas = 2 "
SQL_SELECT_INVASIONGAMER = "select * from producto p inner join tiendas t on p.Tiendas_idTiendas = 1 "

cursorSelectSpdigital.execute(SQL_SELECT_SPDIGITAL)
cursorSelectPcfactory.execute(SQL_SELECT_PCFACTORY)
print("a")
arraySelectSpdigital = cursorSelectSpdigital.fetchall()
arraySelectPcfactory = cursorSelectPcfactory.fetchall()

cursorSelectSpdigital.close()
cursorSelectPcfactory.close()


arrayNombres = []
arrayprecio = []
arrayImagenes = []

def buscarEnSPdigital(linkBuscar,cursorInsert,cursorInsertDetalle, id_TipoProducto):
    inicio = time.time()
    numeroPagina = 1
    insertar = bool(False)
    insertarUnico = bool(False)
    
    arrayObjeto = []
    arrayDetalle = []
    claseBloque = "Fractal-ProductCard__productcard--container"
    claseNombre = "Fractal-ProductCard--productDescriptionTextContainer"
    
    while(numeroPagina < 20):
        if numeroPagina == 1:
            website = linkBuscar
        else:
            website = linkBuscar + str(numeroPagina)
        
        headers = {'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'}
        result = requests.get(website, headers=headers)
        
        content = result.text

        soup = BeautifulSoup(content, 'lxml')
   
        blockNombre = soup.findAll('div', class_=claseBloque)
        

        if not blockNombre:
            print("l")
            break
        index = 0
        if not arraySelectSpdigital:
            insertar = bool(True)
            
            for i in range(len(blockNombre)):
                nombreObjeto = str(blockNombre[i].find('div', class_=claseNombre).span.text)
                precioNoF = getattr(blockNombre[i].find('div', class_='Fractal-ProductCard--priceVariantContainer').find('span', class_=['Fractal-Typography--typographyBestPriceMd', 'Fractal-Price--price']),  'text', None) 
                
                
                imagenObjeto = str(blockNombre[i].find('div', class_='Fractal-Image--content').find('img')['src'])
                PrecioFormateado = precioNoF.replace("$","").replace(".","")
                
                arrayNombres.append(nombreObjeto)
                arrayprecio.append(getattr(precioNoF,  'text', None))
                arrayImagenes.append(imagenObjeto)
                link = blockNombre[i].find('a', class_='Fractal-ProductCard--image')['href'];
                linkC = str(link).encode('cp1252')
                linkE = linkC.decode("utf-8")
                website = "https://www.spdigital.cl%s" % linkE
                
                result = requests.get(website, headers=headers)
               
                

                content = result.text

                soup = BeautifulSoup(content, 'lxml')
                blockEspecificaciones = soup.find('table', class_='Fractal-SpecTable--table')
                 
                if blockEspecificaciones:
                    arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website,i))
                    trows = blockEspecificaciones.tbody.findAll('tr')
                    arrayProductoDetalle = []
                    indextr= 0
                    for tr in trows:
                        if indextr > 11:
                            break
                        td = tr.findAll('td')
                        
                        if(len(td[1].text) < 100):
                            arrayProductoDetalle.append((td[0].text + ","+ td[1].text))
                        else:
                            arrayProductoDetalle.append((NULL))
                        indextr += 1
                        
                    arrayDetalle.append((arrayProductoDetalle))
                else:
                    arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website))
        else:
            
            for i in range(len(blockNombre)):
                
                nombreObjeto = blockNombre[i].find('div', class_=claseNombre).span.text
                precioNoF = getattr(blockNombre[i].find('div', class_='Fractal-ProductCard--priceVariantContainer').find('span', class_=['Fractal-Typography--typographyBestPriceMd', 'Fractal-Price--price']),  'text', None) 
                imagenObjeto = str(blockNombre[i].find('div', class_='Fractal-Image--content').find('img')['src'])
                PrecioFormateado = precioNoF.replace("$","").replace(".","")
                arrayNombres.append(nombreObjeto)
                arrayprecio.append(getattr(precioNoF,  'text', None))
                arrayImagenes.append(imagenObjeto)

                link = blockNombre[i].find('a', class_='Fractal-ProductCard--image')['href'];
                linkC = str(link).encode('cp1252')
                linkE = linkC.decode("utf-8")
                website = "https://www.spdigital.cl%s" % linkE
                result = requests.get(website, headers=headers)
                
                
                content = result.text

                soup = BeautifulSoup(content, 'lxml')
                
                blockEspecificaciones = soup.find('table', class_='Fractal-SpecTable--table')
                indexInsercion = 0
                index+= 1
                for x in range(len(arraySelectSpdigital)):
                    
                    if(nombreObjeto == arraySelectSpdigital[x][1] and imagenObjeto == arraySelectSpdigital[x][5] and int(PrecioFormateado) == arraySelectSpdigital[x][2] and arraySelectSpdigital[x][8] == website):
                        indexInsercion += 1   
                    if(nombreObjeto == arraySelectSpdigital[x][1] and imagenObjeto == arraySelectSpdigital[x][5] and int(PrecioFormateado) != arraySelectSpdigital[x][2]):
                        sqlUpdate = "update producto set precio = %s where idProducto = %s" % (int(PrecioFormateado),  arraySelectSpdigital[x][0])
                        indexInsercion += 1 
                        
                        cursorUpdate.execute(sqlUpdate)
                        conexion.commit()
                if indexInsercion == 0:
                   
                    if blockEspecificaciones:
                        
                        arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website,i))
                        
                        trows = blockEspecificaciones.tbody.findAll('tr')
                        arrayProductoDetalle = []
                        indextr= 0
                        for tr in trows:
                            if indextr > 11:
                                break
                            td = tr.findAll('td')
                            if(len(td[1].text) < 100):
                                arrayProductoDetalle.append((td[0].text + ","+ td[1].text))
                            else:
                                arrayProductoDetalle.append((NULL))
                            
                            
                            indextr += 1
                            
                        arrayDetalle.append((arrayProductoDetalle))
                
        
        numeroPagina += 1
    
    insertarProductos(arrayObjeto,arrayDetalle,cursorInsert,cursorInsertDetalle, id_TipoProducto,1)
    
    fin = time.time()
    print(fin-inicio)





# SpDigital ----------    
def buscarProcesadoresSPdigital():

    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkProcesador = "https://www.spdigital.cl/categories/componentes-procesador/"
    id_TipoProducto = 1
    buscarEnSPdigital(linkProcesador,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarTarjetasSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-tarjeta-de-video/"
    id_TipoProducto = 2
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarPlacasSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-placa-madre/"
    id_TipoProducto = 3
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarRamSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-memorias-ram/"
    id_TipoProducto = 4
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarAlmacenamientoSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-almacenamiento/"
    id_TipoProducto = 5
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarGabinetesSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-gabinetes/"
    id_TipoProducto = 6
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarFuentePoderSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-fuente-de-poder/"
    id_TipoProducto = 7
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarVentiladorSPdigital():
    print("a");
    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/componentes-refrigeracion-y-ventilacion/"
    id_TipoProducto = 8
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarMonitorSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/monitor/"
    id_TipoProducto = 9
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarTecladosSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/computacion-perifericos-teclados/"
    id_TipoProducto = 10
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarMouseSPdigital():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.spdigital.cl/categories/computacion-perifericos-mouse/"
    id_TipoProducto = 11
    buscarEnSPdigital(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def insertarProductos(arrayObjeto,arrayDetalle, cursorInsertDetalle, cursorInsert, id_TipoProducto,idTienda):

    sql = 'INSERT INTO producto(nombre,precio,id_TipoProducto, link, Tiendas_idTiendas,linkTienda,Detalle_idDetalle,vistas) values (%s,%s,'+ str(id_TipoProducto) + ',%s,'+str(idTienda)+',%s,%s,0)'
    sqlNoDetalle = 'INSERT INTO producto(nombre,precio,id_TipoProducto, link, Tiendas_idTiendas,vistas, linkTienda) values (%s,%s,'+ str(id_TipoProducto)+',%s,'+str(idTienda)+',0,%s)'
    
    lastId = []
    
    for x in range(len(arrayDetalle)):
        

        largo = len(arrayDetalle[x])
        
        sqlDetalle = switchDetalle(largo)
        
        arrayDetalleInsert = arrayDetalle[x]    
        
        try:
            cursorInsertDetalle.execute(sqlDetalle, arrayDetalleInsert)
        except mysql.connector.Error as err:
            print(arrayDetalleInsert)
            print(err)
            
        lastId.append(cursorInsertDetalle.lastrowid)
    indexDetalle = 0
    
    for x in range(len(arrayObjeto)):
        try:
            
            if type(arrayObjeto[x][-1]) == int:
                arrayObjetoInsert = list(arrayObjeto[x])
                arrayObjetoInsert.pop()
                arrayObjetoInsert += lastId[indexDetalle],
            
                cursorInsert.execute(sql,arrayObjetoInsert)
                
                indexDetalle += 1
            else:
                cursorInsert.execute(sqlNoDetalle,arrayObjeto[x])
        except mysql.connector.Error as err:
            print(format(err))
    conexion.commit()
    cursorInsert.close()
    cursorInsertDetalle.close()
    
    
        
def switchDetalle(num):
    seleccion = {

        1: "INSERT INTO detalle(caracteristica) values(%s)",
        2: "INSERT INTO detalle(caracteristica,caracteristica2) values(%s,%s)",
        3: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3) values(%s,%s,%s)",
        4: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4) values(%s,%s,%s,%s)",
        5: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5) values(%s,%s,%s,%s,%s)",
        6: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6) values(%s,%s,%s,%s,%s,%s)",
        7: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7) values(%s,%s,%s,%s,%s,%s,%s)",
        8: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7,caracteristica8) values(%s,%s,%s,%s,%s,%s,%s,%s)",
        9: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7,caracteristica8,caracteristica9) values(%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        10: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7,caracteristica8,caracteristica9,caracteristica10) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        11: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7,caracteristica8,caracteristica9,caracteristica10,caracteristica11) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        12: "INSERT INTO detalle(caracteristica,caracteristica2,caracteristica3,caracteristica4,caracteristica5,caracteristica6,caracteristica7,caracteristica8,caracteristica9,caracteristica10,caracteristica11,caracteristica12) values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
        
    }
    return seleccion.get(num,"no valido")

# InvasionGamer
def buscarPcFactory(linkBuscar,cursorInsert,cursorInsertDetalle, id_TipoProducto):
    inicio = time.time()
    numeroPagina = 1
    insertar = bool(False)
    insertarUnico = bool(False)
    
    arrayObjeto = []
    arrayDetalle = []
    claseBloque = "product"
    claseNombre = "product-ab-link"
    print("x")
    while(numeroPagina < 30):
        if numeroPagina == 1:
            website = linkBuscar
        else:
            website = linkBuscar + "&pagina="+str(numeroPagina)
        
        headers = {'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'}
        result = requests.get(website, headers=headers)
        
        content = result.text

        soup = BeautifulSoup(content, 'lxml')
        
        blockNombre = soup.findAll('div', class_=claseBloque)
        
        
        if not blockNombre:
            print("l")
            break
        index = 0
       
        if not arraySelectPcfactory:
            print("lll")
            insertar = bool(True)
            
            for i in range(len(blockNombre)):
                
                nombreObjeto = str(blockNombre[i].find('div', class_="product__card-title").text)
                
                precioNoF = blockNombre[i].find('div', class_='product__price-texts').find("div",class_='title-md').text
                
                imagenObjeto = str(blockNombre[i].find('div', class_="product__image").find("img")["src"])
                PrecioFormateado = precioNoF.replace("$","").replace(".","")
                
                arrayNombres.append(nombreObjeto)
                arrayprecio.append(precioNoF)
                arrayImagenes.append(imagenObjeto)

                link = blockNombre[i].find('a', class_=claseNombre)['href'];
                
                linkC = str(link).encode('cp1252')
                linkE = linkC.decode("utf-8")
                website = str("https://www.pcfactory.cl/producto" + linkE)
                result = requests.get(website, headers=headers)
                

                content = result.text

                soup = BeautifulSoup(content, 'lxml')
                
                if(soup.find("div", {"data-tab": "fichatecnica"})):
                    blockEspecificaciones = soup.find("div", {"data-tab": "fichatecnica"}).find('div', class_='table')
                    if(blockEspecificaciones.find("div",class_="table__header")):
                        blockEspecificaciones.find("div",class_="table__header").decompose()

                    if blockEspecificaciones:
                        arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website,i))
                        div = blockEspecificaciones
                        div = div.findAll('div', class_='table__content--two-column')

                        arrayProductoDetalle = []
                        indextr= 0
                        
                        for d in div:
                            
                            
                            if indextr > 11:
                                break
                            
                            dDetalle = d.findAll('div', class_="link")
                            
                            if(len(dDetalle[1].text) < 50 and len(dDetalle[0].text) < 50 ):
                                    
                                arrayProductoDetalle.append((dDetalle[0].text + ","+ dDetalle[1].text))
                            else:
                                arrayProductoDetalle.append((NULL))
                            indextr += 1
                            
                        arrayDetalle.append((arrayProductoDetalle))
                    else:
                        arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website))
        else:
            
            for i in range(len(blockNombre)):
                
                nombreObjeto = str(blockNombre[i].find('div', class_="product__card-title").text)
                
                precioNoF = blockNombre[i].find('div', class_='product__price-texts').find("div",class_='title-md').text
                
                
                imagenObjeto = str(blockNombre[i].find('div', class_="product__image").find("img")["src"])
                
                PrecioFormateado = precioNoF.replace("$","").replace(".","")
                arrayNombres.append(nombreObjeto)
                arrayprecio.append(precioNoF)
                arrayImagenes.append(imagenObjeto)
                
                link = blockNombre[i].find('a', class_=claseNombre)['href'];
                
                linkC = str(link).encode('cp1252')
                linkE = linkC.decode("utf-8")
                website = str("https://www.pcfactory.cl/producto" + linkE)
                
                result = requests.get(website, headers=headers)
               
              

                content = result.text

                soup = BeautifulSoup(content, 'lxml')
                blockEspecificaciones = soup.find("div", {"data-tab": "fichatecnica"}).find('div', class_='table')
                if(blockEspecificaciones.find("div",class_="table__header")):
                    blockEspecificaciones.find("div",class_="table__header").decompose()
                
                
                
                
                indexInsercion = 0
                index+= 1
                
                for x in range(len(arraySelectPcfactory)):
                    
                    if(nombreObjeto == arraySelectPcfactory[x][1] and imagenObjeto == arraySelectPcfactory[x][5] and int(PrecioFormateado) == arraySelectPcfactory[x][2] and arraySelectPcfactory[x][8] == website):
                        indexInsercion += 1   
                    if(nombreObjeto == arraySelectPcfactory[x][1] and imagenObjeto == arraySelectPcfactory[x][5] and int(PrecioFormateado) != arraySelectPcfactory[x][2]):
                        sqlUpdate = "update producto set precio = %s where idProducto = %s" % (int(PrecioFormateado),  arraySelectPcfactory[x][0])
                        indexInsercion += 1 
                        
                        cursorUpdate.execute(sqlUpdate)
                        conexion.commit()
                if indexInsercion == 0:
                    
                    if blockEspecificaciones:
                        
                        arrayObjeto.append((nombreObjeto,PrecioFormateado,imagenObjeto,website,i))
                        div = blockEspecificaciones
                        div = div.findAll('div', class_='table__content--two-column')

                        arrayProductoDetalle = []
                        indextr= 0
                        
                        for d in div:
                            
                            
                            if indextr > 11:
                                break
                            
                            dDetalle = d.findAll('div', class_="link")
                            
                            if(len(dDetalle[1].text) < 50 and len(dDetalle[0].text) < 50 ):
                                    
                                arrayProductoDetalle.append((dDetalle[0].text + ","+ dDetalle[1].text))
                            else:
                                arrayProductoDetalle.append((NULL))
                            indextr += 1

                        
                        if(arrayProductoDetalle == []):
                            arrayDetalle.append((NULL))
                        else:

                            arrayDetalle.append((arrayProductoDetalle))
                
        
        numeroPagina += 1
    
    insertarProductos(arrayObjeto,arrayDetalle,cursorInsert,cursorInsertDetalle, id_TipoProducto,2)
    
    fin = time.time()
    print(fin-inicio)





def buscarProcesadoresPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkProcesador = "https://www.pcfactory.cl/procesadores?categoria=272"
    id_TipoProducto = 1
    buscarPcFactory(linkProcesador,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarTarjetasPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTarjeta = "https://www.pcfactory.cl/tarjetas-graficas?categoria=334"
    id_TipoProducto = 2
    buscarPcFactory(linkTarjeta,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarPlacasPcFactory():
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkPlaca = "https://www.pcfactory.cl/placas-madres?categoria=292"
    id_TipoProducto = 3
    buscarPcFactory(linkPlaca,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarRamPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkRam = "https://www.pcfactory.cl/memorias-pc?categoria=112&papa=264"
    id_TipoProducto = 4
    buscarPcFactory(linkRam,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarAlmacenamientoPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkAlmacenamiento = "https://www.pcfactory.cl/discos-ssd?categoria=585"
    id_TipoProducto = 5
    buscarPcFactory(linkAlmacenamiento,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarGabinetesPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkGabinete = "https://www.pcfactory.cl/gabinetes?categoria=326"
    id_TipoProducto = 6
    buscarPcFactory(linkGabinete,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarFuentePoderPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkFuente = "https://www.pcfactory.cl/fuentes-de-poder-psu-?categoria=54"
    id_TipoProducto = 7
    buscarPcFactory(linkFuente,cursorInsert,cursorInsertDetalle, id_TipoProducto)
def buscarVentiladorPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkVentilador = "https://www.pcfactory.cl/refrigeracion?categoria=42"
    id_TipoProducto = 8
    buscarPcFactory(linkVentilador,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarMonitorPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkMonitor = "https://www.pcfactory.cl/monitores?categoria=995"
    id_TipoProducto = 9
    buscarPcFactory(linkMonitor,cursorInsert,cursorInsertDetalle, id_TipoProducto)


def buscarTecladosPcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkTeclados = "https://www.pcfactory.cl/teclados?categoria=1301"
    id_TipoProducto = 10
    buscarPcFactory(linkTeclados,cursorInsert,cursorInsertDetalle, id_TipoProducto)

def buscarMousePcFactory():

    
    cursorInsert = conexion.cursor()
    cursorInsertDetalle = conexion.cursor()
    linkMouse = "https://www.pcfactory.cl/mouses?categoria=1302"
    id_TipoProducto = 11
    buscarPcFactory(linkMouse,cursorInsert,cursorInsertDetalle, id_TipoProducto)


with ThreadPoolExecutor() as executor:
    
    proceSp = executor.submit(buscarProcesadoresSPdigital)

    tarjetaSp = executor.submit(buscarTarjetasSPdigital)

    PlacaSp = executor.submit(buscarPlacasSPdigital)

    RamSp = executor.submit(buscarRamSPdigital)

    AlmacenamientoSp = executor.submit(buscarAlmacenamientoSPdigital)

    GabineteSp = executor.submit(buscarGabinetesSPdigital)

    FuenteSp = executor.submit(buscarFuentePoderSPdigital)

    VentiladorSp = executor.submit(buscarVentiladorSPdigital)

    MonitorSp = executor.submit(buscarMonitorSPdigital)

    TecladoSp = executor.submit(buscarTecladosSPdigital)
    MouseSp = executor.submit(buscarMouseSPdigital)


    procePc = executor.submit(buscarProcesadoresPcFactory) # 

    tarjetaPc= executor.submit(buscarTarjetasPcFactory) 

    PlacaPc = executor.submit(buscarPlacasPcFactory)#
    MousePc =executor.submit(buscarMousePcFactory)
    RamPc= executor.submit(buscarRamPcFactory) #

    AlmacenamientoPc= executor.submit(buscarAlmacenamientoPcFactory) #

    GabinetePc = executor.submit(buscarGabinetesPcFactory)#

    FuentePc= executor.submit(buscarFuentePoderPcFactory) #
    VentiladorPc= executor.submit(buscarVentiladorPcFactory) #

    MonitorPc = executor.submit(buscarMonitorPcFactory)

    TecladosPc= executor.submit(buscarTecladosPcFactory)#

    print(TecladoSp.result())
    print(tarjetaPc.result())




    





    
