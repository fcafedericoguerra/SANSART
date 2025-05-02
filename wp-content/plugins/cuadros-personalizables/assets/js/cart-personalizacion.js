/**
 * Script mejorado para gestionar la personalización desde el carrito
 * Soluciona problemas de visualización de CSS y carga de imágenes
 */
jQuery(document).ready(function($) {
  console.log("Inicializando script de personalización para carrito...");
  
  // Detectar enlaces de personalizaciones en el carrito
  activarEnlacesPersonalizacion();
  
  // Optimizar imágenes al cargar
  optimizarImagenesCarrito();
  
  // Ejecutar cuando se actualiza el carrito
  $(document.body).on('updated_cart_totals', function() {
      console.log("Carrito actualizado - reinicializando funcionalidades");
      setTimeout(function() {
          activarEnlacesPersonalizacion();
          optimizarImagenesCarrito();
      }, 500);
  });
  
  // También volver a ejecutar después de un tiempo para manejar carga asíncrona
  setTimeout(optimizarImagenesCarrito, 1000);
  
  /**
   * Activa los enlaces de personalización en el carrito
   */
  function activarEnlacesPersonalizacion() {
      // Detectar enlaces de personalización
      const viewLinks = document.querySelectorAll('.view-personalizacion');
      const editLinks = document.querySelectorAll('.edit-personalizacion');
      
      console.log(`Inicializando ${viewLinks.length} enlaces "Ver" y ${editLinks.length} enlaces "Editar"`);
      
      // Desactivar handlers previos para evitar duplicados
      $(viewLinks).off('click');
      $(editLinks).off('click');
      
      // Agregar evento a enlaces "Ver imagen"
      $(viewLinks).on('click', function(e) {
          e.preventDefault();
          const imageUrl = $(this).attr('href');
          showImageLightbox(imageUrl);
      });
      
      // Mejorar el manejo de edición desde el carrito
      $(editLinks).on('click', function(e) {
          // Almacenar momentáneamente el hecho de que estamos editando
          localStorage.setItem('editing_from_cart', 'true');
          
          // También intentar extraer el ID de personalización de la URL
          const editUrl = $(this).attr('href');
          const urlParams = new URLSearchParams(editUrl.split('?')[1]);
          const id = urlParams.get('id');
          if (id) {
              localStorage.setItem('editing_personalization_id', id);
          }
      });
  }
  
  /**
   * Optimiza la visualización de imágenes en el carrito
   */
  function optimizarImagenesCarrito() {
      // Aplicar estilos generales
      const styles = {
          // Contenedor
          '.personalizacion-preview-container': {
              margin: '10px 0',
              textAlign: 'center'
          },
          // Imágenes
          '.cart-personalizado-preview': {
              maxWidth: '100px',
              maxHeight: '100px',
              display: 'block !important',
              margin: '5px auto',
              border: '1px solid #ddd',
              borderRadius: '4px',
              objectFit: 'contain',
              backgroundColor: '#f9f9f9'
          },
          // Contenedor de acciones
          '.personalizacion-actions': {
              marginTop: '8px',
              display: 'flex',
              justifyContent: 'center',
              gap: '5px'
          },
          // Botones
          '.view-personalizacion, .edit-personalizacion': {
              fontSize: '12px !important',
              padding: '5px 10px !important',
              lineHeight: '1.5 !important',
              height: 'auto !important',
              display: 'inline-block'
          }
      };
      
      // Aplicar estilos directamente a los elementos para evitar problemas de CSS
      Object.keys(styles).forEach(selector => {
          const elements = document.querySelectorAll(selector);
          elements.forEach(el => {
              Object.assign(el.style, styles[selector]);
          });
      });
      
      // Buscar todas las imágenes de personalización en carrito
      const imagenes = document.querySelectorAll('.cart-personalizado-preview, .personalizacion-preview-container img');
      
      console.log(`Optimizando ${imagenes.length} imágenes en el carrito`);
      
      imagenes.forEach(function(img, index) {
          // Verificar si la imagen tiene una URL válida
          const src = img.getAttribute('src') || '';
          
          // Si la imagen no tiene src o no es base64, intentar recuperarla
          if (!src || src.indexOf('data:image') !== 0) {
              console.log(`Imagen #${index} con URL no válida`);
              
              // Buscar el botón "Ver imagen" cercano para obtener la URL correcta
              const contenedor = img.closest('tr, li, .cart_item, .personalizacion-preview-container');
              if (contenedor) {
                  const viewLink = contenedor.querySelector('.view-personalizacion');
                  if (viewLink) {
                      const correctSrc = viewLink.getAttribute('href');
                      if (correctSrc && correctSrc.indexOf('data:image') === 0) {
                          console.log(`Recuperando imagen #${index} desde enlace "Ver imagen"`);
                          img.setAttribute('src', correctSrc);
                      }
                  }
              }
          }
      });
      
      // Asegurarse de que todos los enlaces View tengan el evento click correcto
      const viewLinks = document.querySelectorAll('.view-personalizacion');
      viewLinks.forEach(link => {
          $(link).off('click').on('click', function(e) {
              e.preventDefault();
              showImageLightbox(this.getAttribute('href'));
          });
      });
  }
  
  /**
   * Muestra un lightbox con la imagen personalizada
   * Versión robusta que maneja errores
   */
  function showImageLightbox(imageUrl) {
      // Eliminar cualquier lightbox existente
      $('.personalizacion-lightbox').remove();
      
      // Verificar URL de la imagen
      if (!imageUrl || imageUrl.indexOf('data:image') !== 0) {
          console.error("URL de imagen inválida para lightbox:", imageUrl);
          alert("No se puede mostrar la imagen personalizada. Por favor, intenta editarla nuevamente.");
          return;
      }
      
      // Crear lightbox con el DOM API para evitar problemas de jQuery
      const lightbox = document.createElement('div');
      lightbox.className = 'personalizacion-lightbox';
      
      const overlay = document.createElement('div');
      overlay.className = 'personalizacion-overlay';
      
      const content = document.createElement('div');
      content.className = 'personalizacion-lightbox-content';
      
      const closeBtn = document.createElement('a');
      closeBtn.href = '#';
      closeBtn.className = 'personalizacion-lightbox-close';
      closeBtn.innerHTML = '&times;';
      
      // Función para cerrar el lightbox
      function closeHandler(e) {
          e.preventDefault();
          lightbox.classList.remove('active');
          setTimeout(() => lightbox.remove(), 300);
      }
      
      closeBtn.addEventListener('click', closeHandler);
      overlay.addEventListener('click', closeHandler);
      
      // Crear imagen con manejo de errores
      const imgEl = new Image();
      
      // Indicador de carga
      const loadingEl = document.createElement('div');
      loadingEl.className = 'loading-spinner';
      loadingEl.innerHTML = '<div class="spinner"></div><p>Cargando imagen...</p>';
      content.appendChild(loadingEl);
      
      // Manejar carga exitosa
      imgEl.onload = function() {
          // Eliminar indicador de carga
          loadingEl.remove();
          
          // Añadir imagen al contenido
          content.appendChild(imgEl);
          
          // Mostrar lightbox con animación
          setTimeout(() => lightbox.classList.add('active'), 10);
      };
      
      // Manejar error de carga
      imgEl.onerror = function() {
          // Eliminar indicador de carga
          loadingEl.remove();
          
          // Mostrar mensaje de error
          const errorMsg = document.createElement('div');
          errorMsg.className = 'error-message';
          errorMsg.textContent = 'No se pudo cargar la imagen personalizada.';
          content.appendChild(errorMsg);
          
          // Mostrar lightbox con animación
          setTimeout(() => lightbox.classList.add('active'), 10);
      };
      
      // Configurar imagen
      imgEl.className = 'lightbox-image';
      imgEl.alt = 'Imagen personalizada';
      imgEl.src = imageUrl;
      
      // Armar estructura del lightbox
      content.appendChild(closeBtn);
      lightbox.appendChild(overlay);
      lightbox.appendChild(content);
      document.body.appendChild(lightbox);
      
      // Añadir estilos en línea para garantizar visualización correcta
      const styles = document.createElement('style');
      styles.textContent = `
      .personalizacion-lightbox {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 100000;
          opacity: 0;
          transition: opacity 0.3s ease;
      }
      
      .personalizacion-lightbox.active {
          opacity: 1;
      }
      
      .personalizacion-overlay {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0,0,0,0.85);
      }
      
      .personalizacion-lightbox-content {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          background-color: white;
          padding: 20px;
          border-radius: 8px;
          max-width: 90%;
          max-height: 90%;
          overflow: auto;
          box-shadow: 0 0 20px rgba(0,0,0,0.3);
          display: flex;
          flex-direction: column;
          align-items: center;
          min-width: 300px;
          min-height: 200px;
      }
      
      .lightbox-image {
          display: block;
          max-width: 100%;
          max-height: 80vh;
          margin: 0 auto;
      }
      
      .personalizacion-lightbox-close {
          position: absolute;
          top: 10px;
          right: 10px;
          font-size: 24px;
          color: #333;
          text-decoration: none;
          width: 30px;
          height: 30px;
          line-height: 30px;
          text-align: center;
          background: #f0f0f0;
          border-radius: 50%;
          z-index: 10;
          transition: all 0.2s ease;
      }
      
      .personalizacion-lightbox-close:hover {
          background: #ddd;
          color: #000;
          transform: rotate(90deg);
      }
      
      .loading-spinner {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          height: 100px;
          width: 100%;
      }
      
      .loading-spinner .spinner {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          border: 3px solid #f3f3f3;
          border-top: 3px solid #3498db;
          animation: spin 1s linear infinite;
          margin-bottom: 15px;
      }
      
      @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
      }
      
      .error-message {
          color: #e74c3c;
          background-color: #fdeaea;
          padding: 15px;
          border-radius: 6px;
          text-align: center;
          margin: 20px 0;
          font-size: 14px;
          line-height: 1.5;
      }`;
      
      document.head.appendChild(styles);
  }
  
  // Verificar si venimos de una edición del carrito
  function checkCartEdit() {
      if (localStorage.getItem('editing_from_cart') === 'true') {
          // Limpiar flag
          localStorage.removeItem('editing_from_cart');
          
          const id = localStorage.getItem('editing_personalization_id');
          localStorage.removeItem('editing_personalization_id');
          
          console.log('Detectada edición desde carrito, ID:', id);
          
          // Si estamos en una página de producto, intentar abrir el personalizador
          if (window.location.search.includes('edit_personalizacion=1')) {
              console.log('Ya en modo edición, esperando carga completa');
          } else if ($('#btn-personalizar').length > 0) {
              console.log('Abriendo personalizador tras navegar desde carrito');
              setTimeout(function() {
                  $('#btn-personalizar').click();
              }, 800);
          }
      }
  }
  
  // Ejecutar verificación en carga
  checkCartEdit();
});