document.addEventListener('DOMContentLoaded', function () {
  if (typeof sansartAjax === 'undefined' || !sansartAjax.ajaxurl) {
    console.error('El objeto sansartAjax no está disponible. Asegúrate de que wp_localize_script esté configurado correctamente en functions.php.');
    return;
  }

  const grid = document.getElementById('cuadros-artista-grid');
  const ordenSelect = document.getElementById('orden_cuadros');
  const btnVerMas = document.getElementById('ver-mas-cuadros');

  if (!grid) return;

  let pagina = parseInt(grid.dataset.pagina) || 1;
  let orden = grid.dataset.orden || 'desc';
  const artistaId = grid.dataset.artista;

  function cargarCuadros(paginaNueva = false) {
    const data = new FormData();
    data.append('action', 'sansart_get_cuadros_artista');
    data.append('artista_id', artistaId);
    data.append('pagina', pagina);
    data.append('orden', orden);

    fetch(sansartAjax.ajaxurl, {
      method: 'POST',
      body: data
    })
    .then(response => response.json())
    .then(res => {
      if (!res || !res.success) {
        console.error('Respuesta AJAX inválida:', res);
        return;
      }

      const html = (res.data && res.data.html) ? res.data.html : '';

      if (paginaNueva) {
        grid.innerHTML = '';
      }

      if (html && html.trim() !== '') {
        grid.insertAdjacentHTML('beforeend', html);
        if (btnVerMas) btnVerMas.style.display = 'inline-block';
      } else {
        if (btnVerMas) btnVerMas.style.display = 'none';
      }
    })
    .catch(err => {
      console.error('Error al cargar cuadros:', err);
    });
  }

  if (btnVerMas) {
    btnVerMas.addEventListener('click', function () {
      pagina++;
      cargarCuadros(false);
    });
  }

  if (ordenSelect) {
    ordenSelect.addEventListener('change', function () {
      orden = this.value;
      pagina = 1;
      cargarCuadros(true);
    });
  }

  cargarCuadros(true);
});