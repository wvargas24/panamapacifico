<?php
/**
 * Load Elementor templates from JSON files in the 'inc/elementor-json' directory.
 */

define('ELEMENTOR_TEMPLATES_DIR', get_stylesheet_directory() . '/inc/elementor-json');

if (class_exists('\Elementor\Plugin')) {
    $template_manager = \Elementor\Plugin::$instance->templates_manager;
    add_action('elementor/template/after_save', function ($template_id, $data) {
        // Obtener el nombre y el ID del template
        $template_name = sanitize_title($data['title']); // Obtener el título del template

        // Log para verificar que la acción se está ejecutando
        error_log("Elementor template saved: $template_name - $template_id");

        // Crear el nombre del archivo (usando el patrón name-id.json)
        $file_name = "{$template_name}-{$template_id}.json";
        error_log("Generated file name: $file_name");

        // Obtener la ruta completa del archivo
        $directory = get_stylesheet_directory() . "/inc/elementor-json/";

        // Asegurarse de que el directorio exista, si no, crearlo
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            error_log("Directory created: $directory");
        }

        // Obtener el contenido del template en formato HTML
        $template_content = $data['content'];
        error_log("Template content retrieved for template ID: $template_id");

        // Codificar los datos en base64 si lo necesitas
        $encoded_data = base64_encode($template_content);

        // Preparar los datos que se guardarán en el archivo JSON
        $template_data = [
            'fileData' => $encoded_data,
            'fileName' => $file_name,
            'template_name' => $template_name,
            'template_id' => $template_id
        ];

        // Convertir los datos a JSON
        $json_data = json_encode($template_data, JSON_PRETTY_PRINT);

        // Guardar el archivo JSON en el directorio
        $file_path = $directory . $file_name;

        // Escribir los datos en el archivo JSON
        if (file_put_contents($file_path, $json_data)) {
            // Loguear si el archivo se guardó correctamente
            error_log("Template saved as JSON: $file_name");
        } else {
            // Loguear si hubo algún problema al guardar el archivo
            error_log("Failed to save the template JSON: $file_name");
        }
    }, 10, 2);


} else {
    return;
}

// add_action('elementor/init', function () {
//     $directory = get_stylesheet_directory() . "/inc/elementor-json/";
//     $json_files = glob($directory . "*.json");

//     if (!empty($json_files)) {
//         foreach ($json_files as $path) {
//             if (file_exists($path) && is_readable($path)) {
//                 $file_name = pathinfo($path, PATHINFO_FILENAME); // Nombre del archivo sin la extensión
//                 $data = file_get_contents($path);

//                 list($template_name, $template_id) = explode('-', $file_name, 2);
//                 var_dump("template_name:$template_name");
//                 var_dump("template_id:$template_id");

//                 if ($data) {
//                     $template_data = [
//                         'fileData' => base64_encode($data),
//                         'fileName' => basename($path),
//                     ];

//                     // Obtener todas las plantillas existentes en Elementor
//                     $templates = \Elementor\Plugin::$instance->templates_manager->get_source('local')->get_items();

//                     $is_duplicate = false;

//                     foreach ($templates as $template) {
//                         $id = $template['template_id'];
//                         $title = $template['title'];

//                         var_dump("Id:$id");
//                         var_dump("Title:$title");

//                         // Comprobar si el titulo y el id son iguales a template_name y template_id
//                         if (strtolower($title) === strtolower($template_name) && $id === $template_id) {
//                             $is_duplicate = true;
//                             break;
//                         }
//                     }

//                     // Importar solo si no es duplicado
//                     if (!$is_duplicate) {
//                         \Elementor\Plugin::$instance->templates_manager->import_template($template_data);
//                         var_dump("Imported template: $file_name");
//                     } else {
//                         var_dump("Skipped importing duplicate template: $file_name");
//                     }
//                 } else {
//                     var_dump("Failed to read the Elementor template file at $path.");
//                 }
//             } else {
//                 var_dump("The file $path does not exist or is not readable.");
//             }
//         }
//     } else {
//         var_dump("No JSON files found in the directory $directory.");
//     }
// });




/**
 * Export Elementor templates to JSON file on update/save.
 */

