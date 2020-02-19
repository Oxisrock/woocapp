<?php
/** 
 *  @package woocapp 
*/

namespace Inc\Base;

class Activate {
    public static function activate() {

        // Declaramos la tabla que se creará de la forma común.
        global $wpdb;
        
        // Con esto creamos el nombre de la tabla y nos aseguramos que se cree con el mismo prefijo que ya tienen las otras tablas creadas (wp_form).
        $table_name = $wpdb->prefix . 'proyectos';

        // Declaramos la tabla que se creará de la forma común.
        $sql = "CREATE TABLE $table_name (
        `id_proyecto` int(11) NOT NULL AUTO_INCREMENT,
        `nombre_proyecto` varchar(255) NOT NULL,
        `excel_proyecto` varchar(255) NOT NULL,
        `apto_disponible_proyecto` varchar(255) NOT NULL,
        `desc_proyecto` LONGTEXT NULL,
        `id_asesor` int(11) NOT NULL,
        PRIMARY KEY (`id_proyecto`));";
        // upgrade contiene la función dbDelta la cuál revisará si existe la tabla.
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        // Creamos la tabla
        dbDelta($sql);
    }
}