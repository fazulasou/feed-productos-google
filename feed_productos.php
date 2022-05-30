<?php

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	/* Genera descripciones para los ficheros xml poniendo acentos y demás caracteres 	 */
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	function quitar_p_tag($CADENA){

		$no_permitidas= array ("<p>", "</p>","%","&aacute;", "&oacute;", "&eacute;","&iacute;","&uacute;","&nbsp;","&Aacute;", "&Oacute;", "&Eacute;","&Iacute;","&Uacute;", "&ntilde;", "&Ntilde;","&ordm;", "<br />",'"','<span lang="es">',"<span>","</span>",'<span class="hps">','<span lang=es>','<span class=hps>','<span id=result_box lang=es>', '<span class=bold>','<span class=editable>', '<h2>','</h2>', '<h3>', '</h3>' );

		$permitidas= array ("", " ","","á","ó", "é", "í", "ú"," ","Á","Ó", "É", "Í", "Ú", "ñ", "Ñ","", "","","","","","","","","","","","","","","");

		$CADENA = str_replace($no_permitidas, $permitidas ,$CADENA);
		$CADENA = strip_tags($CADENA);

		return $CADENA;


	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	/* Quita acentos de una cadena 																						 */
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	function quitarAcentosCadena( $CADENA ){

		/* Caracteres no permitidos */
		$noPermitidas = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","À","È","Ì","Ò","Ù");

		/* Caracteres por los que reemplazamos */
		$permitidas	= array ("a","e","i","o","u","A","E","I","O","U","A","E","I","O","U");

		$TEXTO_VALIDO = str_replace($noPermitidas, $permitidas ,$CADENA);

		return $TEXTO_VALIDO;

	}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/* Generador de feed para google Merchant						 	 */
/* @autor: 		Cadabullos Diseño Web								 */
/* @contact: 	info@cadabullos.com									 */		

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	include_once '../../include/config.php';

	header("Content-Type: text/xml; charset=UTF-8");
	echo '<?xml version="1.0"?>';
	
	if(!class_exists('base_datos')){

		include_once 'base_datos.php';
		$OBJ_BD = new base_datos( $bd, $user, $password, $server, 'utf8' );

	}
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	/* * DATOS 																	  	 									 	 * */
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if( $datosIVA = $OBJ_BD->ObtenerRegistroBaseDatos("SELECT * FROM `iva` WHERE `defecto`= '1' LIMIT 1" ) ){ $iva = $datosIVA['oper'];
	}else{ $iva = 0.21;	}

	if( $datosEnvios = $OBJ_BD->ObtenerRegistroBaseDatos("SELECT * FROM `mensajeria_condiciones` WHERE `ref`= '1' LIMIT 1" ) ){ $envios = $datosEnvios;
	}else{ $envios['desde_euros'] = '0'; $envios['hasta_euros'] = '0'; $envios['gastos_envio'] = '0'; }
 
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	/* * FEED																	  	 									 	 * */
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */ ?>
	
	<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
		
        <channel>

            <title>Wordl - Tienda online de Calzado</title>
            <link>tienda de calzado</link>
            <description>Tienda online de calzado para hombre y mujer y niño. Calzado para toda la familia.</description><?php
            
			/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
			/* * PRODUCTOS																  	 									 	 * */
			/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
			include_once '../../include/tcEncode.php';
			include_once '../../include/global.php';
			include_once '../../include/db.php';
			$_SESSION['idioma'] = 1;
			
			$productosMetidos = array();
		
			$productos = productosColor();
			$i = 1;
		
			foreach( $productos as $rowEmp ){

				if( !in_array( $rowEmp['id_producto'], $productosMetidos ) ){
					
					array_push( $productosMetidos, $rowEmp['id_producto'] );					
				
					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					/* * URL AMIGABLE															  	 									 	 * */
					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					include_once '../../include/functions.php';
					$URL_AMIGABLE = genFriendlyUrl('producto/', $rowEmp['titulo'], $rowEmp['id_producto_caracteristica']);
					//$URL_AMIGABLE = str_replace( ',', '', $URL_AMIGABLE );
					$URL_AMIGABLE = str_replace( '&', '', $URL_AMIGABLE );

					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					/* * MARCAS																  	 										 	 * */
					/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
					$nombreMarca = '';
					$datosCaracteristicaMarca['id_caracteristica'] = 0;
					if( $datosCaracteristicaMarca = $OBJ_BD->ObtenerRegistroBaseDatos("SELECT * FROM `p_caracteristica` WHERE `ref`= 'Marca' OR `ref`= 'marca'" ) ){

						if( $caracteristicasProducto = $OBJ_BD->ObtenerRegistroBaseDatos( "SELECT * FROM p_valor_idioma WHERE `id_valor`='".$rowEmp['id_marca']."' LIMIT 1" ) ){

							$nombreMarca = $caracteristicasProducto['valor'];

						}
					}

					if( $nombreMarca != '' && $rowEmp['id_categoria_google'] != 0 ){

						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						/* * IMAGENES																  	 									 	 * */
						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						$fotos = fotosColor($rowEmp);
						$imageLink = getImageUrl( $fotos[0], 'original' );
						$imageLink = str_replace( '&', '&amp;', $imageLink );

						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						/* * DESCRIPCION Y NOMBRE													  	 									 	 * */
						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						$descripcionProducto = $OBJ_BD->ObtenerRegistroBaseDatos("SELECT * FROM `p_producto_idioma` WHERE `id_producto`= '".$rowEmp['id_producto']."' AND `id_idioma`= '".$_SESSION['idioma']."' LIMIT 1" );


						$descripcionLarga = quitar_p_tag( $descripcionProducto['descripcion'] );
						$nombreLargo = quitar_p_tag( $descripcionProducto['titulo'] );

						$busquedas = array( '&iexcl;', '&reg;', '&iquest', '&ordf;', '&acute;', '&euro;', '&ldquo;', '&rdquo;', '&' );
						$reemplazar = array( '¡', '', '¿', 'º', '´', '€', '“', '”', '&amp;' );

						$descripcionLarga = str_replace( $busquedas, $reemplazar, $descripcionLarga );
						$nombreMarca = str_replace( $busquedas, $reemplazar, $nombreMarca );
						$rowEmp['ref'] = str_replace( $busquedas, $reemplazar, $rowEmp['ref'] );
						$nombreLargo = str_replace( $busquedas, $reemplazar, $nombreLargo );

						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						/* * PRECIO															  	 									 	 * */
						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						if( $rowEmp['precio_rebajas'] != '' &&  $rowEmp['precio_rebajas'] != 0 ){
							$precioProducto = $rowEmp['precio_rebajas'];
						}elseif( $rowEmp['precio_fin'] != '' &&  $rowEmp['precio_fin'] != 0 ){
							$precioProducto = $rowEmp['precio_fin'];
						}else{
							$precioProducto = $rowEmp['precio_ini'];
						}
						$precioConIva = number_format( round( $precioProducto + ( $precioProducto * $iva ) , 2 ), 2 );

						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						/* * ENVIOS															  	 									 	 		 * */
						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						if( $precioConIva > $envios['hasta_euros'] ){ $precioEnvio = 0; }else{ $precioEnvio = $envios['gastos_envio']; } 

						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						/* * DISPONIBILIDAD PRODUCTO												  	 									 	 * */
						/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
						$AVAILABILITY = 'out of stock';
						if( $queEmp = $OBJ_BD->Consulta_General("SELECT * FROM `p_stock` WHERE `id_producto`= '".$rowEmp['id_producto']."'" ) ){

							while( $rowEmpDisponibilidad = $OBJ_BD->devolverSiguienteRegistro( $queEmp ) ){
								if( $rowEmpDisponibilidad['stock'] > 0 ){
									$AVAILABILITY = 'in stock';
								}
							}

						} 

						if( $datosCaregoriaGoogle = $OBJ_BD->ObtenerRegistroBaseDatos("SELECT * FROM `categorias_google` WHERE `id_categoria` = '". $rowEmp['id_categoria_google'] ."' LIMIT 1") ){ ?>

							<item>

								<g:id><?php echo $rowEmp['id_producto'] ?></g:id>
								<g:title><?php echo htmlspecialchars(quitarAcentosCadena( $nombreLargo )); ?></g:title>
								<g:description><?php echo $descripcionLarga ?></g:description>
								<g:link><?php echo $URL_AMIGABLE ?></g:link>
								<g:image_link><?php echo $imageLink ?></g:image_link>
								<g:condition>new</g:condition>
								<g:availability><?php echo $AVAILABILITY ?></g:availability>
								<g:price><?php echo $precioConIva ?> EUR</g:price>
								<g:shipping>
									<g:country>ES</g:country>
									<g:service>Standard</g:service>
									<g:price><?php echo $precioEnvio ?> EUR</g:price>
								</g:shipping>
								<g:brand><?php echo quitarAcentosCadena( $nombreMarca ) ?></g:brand>			
								<g:mpn><?php echo quitarAcentosCadena($rowEmp['ref']); ?></g:mpn>
								<g:google_product_category><?php echo $datosCaregoriaGoogle['nombre'] ?></g:google_product_category>
								<g:product_type><?php echo $datosCaregoriaGoogle['nombre'] ?></g:product_type>

							</item><?php
							
							$i += 1;

						}
					}

				}
			} ?>            

		</channel>			

    </rss>
	

