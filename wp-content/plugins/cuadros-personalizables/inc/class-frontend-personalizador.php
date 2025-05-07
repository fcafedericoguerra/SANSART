<?php
if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

class CuadrosPersonalizables_Frontend {

  public function __construct() {
    // Registrar shortcode
    add_shortcode('mostrar_personalizador', array($this, 'shortcode_personalizador_frontend'));

    // Hooks para WooCommerce
    add_filter('woocommerce_add_to_cart_validation', array($this, 'validar_personalizacion'), 10, 2);
    add_filter('woocommerce_add_cart_item_data', array($this, 'agregar_datos_personalizados_al_carrito'), 10, 2);
    add_filter('woocommerce_get_item_data', array($this, 'mostrar_datos_en_carrito'), 10, 2);
    add_action('woocommerce_add_order_item_meta', array($this, 'guardar_datos_en_orden'), 10, 2);
    
    // Para la miniatura en el carrito
    add_filter('woocommerce_cart_item_thumbnail', array($this, 'personalizar_thumbnail_carrito'), 10, 3);

    // Asegurar que el campo personalizado esté en el formulario
    add_action('woocommerce_before_add_to_cart_button', array($this, 'agregar_campo_personalizado_formulario'));

    // JavaScript para deshabilitar/habilitar el botón de carrito
    add_action('wp_footer', array($this, 'script_validacion_carrito'));

    // Encolar scripts y estilos
    add_action('wp_enqueue_scripts', array($this, 'cargar_scripts_frontend'));
    
    // Cargar script para carrito
    add_action('wp_enqueue_scripts', array($this, 'cargar_script_carrito'));
    
    // Detectar y manejar la edición desde el carrito
    add_action('wp_footer', array($this, 'detectar_edicion_del_carrito'));
    
    // Agregar botón para resetear personalización
    add_action('woocommerce_before_add_to_cart_button', array($this, 'agregar_boton_reset'), 15);
    
    // AJAX para resetear personalización
    add_action('wp_ajax_reset_personalization', array($this, 'reset_personalization'));
    add_action('wp_ajax_nopriv_reset_personalization', array($this, 'reset_personalization'));
  }

/**
 * Encolar los scripts y estilos en la página de producto
 */
public function cargar_scripts_frontend() {
  if (is_product()) {
      global $product;
      
      // Verificar que $product es un objeto WC_Product válido
      if (!is_object($product)) {
          // Si $product no es un objeto, intentar obtenerlo de la ID de la página actual
          $product = wc_get_product(get_the_ID());
          
          // Si aún no tenemos un objeto de producto válido, salir
          if (!is_object($product)) {
              return;
          }
      }
        
      // Verificar si es un producto personalizable
      $mockup_url = get_post_meta($product->get_id(), '_mockup_url', true);
      if (empty($mockup_url)) return;
      
      // Fabric.js
      wp_enqueue_script(
          'fabric-js',
          'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js',
          array('jquery'),
          null,
          true
      );

      // Nuestro JS para Elementor - Asegurarse de que dependa de fabric.js
      wp_enqueue_script(
          'personalizador-frontend-js',
          plugin_dir_url(dirname(__FILE__)) . 'assets/js/personalizador-frontend.js',
          array('jquery', 'fabric-js'),
          defined('CPC_PLUGIN_VERSION') ? CPC_PLUGIN_VERSION : time(),
          true
      );
      
      // Script de depuración (solo en modo debug)
      if (defined('WP_DEBUG') && WP_DEBUG) {
          wp_enqueue_script(
              'personalizador-debug-js',
              plugin_dir_url(dirname(__FILE__)) . 'assets/js/personalizador-debug.js',
              array('jquery', 'personalizador-frontend-js'),
              defined('CPC_PLUGIN_VERSION') ? CPC_PLUGIN_VERSION : time(),
              true
          );
      }

      // Nuestro CSS
      wp_enqueue_style(
          'personalizador-frontend-css',
          plugin_dir_url(dirname(__FILE__)) . 'assets/css/personalizador.css',
          array(),
          defined('CPC_PLUGIN_VERSION') ? CPC_PLUGIN_VERSION : time()
      );
      
      // Pasamos variables al JS para debugging
      wp_localize_script(
          'personalizador-frontend-js',
          'personalizadorVars',
          array(
              'ajaxurl' => admin_url('admin-ajax.php'),
              'nonce' => wp_create_nonce('personalizador_nonce'),
              'debug' => defined('WP_DEBUG') ? WP_DEBUG : false,
              'plugin_url' => plugin_dir_url(dirname(__FILE__)),
              'product_id' => $product->get_id(),
              'is_product_page' => is_product()
          )
      );
  }
}

/**
 * Método para cargar script adicional para el carrito
 */
public function cargar_script_carrito() {
    // Solo cargar en páginas de carrito o checkout
    if (is_cart() || is_checkout()) {
        wp_enqueue_script(
            'cart-personalizacion-js',
            plugin_dir_url(dirname(__FILE__)) . 'assets/js/cart-personalizacion.js',
            array('jquery'),
            defined('CPC_PLUGIN_VERSION') ? CPC_PLUGIN_VERSION : time(),
            true
        );
        
        // Agregar script mejorado para manejo de imágenes
        wp_enqueue_script(
            'image-handler-js',
            plugin_dir_url(dirname(__FILE__)) . 'assets/js/image-handler.js',
            array('jquery', 'cart-personalizacion-js'),
            defined('CPC_PLUGIN_VERSION') ? CPC_PLUGIN_VERSION : time(),
            true
        );
    }
}

/**
 * Asegurar que el campo personalizado esté en el formulario
 * Este es un punto clave para Elementor
 */
public function agregar_campo_personalizado_formulario() {
  global $product;
  
  // Verificar que $product es un objeto WC_Product válido
  if (!is_object($product)) {
      // Si $product no es un objeto, intentar obtenerlo de la ID de la página actual
      $product = wc_get_product(get_the_ID());
      
      // Si aún no tenemos un objeto de producto válido, salir
      if (!is_object($product)) {
          return;
      }
  }
  
  $mockup_url = get_post_meta($product->get_id(), '_mockup_url', true);
  if (empty($mockup_url)) return;
  
  // Campo oculto para la imagen personalizada (compatibilidad)
  echo '<input type="hidden" name="img_personalizada" id="img_personalizada" value="">';
  
  // Nuevo campo oculto para ID de personalización
  echo '<input type="hidden" name="personalizacion_id" id="personalizacion_id" value="">';
}

/**
 * Agregar botón para resetear personalización
 */
public function agregar_boton_reset() {
  global $product;
  
  // Verificar que $product es un objeto WC_Product válido
  if (!is_object($product)) {
      // Si $product no es un objeto, intentar obtenerlo de la ID de la página actual
      $product = wc_get_product(get_the_ID());
      
      // Si aún no tenemos un objeto de producto válido, salir
      if (!is_object($product)) {
          return;
      }
  }
  
  $mockup_url = get_post_meta($product->get_id(), '_mockup_url', true);
  if (empty($mockup_url)) return;
  
  // ID del producto
  $product_id = $product->get_id();
  
  // Verificar si hay una personalización guardada
  if (class_exists('CuadrosPersonalizables_DB')) {
      $db = CuadrosPersonalizables_DB::get_instance();
      $personalizacion = $db->get_personalization($product_id);
      
      if ($personalizacion) {
          ?>
          <div class="personalizador-reset-container" style="margin-top: 10px;">
              <button type="button" id="reset-personalizacion" class="button">Restablecer personalización</button>
          </div>
          
          <script>
          jQuery(document).ready(function($) {
              $('#reset-personalizacion').on('click', function() {
                  if (typeof window.resetPersonalizacion === 'function') {
                      window.resetPersonalizacion()
                          .then(function() {
                              location.reload();
                          })
                          .catch(function(error) {
                              console.error("Error al restablecer:", error);
                          });
                  } else {
                      alert('Error: La función de restablecimiento no está disponible.');
                  }
              });
          });
          </script>
          <?php
      }
  }
}

/**
 * Script para validar que se personalice antes de agregar al carrito
 */
public function script_validacion_carrito() {
  if (is_product()) {
    global $product;
    
    // Verificar que $product es un objeto WC_Product válido
    if (!is_object($product)) {
        // Si $product no es un objeto, intentar obtenerlo de la ID de la página actual
        $product = wc_get_product(get_the_ID());
        
        // Si aún no tenemos un objeto de producto válido, salir
        if (!is_object($product)) {
            return;
        }
    }
    
    $mockup_url = get_post_meta($product->get_id(), '_mockup_url', true);
    if (empty($mockup_url)) return;
    
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Selectores específicos de Elementor
      const addToCartBtn = document.querySelector('.elementor-button.elementor-button--checkout, .elementor-add-to-cart .single_add_to_cart_button, .single_add_to_cart_button, form.cart button[type="submit"]');
      const personalizacionInput = document.getElementById('img_personalizada');
      const personalizacionIdInput = document.getElementById('personalizacion_id');
      const form = document.querySelector('form.cart');
      
      console.log('Debug: Botón de carrito encontrado:', addToCartBtn);
      console.log('Debug: Campo personalización encontrado:', personalizacionInput);
      console.log('Debug: Campo ID personalización encontrado:', personalizacionIdInput);
      console.log('Debug: Formulario encontrado:', form);
      
      // Verificar si ya hay una personalización guardada
      const tienePersonalizacion = (personalizacionInput && personalizacionInput.value) || 
                                   (personalizacionIdInput && personalizacionIdInput.value);
      
      if (addToCartBtn) {
        // Inicialmente deshabilitar el botón si no hay personalización
        if (!tienePersonalizacion) {
          addToCartBtn.disabled = true;
          addToCartBtn.classList.add('disabled');
        } else {
          // Activar el botón si ya hay personalización
          addToCartBtn.disabled = false;
          addToCartBtn.classList.remove('disabled');
          
          // Cambiar apariencia del botón personalizar
          const btnPersonalizar = document.getElementById('btn-personalizar');
          if (btnPersonalizar) {
            btnPersonalizar.classList.add('personalizado');
            btnPersonalizar.innerText = 'Editar personalización';
          }
        }
        
        // Validar antes de enviar el formulario
        if (form) {
          form.addEventListener('submit', function(e) {
            // Verificar nuevamente al momento de enviar
            const tienePersonalizacionActual = (personalizacionInput && personalizacionInput.value) || 
                                             (personalizacionIdInput && personalizacionIdInput.value);
            
            if (!tienePersonalizacionActual) {
              e.preventDefault();
              e.stopPropagation();
              alert('Por favor, personaliza tu producto antes de añadirlo al carrito.');
              return false;
            }
          });
        }
        
        // Prevenir clic directo
        addToCartBtn.addEventListener('click', function(e) {
          // Verificar nuevamente al hacer clic
          const tienePersonalizacionActual = (personalizacionInput && personalizacionInput.value) || 
                                           (personalizacionIdInput && personalizacionIdInput.value);
          
          if (this.disabled || !tienePersonalizacionActual) {
            e.preventDefault();
            e.stopPropagation();
            alert('Por favor, personaliza tu producto antes de añadirlo al carrito.');
            
            // Intentar abrir el personalizador
            const btnPersonalizar = document.getElementById('btn-personalizar');
            if (btnPersonalizar) {
              btnPersonalizar.click();
            }
            
            return false;
          }
        });
      }
    });
    </script>
    <?php
  }
}

/**
 * Shortcode para el personalizador (adaptado para Elementor)
 */
public function shortcode_personalizador_frontend() {
    global $product;
    if (!is_object($product)) {
      $product = wc_get_product(get_the_ID());
      if (!is_object($product) || !is_product()) return '';
    }
  
    $mockup_url  = get_post_meta($product->get_id(), '_mockup_url', true);
    $mockup_area = get_post_meta($product->get_id(), '_mockup_area', true);
    if (empty($mockup_url)) return '';
  
    // Asegura URL absolutas
    if (strpos($mockup_url, 'http') !== 0 && strpos($mockup_url, '//') !== 0) {
      $upload = wp_upload_dir();
      $mockup_url = (strpos($mockup_url, '/') === 0)
        ? site_url($mockup_url)
        : $upload['baseurl'] . '/' . ltrim($mockup_url, '/');
      update_post_meta($product->get_id(), '_mockup_url', $mockup_url);
    }
  
    // Determina tamaño producto “30x30” u otro
    $tam = '30x30';
    if ($meta = get_post_meta($product->get_id(), '_tamaño_cuadro', true)) {
      if (preg_match('/(\d+)\s*[x×]\s*(\d+)/i', $meta, $m)) $tam = "{$m[1]}x{$m[2]}";
    } elseif ($product->is_type('variable')) {
      foreach ($product->get_attributes() as $name => $attr) {
        $label = wc_attribute_label($name);
        if (preg_match('/tamañ|dimensi|medida|size/i', $label) && $terms = wc_get_product_terms($product->get_id(), $name, ['fields'=>'names'])) {
          foreach ($terms as $t) {
            if (preg_match('/(\d+)\s*[x×]\s*(\d+)/i', $t, $m)) { $tam = "{$m[1]}x{$m[2]}"; break 2; }
          }
        }
      }
    }
  
    // Área como JSON seguro
    $area_json = !empty($mockup_area)
      ? wp_json_encode(json_decode($mockup_area, true))
      : wp_json_encode(['x'=>0,'y'=>0,'width'=>0,'height'=>0]);
  
    ob_start(); ?>
    <div class="personalizador-container">
      <button type="button" id="btn-personalizar" class="button elementor-button">Personalizar</button>
      <div id="popup-personalizar">
        <div class="contenido-modal">
          <a href="#" id="cerrar-modal">&times;</a>
          <div class="col-left">
            <canvas id="mockupCanvas"></canvas>
          </div>
          <div class="col-right">
            <h3>Personaliza tu producto</h3>
            <div class="custom-file-upload">
              <label for="foto_subida">Sube tu imagen</label>
              <div class="custom-file-input">
                <input type="file" id="foto_subida" accept="image/*">
                <div class="file-upload-btn"><span>Seleccionar archivo</span></div>
                <div class="file-selected-name">Ningún archivo seleccionado</div>
              </div>
            </div>
            <p class="help-text">Mientras mejor calidad tenga tu imagen, mejor saldrá tu impresión.</p>
            <div class="zoom-control">
              <label for="zoomRange">Zoom:</label>
              <div class="zoom-slider-container">
                <span class="zoom-icon zoom-out">-</span>
                <input type="range" id="zoomRange" min="1" max="3" step="0.1" value="1" disabled>
                <span class="zoom-icon zoom-in">+</span>
              </div>
            </div>
            <div class="actions-container">
              <button type="button" id="rotate90Btn" class="action-btn btn-rotate">↻ Rotar 90°</button>
              <button type="button" id="confirmar-personalizacion" class="action-btn btn-confirm">Confirmar</button>
            </div>
          </div>
        </div>
        <div id="mockup-data"
             data-mockup-url="<?php echo esc_attr($mockup_url); ?>"
             data-mockup-area="<?php echo esc_attr($area_json); ?>"
             data-producto-tamaño="<?php echo esc_attr($tam); ?>">
        </div>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
  

/**
 * Validar personalización antes de agregar al carrito
 */
public function validar_personalizacion($passed, $product_id) {
  $mockup_url = get_post_meta($product_id, '_mockup_url', true);
  
  if (empty($mockup_url)) {
      return $passed; // No es un producto personalizable
  }
  
  // Verificar si hay una personalización guardada
  $tiene_personalizacion = false;
  
  // Verificar ID de personalización (nuevo método)
  if (!empty($_POST['personalizacion_id'])) {
      $personalizacion_id = intval($_POST['personalizacion_id']);
      if (class_exists('CuadrosPersonalizables_DB')) {
          $db = CuadrosPersonalizables_DB::get_instance();
          $personalizacion = $db->get_personalization_by_id($personalizacion_id);
          if ($personalizacion) {
              $tiene_personalizacion = true;
          }
      }
  }
  
  // Verificar imagen personalizada (método antiguo)
  if (!$tiene_personalizacion && !empty($_POST['img_personalizada'])) {
      $tiene_personalizacion = true;
  }
  
  // Si no hay personalización, mostrar error
  if (!$tiene_personalizacion) {
      wc_add_notice('Por favor, personaliza tu producto antes de añadirlo al carrito.', 'error');
      return false;
  }
  
  return $passed;
}

public function agregar_datos_personalizados_al_carrito($cart_item_data, $product_id) {
    $mockup_url = get_post_meta($product_id, '_mockup_url', true);
    
    if (empty($mockup_url)) {
        return $cart_item_data; // No es un producto personalizable
    }
    
    // Método nuevo: usar ID de personalización (prioridad)
    if (!empty($_POST['personalizacion_id'])) {
        $personalizacion_id = intval($_POST['personalizacion_id']);
        
        if (class_exists('CuadrosPersonalizables_DB')) {
            $db = CuadrosPersonalizables_DB::get_instance();
            $personalizacion = $db->get_personalization_by_id($personalizacion_id);
            
            if ($personalizacion) {
                // Guardar ID y URL de imagen
                $cart_item_data['personalizacion_id'] = $personalizacion_id;
                
                // Si existe image_url usamos esa, si no, intentamos image_data para compatibilidad
                if (!empty($personalizacion->image_url)) {
                    $cart_item_data['img_personalizada'] = $personalizacion->image_url;
                } elseif (!empty($personalizacion->image_data)) {
                    $cart_item_data['img_personalizada'] = $personalizacion->image_data;
                }
                
                $cart_item_data['estado_personalizacion'] = $personalizacion->image_state;
                
                // Agregar hash único para evitar combinar items
                $cart_item_data['unique_key'] = md5(microtime().rand());
                
                return $cart_item_data;
            }
        }
    }
    
    // Método antiguo: usar imagen directamente
    if (!empty($_POST['img_personalizada'])) {
        // Guardar URL o base64 de la imagen
        $cart_item_data['img_personalizada'] = $_POST['img_personalizada'];
        
        // Agregar hash único para evitar combinar items
        $cart_item_data['unique_key'] = md5(microtime().rand());
    }
    
    return $cart_item_data;
  }

/**
 * Muestra la miniatura y los enlaces “Ver / Editar” de la personalización
 * dentro de la tabla del carrito.
 *
 * @param array $item_data
 * @param array $cart_item
 * @return array
 */
public function mostrar_datos_en_carrito($item_data, $cart_item) {
    // ¿Este ítem tiene personalización?
    if (empty($cart_item['personalizacion_id']) && empty($cart_item['img_personalizada'])) {
        return $item_data; // nada que hacer
    }

    $product_id = $cart_item['product_id'];
    $personalizacion_id = isset($cart_item['personalizacion_id']) ? $cart_item['personalizacion_id'] : 0;

    /* ---------- 1. Generar URL de edición ---------- */
    $edit_url = add_query_arg(
        array(
            'edit_personalizacion' => '1',
            'id' => $personalizacion_id,
            'timestamp' => time(), // anti-caché
        ),
        get_permalink($product_id)
    );

    /* ---------- 2. Obtener la imagen (preferimos URL sobre base64) ---------- */
    $image_src = '';
    
    // a) Desde el propio cart_item
    if (!empty($cart_item['img_personalizada'])) {
        $image_src = $cart_item['img_personalizada'];
    }
    
    // b) Si no tenemos imagen pero tenemos ID, intentar obtenerla de la base de datos
    if (empty($image_src) && $personalizacion_id && class_exists('CuadrosPersonalizables_DB')) {
        $db = CuadrosPersonalizables_DB::get_instance();
        $row = $db->get_personalization_by_id($personalizacion_id);
        
        if ($row) {
            if (!empty($row->image_url)) {
                $image_src = $row->image_url;
            } elseif (!empty($row->image_data)) {
                $image_src = $row->image_data;
            }
        }
    }

    /* ---------- 3. Armar HTML seguro ---------- */
    ob_start();
    ?>
    <div class="personalizacion-preview-container">
        <?php if (!empty($image_src)) : ?>
            <img src="<?php echo esc_attr($image_src); ?>" 
                 class="cart-personalizado-preview" 
                 alt="<?php esc_attr_e('Diseño personalizado', 'cpc'); ?>" />
            <div class="personalizacion-actions">
                <a href="#" 
                   class="button view-personalizacion" 
                   data-src="<?php echo esc_attr($image_src); ?>">
                    <?php _e('Ver imagen', 'cpc'); ?>
                </a>
                <a href="<?php echo esc_url($edit_url); ?>" 
                   class="button edit-personalizacion">
                    <?php _e('Editar', 'cpc'); ?>
                </a>
            </div>
        <?php else : ?>
            <p><?php _e('La imagen personalizada no está disponible.', 'cpc'); ?></p>
            <a href="<?php echo esc_url($edit_url); ?>" 
               class="button button-primary edit-personalizacion">
                <?php _e('Editar diseño', 'cpc'); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php
    $html = ob_get_clean();

    $item_data[] = array(
        'key' => __('Diseño Personalizado', 'cpc'),
        'display' => wp_kses_post($html),
    );

    return $item_data;
}

public function personalizar_thumbnail_carrito($thumbnail, $cart_item, $cart_item_key) {
  // Si el item tiene imagen personalizada, reemplazar miniatura
  if (isset($cart_item['img_personalizada'])) {
      return '<img src="' . esc_attr($cart_item['img_personalizada']) . '" class="cart-personalizado-preview" />';
  }
  
  return $thumbnail;
}

/**
 * Guardar datos personalizados en la orden
 */
public function guardar_datos_en_orden($item_id, $values) {
  if (isset($values['img_personalizada'])) {
      wc_add_order_item_meta($item_id, 'Diseño Personalizado', $values['img_personalizada']);
  }
  
  if (isset($values['personalizacion_id'])) {
      wc_add_order_item_meta($item_id, '_personalizacion_id', $values['personalizacion_id']);
  }
}

/**
 * Detectar y manejar la edición desde el carrito
 * Método mejorado para resolver problemas de desface
 */
public function detectar_edicion_del_carrito() {
    // Solo ejecutar en páginas de producto
    if (!is_product()) {
        return;
    }
    
    // Verificar parámetros en la URL
    if (isset($_GET['edit_personalizacion']) && $_GET['edit_personalizacion'] == '1') {
        $personalizacion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if (!class_exists('CuadrosPersonalizables_DB')) {
            return;
        }
        
        $db = CuadrosPersonalizables_DB::get_instance();
        $personalizacion = null;
        
        // Intentar obtener la personalización por ID
        if ($personalizacion_id) {
            $personalizacion = $db->get_personalization_by_id($personalizacion_id);
            
            // Si no se encuentra por ID, intentar obtener por producto
            if (!$personalizacion) {
                global $product;
                
                if (is_object($product)) {
                    $personalizacion = $db->get_personalization($product->get_id());
                }
            }
        } else {
            // Intentar obtener por producto si estamos en una página de producto
            global $product;
            
            // Verificar que $product es un objeto WC_Product válido
            if (!is_object($product) && is_product()) {
                // Si $product no es un objeto, intentar obtenerlo de la ID de la página actual
                $product = wc_get_product(get_the_ID());
            }
            
            if (is_object($product)) {
                $personalizacion = $db->get_personalization($product->get_id());
            }
        }
        
        // Si encontramos la personalización, pasar los datos a JavaScript
        if ($personalizacion) {
            // Definir qué datos de imagen usar (priorizar URL sobre base64)
            $image_data = '';
            if (!empty($personalizacion->image_url)) {
                $image_data = $personalizacion->image_url;
            } elseif (!empty($personalizacion->image_data)) {
                $image_data = $personalizacion->image_data;
            }
            
            ?>
            <script>
            // Datos de personalización para editar desde el carrito
            window.personalizacionDatos = {
                id: <?php echo json_encode($personalizacion->id); ?>,
                image_data: <?php echo json_encode($image_data); ?>,
                image_state: <?php echo json_encode($personalizacion->image_state); ?>
            };
            
            document.addEventListener('DOMContentLoaded', function() {
                console.log("Modo edición detectado con ID:", <?php echo json_encode($personalizacion_id); ?>);
                
                // Establecer IDs en el formulario
                const personalizacionIdInput = document.getElementById('personalizacion_id');
                if (personalizacionIdInput) {
                    personalizacionIdInput.value = <?php echo json_encode($personalizacion->id); ?>;
                }
                
                // Auto-abrir el personalizador
                const btnPersonalizar = document.getElementById('btn-personalizar');
                if (btnPersonalizar) {
                    // Indicar visualmente que hay una personalización activa
                    btnPersonalizar.classList.add('personalizado');
                    btnPersonalizar.textContent = 'Editar personalización';
                    
                    // Esperar un poco más para asegurarnos que todo está cargado
                    setTimeout(function() {
                        console.log("Abriendo personalizador automáticamente");
                        btnPersonalizar.click();
                    }, 800);
                }
            });
            </script>
            <?php
        } else {
            // No se encontró la personalización - Mostrar error o notificación
            ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.error("No se encontró la personalización con ID:", <?php echo json_encode($personalizacion_id); ?>);
                
                // Notificar al usuario
                const notice = document.createElement('div');
                notice.className = 'woocommerce-error';
                notice.textContent = 'No se encontró la personalización para editar. Por favor, intenta personalizar el producto nuevamente.';
                
                // Insertar al inicio del contenido
                const contentEl = document.querySelector('.woocommerce-notices-wrapper');
                if (contentEl) {
                    contentEl.appendChild(notice);
                } else {
                    const content = document.querySelector('.content-area, .site-content');
                    if (content) {
                        content.insertBefore(notice, content.firstChild);
                    }
                }
            });
            </script>
            <?php
        }
    }
}

/**
 * Resetear personalización mediante AJAX
 */
public function reset_personalization() {
  // Verificar nonce
  if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'personalizador_nonce')) {
      wp_send_json_error('Error de seguridad.');
  }
  
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
  
  if (!$product_id) {
      wp_send_json_error('ID de producto no válido.');
  }
  
  // Verificar que el product_id corresponde a un producto real
  $product = wc_get_product($product_id);
  if (!$product) {
      wp_send_json_error('El producto especificado no existe.');
  }
  
  // Eliminar de la base de datos
  if (!class_exists('CuadrosPersonalizables_DB')) {
      wp_send_json_error('Error del sistema: Módulo de base de datos no disponible.');
  }
  
  $db = CuadrosPersonalizables_DB::get_instance();
  $result = $db->delete_personalization($product_id);
  
  if ($result) {
      wp_send_json_success(array(
          'message' => 'La personalización ha sido eliminada correctamente.'
      ));
  } else {
      wp_send_json_error('No se encontró ninguna personalización para eliminar.');
  }
}

} // Cierre de la clase CuadrosPersonalizables_Frontend_id', $values['personalizacion
