(function($){
  function iniciarListadoArtistas() {
    var $inputNombre = $('#filtro-nombre');
    var $selectTec   = $('#filtro-tecnica');
    var $selectFecha = $('#filtro-fecha');
    var $contenedor  = $('#listado-artistas');
    var $btnCargar   = $('#btn-cargar-mas');

    if (!$contenedor.length || !$btnCargar.length) return;

    var $tarjetasTodas = $contenedor.find('.tarjeta-artista');
    var visibles = 0;
    var porPagina = 12;
    var $ultimasFiltradas = $tarjetasTodas;

    function aplicarFiltros() {
      var texto   = ($inputNombre.val() || '').toLowerCase();
      var tecnica = ($selectTec.val() || '').toLowerCase();

      var $filtradas = $tarjetasTodas.filter(function(){
        var $el = $(this);
        var nombre = ($el.data('nombre') || '').toLowerCase();
        var tec = ($el.data('tecnica') || '').toLowerCase();

        var coincideNombre = nombre.includes(texto);
        var coincideTec = tecnica === '' || tec.includes(tecnica);

        return coincideNombre && coincideTec;
      });

      $ultimasFiltradas = $filtradas;
      ordenarPorFecha($filtradas);
      mostrarPagina($filtradas, true);
    }

    function ordenarPorFecha($filtradas) {
      var orden = $selectFecha.val() || 'desc';
      var arr = $filtradas.get();

      arr.sort(function(a, b){
        var fA = $(a).data('fecha') || '';
        var fB = $(b).data('fecha') || '';
        return orden === 'desc' ? (fA < fB ? 1 : -1) : (fA > fB ? 1 : -1);
      });

      $.each(arr, function(i, el){
        $contenedor.append(el);
      });
    }

    function ordenarPorNombre($filtradas) {
      var arr = $filtradas.get();

      arr.sort(function(a, b) {
        var nA = $(a).data('nombre') || '';
        var nB = $(b).data('nombre') || '';
        return nA.localeCompare(nB);
      });

      $.each(arr, function(i, el){
        $contenedor.append(el);
      });
    }

    function mostrarPagina($filtradas, reset) {
      if (reset) visibles = 0;

      $tarjetasTodas.hide().removeClass('animar-entrada');

      var siguientes = $filtradas.slice(visibles, visibles + porPagina);

      // Mostrar estado de carga con spinner
      $btnCargar.html(`
        <span class="spinner" style="display:inline-block;width:18px;height:18px;margin-right:6px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin 0.6s linear infinite;"></span>
        Cargando...
      `).prop('disabled', true);

      setTimeout(function () {
        siguientes.each(function(){
          $(this).fadeIn(200).addClass('animar-entrada');
        });

        visibles += siguientes.length;

        if (visibles >= $filtradas.length) {
          $btnCargar.hide();
        } else {
          $btnCargar.html('Cargar más').prop('disabled', false).show();
        }
      }, 400);
    }

    $btnCargar.on('click', function(e){
      e.preventDefault();
      mostrarPagina($ultimasFiltradas, false);
    });

    // Animación CSS y spinner
    $('<style>')
      .text(`
        .animar-entrada {
          opacity: 0;
          transform: translateY(20px);
          animation: fadeUp 0.5s ease forwards;
        }
        @keyframes fadeUp {
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `)
      .appendTo('head');

    function debounce(fn, delay) {
      var timer;
      return function(){
        clearTimeout(timer);
        timer = setTimeout(fn, delay);
      };
    }

    $inputNombre.on('input', debounce(aplicarFiltros, 300));
    $selectTec.on('change', aplicarFiltros);
    $selectFecha.on('change', aplicarFiltros);

    // Init: ordenar por nombre alfabéticamente
    ordenarPorNombre($tarjetasTodas);
    mostrarPagina($tarjetasTodas, true);

    //console.log("✅ listado-artistas actualizado con orden alfabético y estado de carga");
  }

  // Elementor Hook
  $(window).on('elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction('frontend/element_ready/global', iniciarListadoArtistas);
  });

  // Fallback si no hay Elementor
  $(document).ready(function(){
    if (typeof elementorFrontend === 'undefined') {
      iniciarListadoArtistas();
    }
  });

})(jQuery);
