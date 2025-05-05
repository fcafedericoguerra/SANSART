/*  Personalizador de cuadros – versión parche 25-abr-2025
 *  Cambios implementados en esta edición:
 *  1. initCanvas() → fija también width/height vía CSS para que el lienzo no se encoja.
 *  2. cargarMockup() → reescala areaObj usando el mismo factor «scale» que la imagen.
 *  3. obtenerTamañoProducto() → busca cualquier select de variación cuyo nombre incluya «tamano/tamaño»
 *     y recalcula DPI al cambiar la variación.
 *  (Resto del código sin cambios para mantener compatibilidad.)
 */

document.addEventListener("DOMContentLoaded", function () {
  /* ------------------------------ Elementos DOM ------------------------------ */
  const openBtn  = document.getElementById("btn-personalizar");
  const popup    = openBtn ? document.getElementById("popup-personalizar") : null;
  const closeBtn = popup   ? document.getElementById("cerrar-modal")       : null;
  let   confirmBtn = null; // se obtiene cuando el popup ya es visible

  const inputFoto   = document.getElementById("foto_subida");
  const inputHidden = document.getElementById("img_personalizada");
  const mockupDataEl= document.getElementById("mockup-data");
  const fileLabel   = document.querySelector(".file-selected-name");
  const zoomSlider  = document.getElementById("zoomRange");
  const zoomIn  = document.querySelector(".zoom-in");
  const zoomOut = document.querySelector(".zoom-out");
  const rotateBtn = document.getElementById("rotate90Btn");
  const personalizacionIdField = document.getElementById("personalizacion_id");

  if (!openBtn || !popup || !mockupDataEl) {
    console.error("Elementos esenciales no encontrados");
    return;
  }

  /* --------------------------- Variables de entorno -------------------------- */
  let personalizacionId = personalizacionIdField ? personalizacionIdField.value : 0;
  const ajaxUrl  = typeof personalizadorVars !== "undefined" ? personalizadorVars.ajaxurl  : ajaxurl;
  const nonce    = typeof personalizadorVars !== "undefined" ? personalizadorVars.nonce    : "";
  const productId= typeof personalizadorVars !== "undefined" ? personalizadorVars.product_id: 0;

  /* ------------------------------ Estado global ------------------------------ */
  let fabricCanvas = null;
  window.fabricCanvas = null;

/* --------------------------- Datos del mock-up ----------------------------- */
const mockupUrl = mockupDataEl.getAttribute("data-mockup-url")  || "";
const areaJson   = mockupDataEl.getAttribute("data-mockup-area") || "";
let areaObj      = { x: 0, y: 0, width: 0, height: 0 };

try {
  if (areaJson) areaObj = JSON.parse(areaJson);
} catch (e) {
  console.error("Área JSON inválida", e);
}

// ← Aquí guardamos la versión “raw”, sin escalar
const rawArea = { ...areaObj };


  /* -------------------------------- Flags UI --------------------------------- */
  let userImg  = null;
  let editMode = false;
  let currentZoom = 1;

  /* =========================== Funciones principales ========================= */
  
  /* ============================= Funciones base ============================== */
  function mostrarCargando(){
    const existing=document.querySelector('.fullscreen-loading');
    if(existing) return;
    const d=document.createElement('div');
    d.className='fullscreen-loading loading-indicator';
    d.innerHTML='<div class="spinner"></div><p>Cargando…</p>';
    document.body.appendChild(d);
  }
  function ocultarCargando(){
    const e=document.querySelector('.fullscreen-loading');
    if(e) e.remove();
  }
  function mostrarNotificacion(msg){
    const n=document.createElement('div');
    n.className='personalizado-notificacion';
    n.textContent=msg;
    document.body.appendChild(n);
    setTimeout(()=>n.classList.add('show'),10);
    setTimeout(()=>n.remove(),4000);
  }

  /* ------------------------------- Canvas init ------------------------------- */
  function initCanvas(){
    if (fabricCanvas){ fabricCanvas.clear(); fabricCanvas.dispose(); }
    const canvasElement = document.getElementById("mockupCanvas");
    if (!canvasElement){ console.error("<canvas> no encontrado"); return null; }

    const fixedWidth = 800, fixedHeight = 600;
    canvasElement.width  = fixedWidth;
    canvasElement.height = fixedHeight;
    canvasElement.style.width  = fixedWidth  + "px";
    canvasElement.style.height = fixedHeight + "px";

    fabricCanvas = new fabric.Canvas("mockupCanvas", {
      width: fixedWidth,
      height: fixedHeight,
      preserveObjectStacking: true,
      selection: false
    });
    window.fabricCanvas = fabricCanvas;
    return fabricCanvas;
  }

  /* ----------------------------- Cargar mock‑up ----------------------------- */
  function cargarMockup(url) {
    return new Promise((resolve, reject) => {
      if (!url)          return reject("URL mockup vacía");
      if (!fabricCanvas) return reject("Canvas no disponible");
  
      // 1) Resetea el área al raw inicial
      areaObj = { ...rawArea };
  
      // 2) URL absoluta + cache-buster
      let abs = /^https?:/.test(url) ? url
              : window.location.origin + (url.startsWith("/") ? url : "/" + url);
      abs += (abs.includes("?") ? "&" : "?") + "nocache=" + Date.now();
  
      fabric.Image.fromURL(abs, img => {
        if (!img) return reject("No se cargó mockup");
  
        // 3) Escalar y centrar la imagen
        const cw = 800, ch = 600;
        fabricCanvas.setWidth(cw);
        fabricCanvas.setHeight(ch);
  
        const scale = Math.min(cw / img.width, ch / img.height) * 0.95;
        img.set({
          scaleX:  scale,
          scaleY:  scale,
          originX: "center",
          originY: "center",
          left:    cw / 2,
          top:     ch / 2
        });
        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));
  
        // 4) Obtener bounds reales del mock-up
        const b = img.getBoundingRect(true, true);
        const bx = b.left, by = b.top;
  
        // 5) Escalar el área (ahora sí, desde rawArea)
        areaObj = {
          x:      bx + areaObj.x * scale,
          y:      by + areaObj.y * scale,
          width:  areaObj.width  * scale,
          height: areaObj.height * scale
        };
  
        // 6) Elimina rect anteriores y dibuja el nuevo
        fabricCanvas.getObjects('rect')
          .filter(o => !o.selectable && o.stroke === 'red')
          .forEach(o => fabricCanvas.remove(o));
  
        const rect = new fabric.Rect({
          left:           areaObj.x,
          top:            areaObj.y,
          width:          areaObj.width,
          height:         areaObj.height,
          fill:           "rgba(0,0,0,0)",
          stroke:         "red",
          strokeWidth:    2,
          strokeDashArray:[5,5],
          selectable:     false,
          evented:        false
        });
        fabricCanvas.add(rect);
  
        resolve();
      }, { crossOrigin: "anonymous" });
    });
  }
  
  /* -------------------------- Zoom & movimiento cover ------------------------- */
  function limitarMovimientoCover(img, area, canvas){
    if(!img || !area) return;
    const bound = img.getBoundingRect(true,true);
    const minLeft = area.x + area.width  - bound.width ;
    const maxLeft = area.x;
    const minTop  = area.y + area.height - bound.height;
    const maxTop  = area.y;
    if(bound.width < area.width){ img.scaleX *= area.width / bound.width; img.scaleY = img.scaleX; }
    if(bound.height< area.height){ img.scaleX *= area.height/ bound.height; img.scaleY = img.scaleX; }
    if(img.left < minLeft) img.left = minLeft;
    if(img.left > maxLeft) img.left = maxLeft;
    if(img.top  < minTop ) img.top  = minTop;
    if(img.top  > maxTop ) img.top  = maxTop;
  }
  function setupZoomControls(){
    if(!zoomSlider) return;
    zoomSlider.disabled=false;
    zoomSlider.addEventListener('input',()=>{
      const z = Number(zoomSlider.value);
      if(userImg){ userImg.scaleX = userImg.scaleY = z; limitarMovimientoCover(userImg, areaObj, fabricCanvas); fabricCanvas.renderAll(); }
    });
    if(zoomIn)  zoomIn.onclick=()=>{ zoomSlider.value = Math.min( +zoomSlider.value + 0.1, 3).toFixed(2); zoomSlider.dispatchEvent(new Event('input')); };
    if(zoomOut) zoomOut.onclick=()=>{ zoomSlider.value = Math.max( +zoomSlider.value - 0.1, 0.1).toFixed(2); zoomSlider.dispatchEvent(new Event('input')); };
  }

  /* ---------------------- DPI helpers / tamaño variación ---------------------- */
  function obtenerTamañoProducto(){
    const sel=document.querySelector('select[name^="attribute_"][name*="tamano" i]');
    if(sel&&sel.value){ const m=sel.value.match(/(\d+)\s*[x×]\s*(\d+)/i); if(m) return `${m[1]}x${m[2]}`; }
    return "30x30";
  }
  document.addEventListener('change',e=>{
    if(e.target.matches('select[name^="attribute_"][name*="tamano" i]') && userImg){ verificarResolucion({ width:userImg.width, height:userImg.height }); }
  });

  /* ----------------- Verificar resolución versus medida cuadro ---------------- */
  function verificarResolucion(img) {
    // 1) Extraemos la medida «30x30» o similar
    const size = obtenerTamañoProducto()
      .split('x')
      .map(n => parseInt(n, 10));
    if (size.length !== 2) {
      console.warn('Medida cuadro inválida');
      return true;
    }
  
    // 2) Calculamos el mínimo en px (DPI reducido a 75)
    const [cmW, cmH] = size;
    const dpi = 75; 
    const pxW = Math.round((cmW / 2.54) * dpi);
    const pxH = Math.round((cmH / 2.54) * dpi);
  
    // 3) Comprobamos si la imagen cumple
    const ok = img.width >= pxW && img.height >= pxH;
  
    // 4) Preparamos el mensaje
    const msg = document.createElement('div');
    msg.className = 'resolucion-mensaje ' + (ok ? 'resolucion-ok' : 'resolucion-error');
    msg.innerHTML = ok
      ? `✓ Resolución adecuada: ${img.width}×${img.height}px para ${cmW}×${cmH}cm (mín. ${pxW}×${pxH}px)`
      : `⚠️ Resolución baja: ${img.width}×${img.height}px (se recomiendan ${pxW}×${pxH}px)`;
  
    // 5) Limpiamos antiguos mensajes y mostramos el nuevo
    document.querySelectorAll('.resolucion-mensaje').forEach(el => el.remove());
    const rightCol = document.querySelector('.col-right');
    if (rightCol) rightCol.prepend(msg);
  
    // 6) Deshabilitamos o no el botón Confirmar
    const confirmBtn = document.getElementById('confirmar-personalizacion');
    if (confirmBtn) {
      confirmBtn.disabled = !ok;
      confirmBtn.classList.toggle('disabled', !ok);
    }
  
    return ok;
  }
  

  /* ------------------------------ Guardar AJAX ------------------------------- */
  function guardarPersonalizacion() {
    // Verificar que tenemos imagen para guardar
    if (!userImg) {
      console.error('Error: No hay imagen para guardar');
      return Promise.reject('No hay imagen para guardar');
    }
    
    // Verificar canvas
    if (!fabricCanvas) {
      console.error('Error: Canvas no disponible');
      return Promise.reject('Canvas no disponible');
    }
    
    try {
      // Generar imagen final con alta calidad
      const finalImg = fabricCanvas.toDataURL({
        format: 'png',
        quality: 1,
        multiplier: 2 // Para mejor resolución
      });
      
      // Crear objeto con estado simple y limpio para evitar problemas de JSON
      const imageState = {
        left: Math.round(userImg.left),
        top: Math.round(userImg.top),
        scaleX: parseFloat(userImg.scaleX.toFixed(4)),
        scaleY: parseFloat(userImg.scaleY.toFixed(4)),
        angle: parseFloat((userImg.angle || 0).toFixed(2)),
        areaSimple: { 
          x: Math.round(areaObj.x), 
          y: Math.round(areaObj.y), 
          width: Math.round(areaObj.width), 
          height: Math.round(areaObj.height) 
        }
      };
      
      // Mejor manejo de errores
      console.log('Preparando para guardar personalización');
      
      return new Promise((resolve, reject) => {
        jQuery.ajax({
          url: ajaxUrl,
          type: 'POST',
          data: {
            action: 'save_personalization',
            security: nonce,
            product_id: productId,
            image_data: finalImg,
            image_state: JSON.stringify(imageState)
          },
          dataType: 'json',
          success: function(response) {
            if (response && response.success) {
              console.log('Guardado exitoso:', response.data);
              resolve(response.data);
            } else {
              console.error('Error en respuesta:', response);
              reject(response && response.data ? response.data : 'Error al guardar');
            }
          },
          error: function(xhr, status, error) {
            console.error('Error AJAX:', status, error);
            console.log('Respuesta del servidor:', xhr.responseText);
            reject('Error de conexión. Intenta nuevamente.');
          }
        });
      });
    } catch (error) {
      console.error('Error al preparar datos:', error);
      return Promise.reject('Error al preparar imagen para guardar');
    }
  }
  

  /* --------------------------- Personalización previa ------------------------- */
  function cargarPersonalizacion(extra={}){
    return jQuery.post(ajaxUrl,{ action:'load_personalization', product_id:productId, security:nonce, ...extra });
  }
  function cargarPersonalizacionGuardada(){
    if (!personalizacionId) 
      return Promise.resolve();
  
    return cargarPersonalizacion({ id: personalizacionId })
      .then(res => {
        if (!res.success) 
          throw new Error('No hay personalización previa');
        
        // Parseamos el JSON que nos devolvió el servidor
        const state = typeof res.data.image_state === 'string'
          ? JSON.parse(res.data.image_state)
          : res.data.image_state;
  
        return mostrarImagenPrevisualizada(res.data.image_data, state);
      })
      .catch(err => {
        console.error('Error al cargar personalización previa:', err);
      });
  }
  

  /* ---------------------- Mostrar imagen guardada/base64 ---------------------- */
  /**
 * @param {string} imageData  Base64 de la imagen
 * @param {object} state      Objeto con left, top, scaleX, scaleY, angle, areaSimple
 */
/**
 * @param {string} imageData Base64 de la imagen
 * @param {object} state     { left, top, scaleX, scaleY, angle, areaSimple, canvasDimensions }
 */
function mostrarImagenPrevisualizada(imageData, state) {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => {
      // 1) limpia la imagen anterior
      if (userImg) fabricCanvas.remove(userImg);

      // 2) crea nueva con estado exacto
      userImg = new fabric.Image(img, {
        left:    state.left,
        top:     state.top,
        scaleX:  state.scaleX,
        scaleY:  state.scaleY,
        angle:   state.angle || 0,
        originX: 'center',
        originY: 'center',
        selectable: true,
        evented:    true
      });
      fabricCanvas.add(userImg);

      // 3) clip-path con coords originales
      const clip = new fabric.Rect({
        left:               state.areaSimple.x,
        top:                state.areaSimple.y,
        width:              state.areaSimple.width,
        height:             state.areaSimple.height,
        absolutePositioned: true
      });
      userImg.clipPath = clip;

      // 4) reactiva zoom y render
      setupZoomControls();
      fabricCanvas.renderAll();
      resolve();
    };
    img.onerror = () => reject('No se pudo cargar la imagen');
    img.src = imageData;
  });
}




  /* -------------------------- Colocar imagen en área -------------------------- */
  function colocarImagenEnArea(img) {
    // Calcular el centro del área editable
    const cx = areaObj.x + areaObj.width / 2;
    const cy = areaObj.y + areaObj.height / 2;
    
    // Calcular escala para ajustar la imagen al área
    // Usamos factor de escala ligeramente mayor para cubrir bien el área
    const scaleX = (areaObj.width / img.width) * 1.05;
    const scaleY = (areaObj.height / img.height) * 1.05;
    const scale = Math.max(scaleX, scaleY);
    
    // Posicionar la imagen en el centro del área editable
    img.set({
      left: cx,
      top: cy,
      scaleX: scale,
      scaleY: scale,
      originX: 'center',
      originY: 'center'
    });
    
    // Aplicar clipPath (recorte) al área editable
    img.clipPath = new fabric.Rect({
      left: areaObj.x,
      top: areaObj.y,
      width: areaObj.width,
      height: areaObj.height,
      absolutePositioned: true
    });
    
    // Si hay un input de zoom, actualizarlo
    if (zoomSlider) {
      zoomSlider.value = scale;
      zoomSlider.disabled = false;
    }
    
    // Actualizar canvas
    fabricCanvas.renderAll();
    
    // Establecer como objeto activo
    fabricCanvas.setActiveObject(img);
  }

  function setupImageControls(img){
    img.setControlsVisibility({ mt:false,mb:false,ml:false,mr:false, bl:false,br:false,tl:false,tr:false, mtr:true });
    [ 'moving','rotating','scaling','modified' ].forEach(evt=> img.on(evt,()=>limitarMovimientoCover(img,areaObj,fabricCanvas)) );
    fabricCanvas.setActiveObject(img);
  }

  /* ------------------------ Abrir popup y flujo completo ---------------------- */
  openBtn.addEventListener('click',()=>{
    popup.style.display='block';
    setTimeout(()=>{
      if(typeof fabric==='undefined'){
        const s=document.createElement('script');
        s.src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js';
        s.onload=()=>initCanvasAndLoadAll();
        document.head.appendChild(s);
      }else initCanvasAndLoadAll();
    },10);
  });

  function initCanvasAndLoadAll(){
    fabricCanvas=initCanvas(); if(!fabricCanvas) return;
    editMode=Boolean(personalizacionId); mostrarCargando();
    cargarMockup(mockupUrl).then(()=> editMode?cargarPersonalizacionGuardada():null ).finally(ocultarCargando);
  }

  /* ------------------------------ Subir imagen ------------------------------- */
  if (inputFoto) {
    inputFoto.addEventListener('change', e => {
      const file = e.target.files[0];
      if (!file) return;
      
      // Actualizar nombre del archivo en la UI
      if (fileLabel) {
        fileLabel.textContent = file.name;
      }
      
      // Mostrar indicador de carga
      mostrarCargando();
      
      // Leer el archivo
      const reader = new FileReader();
      reader.onload = ev => {
        // Imagen temporal para verificar resolución
        const imgTemp = new Image();
        imgTemp.onload = () => {
          console.log("Imagen cargada con resolución:", imgTemp.width, "x", imgTemp.height);
          
          // Verificar resolución adecuada
          if (!verificarResolucion(imgTemp)) {
            ocultarCargando();
            return;
          }
          
          // Cargar la imagen en Fabric
          fabric.Image.fromURL(ev.target.result, fbImg => {
            // Remover imagen anterior si existe
            if (userImg) {
              fabricCanvas.remove(userImg);
            }
            
            // Guardar referencia
            userImg = fbImg;
            
            // Configurar controles
            setupImageControls(userImg);
            
            // Añadir al canvas
            fabricCanvas.add(userImg);
            
            // Colocar en área editable
            colocarImagenEnArea(userImg);
            
            // Configurar zoom
            setupZoomControls();
            
            // Asegurar que el canvas se actualice
            fabricCanvas.renderAll();
            
            // Ocultar cargando
            ocultarCargando();
          }, { crossOrigin: 'anonymous' });
        };
        
        // Manejar error de carga
        imgTemp.onerror = () => {
          console.error("Error al cargar imagen");
          ocultarCargando();
          mostrarErrorEnCanvas("No se pudo cargar la imagen. Verifica el formato.");
        };
        
        imgTemp.src = ev.target.result;
      };
      
      // Manejar error de lectura
      reader.onerror = () => {
        console.error("Error al leer archivo");
        ocultarCargando();
        mostrarErrorEnCanvas("Error al leer el archivo.");
      };
      
      reader.readAsDataURL(file);
    });
  }

  /* ---------------------------- Rotar 90° rápido ------------------------------ */
  if (rotateBtn) {
    rotateBtn.onclick = function() {
      if (userImg) {
        rotarImagenManteniendoCentro(userImg, 90);
      }
    };
  }

  /* ------------------------------- Confirmar ------------------------------- */
  // Manejar el botón Confirmar
document.addEventListener('click', e => {
  if (!e.target.matches('#confirmar-personalizacion')) return;
  
  // Verificar si hay imagen
  if (!userImg) {
    alert('Por favor, sube una imagen primero.');
    return;
  }
  
  // Si el botón está deshabilitado, mostrar mensaje
  if (e.target.disabled || e.target.classList.contains('disabled')) {
    alert('La imagen tiene baja resolución. Por favor, sube una imagen de mayor calidad.');
    return;
  }
  
  // Mostrar indicador de carga
  mostrarCargando();
  
  // Guardar personalización
  guardarPersonalizacion()
    .then(data => {
      console.log('Personalización guardada:', data);
      
      // Guardar en campos ocultos
      if (inputHidden) {
        inputHidden.value = data.image_data || '';
      }
      
      if (personalizacionIdField && data.id) {
        personalizacionIdField.value = data.id;
      }
      
      // Actualizar botón personalizar
      if (openBtn) {
        openBtn.classList.add('personalizado');
        openBtn.textContent = 'Editar personalización';
      }
      
      // Cerrar popup
      popup.style.display = 'none';
      
      // Ocultar cargando
      ocultarCargando();
      
      // Mostrar notificación
      mostrarNotificacion('¡Tu personalización está lista!');
      
      // Habilitar botón agregar al carrito
      const addToCartBtn = document.querySelector('.single_add_to_cart_button');
      if (addToCartBtn) {
        addToCartBtn.disabled = false;
        addToCartBtn.classList.remove('disabled');
      }
    })
    .catch(error => {
      console.error('Error al guardar:', error);
      ocultarCargando();
      
      // Crear mensaje de error
      const errorMsg = typeof error === 'string' ? error : 'Error al guardar. Por favor, intenta nuevamente.';
      
      // Mostrar error en la interfaz
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error-message';
      errorDiv.style.color = '#e74c3c';
      errorDiv.style.backgroundColor = '#fdeaea';
      errorDiv.style.padding = '15px';
      errorDiv.style.borderRadius = '6px';
      errorDiv.style.marginBottom = '15px';
      errorDiv.style.textAlign = 'center';
      errorDiv.innerHTML = `<strong>Error:</strong> ${errorMsg}`;
      
      // Insertar en columna derecha
      const rightCol = document.querySelector('.col-right');
      if (rightCol) {
        // Eliminar errores previos
        const prevError = rightCol.querySelector('.error-message');
        if (prevError) prevError.remove();
        
        // Insertar al inicio
        rightCol.insertBefore(errorDiv, rightCol.firstChild);
        
        // Auto-eliminar después de 10 segundos
        setTimeout(() => {
          if (errorDiv.parentNode) {
            errorDiv.remove();
          }
        }, 10000);
      } else {
        alert(errorMsg);
      }
    });
});

  function mostrarErrorEnCanvas(msg){
    const colLeft = document.querySelector('.col-left');
    if(!colLeft) return;
    const err = document.createElement('div');
    err.className = 'error-message';
    err.textContent = msg;
    colLeft.appendChild(err);
  }
  
  /* ----------------------------- Cerrar modal ------------------------------ */
  if(closeBtn){ closeBtn.addEventListener('click',e=>{ e.preventDefault(); popup.style.display='none'; }); }

  /**
 * Rota la imagen manteniendo su posición centrada en el área editable
 * @param {Object} img - La imagen de Fabric.js a rotar
 * @param {Number} angle - Ángulo en grados (normalmente 90)
 */
function rotarImagenManteniendoCentro(img, angle) {
  if (!img || !areaObj || !fabricCanvas) return;
  
  // Calcular centro del área editable
  const areaCenterX = areaObj.x + areaObj.width / 2;
  const areaCenterY = areaObj.y + areaObj.height / 2;
  
  // Preservar escala actual
  const currentScaleX = img.scaleX;
  const currentScaleY = img.scaleY;
  
  // Aplicar rotación
  img.angle = (img.angle || 0) + angle;
  
  // Forzar centrado
  img.set({
    left: areaCenterX,
    top: areaCenterY,
    originX: 'center',
    originY: 'center',
    scaleX: currentScaleX,
    scaleY: currentScaleY
  });
  
  // Actualizar coordenadas y recorte
  img.setCoords();
  
  // Aplicar clipPath (recorte) al área editable
  img.clipPath = new fabric.Rect({
    left: areaObj.x,
    top: areaObj.y,
    width: areaObj.width,
    height: areaObj.height,
    absolutePositioned: true
  });
  
  // Verificar si después de rotar la imagen cubre completamente el área
  const bound = img.getBoundingRect(true, true);
  
  // Si no cubre completamente, ajustar escala
  if (bound.width < areaObj.width || bound.height < areaObj.height) {
    const scaleX = (areaObj.width / bound.width) * 1.05;
    const scaleY = (areaObj.height / bound.height) * 1.05;
    const newScale = Math.max(scaleX, scaleY);
    
    img.set({
      scaleX: newScale * currentScaleX,
      scaleY: newScale * currentScaleY
    });
    
    // Actualizar coordenadas
    img.setCoords();
  }
  
  // Actualizar canvas
  fabricCanvas.renderAll();
}
});