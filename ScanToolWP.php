<?php 

/*
Plugin Name: ScanToolWP
Description: Este plugin añade un menú en el dashboard llamado ScanToolWP.
*/


function enqueue_plugin_styles() {
    $plugin_dir = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'plugin-style', $plugin_dir . 'css/styles_jhon.css' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_plugin_styles' );



// Añadir menú en el dashboard
function ScanToolWP_menu() {
    add_menu_page(
        'ScanToolWP',            // Título de la página
        'ScanToolWP',            // Texto del menú
        'manage_options',  // Capacidad requerida para acceder al menú
        'ScanToolWP-menu',       // Slug de la página
        'ScanToolWP_page',       // Función que muestra el contenido de la página
        'dashicons-admin-generic' // Icono del menú
    );

    add_submenu_page(
        'ScanToolWP-menu',       // Slug del menú padre
        'Dashboard',       // Título de la página
        'Dashboard',       // Texto del submenú
        'manage_options',  // Capacidad requerida para acceder al submenú
        'ScanToolWP-dashboard',  // Slug de la página
        'ScanToolWP_dashboard_page' // Función que muestra el contenido de la página
    );

    // Añadir submenú "About"
    add_submenu_page(
        'ScanToolWP-menu', // Slug del menú padre
        'About',           // Título de la página
        'About',           // Texto del submenú
        'manage_options',  // Capacidad requerida para acceder al submenú
        'ScanToolWP-about',      // Slug de la página
        'ScanToolWP_about_page'  // Función que muestra el contenido de la página
    );
}
add_action('admin_menu', 'ScanToolWP_menu');


// Función para mostrar el contenido de la página del menú
function ScanToolWP_page() {
    echo '<div class="wrap">';
    echo '<h2>ScanToolWP_menu</h2>';
    echo '<p>Bienvenido al menú de ScanToolWP_menu.</p>';
    echo '</div>';
}
function ScanToolWP_dashboard_page(){
	$site_name = get_bloginfo('name');
    $site_url = get_bloginfo('url');
    $wp_version = get_bloginfo('version');
    $themes = wp_get_themes();
    $wp_ActiveTheme = wp_get_theme();
    $plugins = get_plugins();
    $post_count = wp_count_posts();
    $page_count = wp_count_posts('page');
	echo '<div class="wrap info_site">';
	    echo '<h2>Nombre del sitio: '.$site_name.'</h2>';
	    echo '<h3>Url de instalación: '.$site_url.'</h3>';
	    echo '<h4>La versión de WordPress es: '.$wp_version.'</h4>';
    echo '</div>';
    echo '<div class="wrap info_theme">';
    	echo '<h2>Temas Instalados</h2>';
	    echo '<ul class="list_Theme">';
		    foreach($themes as $theme){
		    	echo '<li>';
		    		if ($theme->get('Name')=== $wp_ActiveTheme->get('Name')) {
		    			echo '<b>' .$theme->get('Name').'</b>';
		    		}else{
		    			echo $theme->get('Name');
		    		}
		    	echo '</li>';
		    }
	    echo "</ul>";
    echo '</div>';
    echo '<div class="wrap info_plugin">';
    	echo '<h2>Plugins Instalados</h2>';
	    echo '<ul class="list_Theme">';
		    foreach($plugins as $plugin_path => $plugin_info){
		    	echo '<li>';
		    		if (is_plugin_active($plugin_path)) {
		    			echo '<span style="color:green;">' .$plugin_info['Name'].'</span>';
		    		}else{
		    			echo '<span style="color:red;">' .$plugin_info['Name'].'</span>';
		    		}
		    	echo '</li>';
		    }
	    echo "</ul>";
	    echo '<p>Número de entradas publicadas: ' . $post_count->publish .'</p>';
		echo '<p>Número de páginas publicadas: ' . $page_count->publish .'</p>';
    echo '</div>';
}
function ScanToolWP_about_page(){
	echo '<div class="wrap">';
    echo '<h2>About</h2>';
    echo '<p>Autor: Jhon Edison Sandoval Barreto</p>';
    echo '<a class="btn facebook" target="_blank" href="https://www.facebook.com/nativapps">Facebook</a> 
    		<a class="btn instagram" target="_blank" href="https://www.instagram.com/nativapps/">Instagram</a> 
    		<a class="btn linkedIn" target="_blank" href="https://www.linkedin.com/company/nativapps-inc/">LinkedIn</a>';
    echo '</div>';

}

// Registro del Custom Post Type 'Libros'
function registrar_post_type_libros() {

    $labels = array(
        'name'               => 'Libros',
        'singular_name'      => 'Libro',
        'menu_name'          => 'Libros',
        'name_admin_bar'     => 'Libro',
        'add_new'            => 'Agregar Nuevo',
        'add_new_item'       => 'Agregar Nuevo Libro',
        'new_item'           => 'Nuevo Libro',
        'edit_item'          => 'Editar Libro',
        'view_item'          => 'Ver Libro',
        'all_items'          => 'Todos los Libros',
        'search_items'       => 'Buscar Libros',
        'parent_item_colon'  => 'Libros Padres:',
        'not_found'          => 'No se encontraron libros.',
        'not_found_in_trash' => 'No se encontraron libros en la papelera.'
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'libros' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' )
    );

    register_post_type( 'libro', $args );

}
add_action( 'init', 'registrar_post_type_libros' );

// Registrar campos personalizados para el Custom Post Type 'Libros'
function registrar_campos_personalizados_libros() {
    // Nombre
    register_post_meta('libro', 'nombre', array(
        'type' => 'string',
        'label' => 'Nombre del libro',
        'single' => true,
        'show_in_rest' => true,
    ));

    // Género
    register_post_meta('libro', 'genero', array(
        'type' => 'string',
        'label' => 'Género del libro',
        'single' => true,
        'show_in_rest' => true,
    ));

    // Autor
    register_post_meta('libro', 'autor', array(
        'type' => 'string',
        'label' => 'Autor del libro',
        'single' => true,
        'show_in_rest' => true,
    ));

    // Año de publicación
    register_post_meta('libro', 'ano_publicacion', array(
        'type' => 'number',
        'label' => 'Año de publicación',
        'single' => true,
        'show_in_rest' => true,
    ));

}
add_action('init', 'registrar_campos_personalizados_libros');

function mostrar_campos_personalizados_libro() {
    global $post;
    $nombre = get_post_meta($post->ID, 'nombre', true);
    $genero = get_post_meta($post->ID, 'genero', true);
    $autor = get_post_meta($post->ID, 'autor', true);
    $ano_publicacion = get_post_meta($post->ID, 'ano_publicacion', true);
    $imagen = get_the_post_thumbnail($post->ID,'large');
    ?>
    <div class="entry-content">
	    <div class="misc-pub-section">
	        <span><?php echo $imagen; ?></span>   
	    		<br>
	        <label>Nombre del libro:</label>
	        <span><?php echo esc_html($nombre); ?></span>
	    <br>
	        <label>Género del libro:</label>
	        <span><?php echo esc_html($genero); ?></span>
	    <br>
	        <label>Autor del libro:</label>
	        <span><?php echo esc_html($autor); ?></span>
	    <br>
	        <label>Año de publicación:</label>
	        <span><?php echo esc_html($ano_publicacion); ?></span>
	    </div>
	</div>
    
    <?php
}
add_action('edit_form_after_title', 'mostrar_campos_personalizados_libro');






