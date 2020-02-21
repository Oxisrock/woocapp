<?php
/** 
 *  @package woocapp 
*/

namespace Inc\Base;


class Deactivate {
    public static function deactivate() {
        // Declaramos la tabla que se creará de la forma común.
        global $wpdb;
    
        // Con esto creamos el nombre de la tabla y nos aseguramos que se cree con el mismo prefijo que ya tienen las otras tablas creadas (wp_form).
        $table_name = $wpdb->prefix . 'proyectos';

        // Declaramos la tabla que se creará de la forma común.
        $sql = "DROP TABLE IF EXISTS $table_name;";
       
        // Creamos la tabla
        $wpdb->query($sql);

        delete_option('wc_client');

        delete_option('wc_secret');
    }
}