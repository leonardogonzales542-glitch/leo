document.addEventListener('DOMContentLoaded', () => {
    
    const inputBusqueda = document.getElementById('inputBusqueda');
    const searchDropdown = document.getElementById('searchDropdown');
    const selectedProductCard = document.getElementById('selectedProductCard');
    const priceQtyRow = document.getElementById('priceQtyRow');
    const btnProcesarVenta = document.getElementById('btnProcesarVenta');

    // Elementos donde mostraremos los datos
    const prodName = document.getElementById('prodName');
    const prodDetails = document.getElementById('prodDetails');
    const prodStock = document.getElementById('prodStock');
    const prodPrice = document.getElementById('prodPrice');
    const prodUnit = document.getElementById('prodUnit');
    const prodQty = document.getElementById('prodQty');

    let currentSelectedProduct = null;

    // Búsqueda en la base de datos
    if (inputBusqueda && searchDropdown) {
        inputBusqueda.addEventListener('input', function() {
            const query = this.value.trim();
            if (query.length < 2) {
                searchDropdown.style.display = 'none';
                return;
            }

            fetch(`api_buscar_producto.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    searchDropdown.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action cursor-pointer';
                            li.style.cursor = 'pointer';
                            li.innerHTML = `<strong>${item.nombre}</strong> <small class="text-muted">(${item.codigo})</small> - Stock: ${item.stock_actual}`;
                            li.addEventListener('click', () => {
                                selectProduct(item);
                            });
                            searchDropdown.appendChild(li);
                        });
                        searchDropdown.style.display = 'block';
                    } else {
                        const li = document.createElement('li');
                        li.className = 'list-group-item text-muted';
                        li.textContent = 'No se encontraron insumos...';
                        searchDropdown.appendChild(li);
                        searchDropdown.style.display = 'block';
                    }
                })
                .catch(err => {
                    console.error("Error fetching data:", err);
                });
        });

        // Ocultar dropdown si se hace clic fuera
        document.addEventListener('click', function(e) {
            if (!inputBusqueda.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.style.display = 'none';
            }
        });
    }

    // Seleccionar producto
    function selectProduct(product) {
        currentSelectedProduct = product;
        inputBusqueda.value = product.nombre;
        searchDropdown.style.display = 'none';

        // Mostrar datos
        prodName.textContent = product.nombre;
        prodDetails.innerHTML = `Código: ${product.codigo} &nbsp;&bull;&nbsp; Unidad: ${product.unidad_medida}`;
        
        let stockVal = parseFloat(product.stock_actual);
        prodStock.textContent = `Stock: ${stockVal} u.`;
        if(stockVal <= 0) {
            prodStock.classList.remove('bg-success', 'text-white');
            prodStock.classList.add('bg-danger', 'text-white');
        } else {
            prodStock.classList.remove('bg-danger', 'text-white');
            prodStock.style.backgroundColor = '#dcfce7'; // restore default
            prodStock.style.color = '#166534';
        }

        let priceVal = parseFloat(product.precio_venta).toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
        prodPrice.textContent = priceVal;
        prodUnit.textContent = `/${product.unidad_medida}`;
        
        prodQty.value = 1;
        prodQty.max = product.stock_actual;

        // Mostrar las cards
        selectedProductCard.style.display = 'flex';
        priceQtyRow.style.display = 'flex';
    }

    // Procesar Venta
    if (btnProcesarVenta) {
        btnProcesarVenta.addEventListener('click', () => {
            if (!currentSelectedProduct) {
                Swal.fire({
                    title: 'Seleccione un insumo',
                    text: 'Debe buscar y seleccionar un insumo antes de procesar la venta.',
                    icon: 'warning',
                    confirmButtonColor: '#cd5219'
                });
                return;
            }

            const cantidad = parseInt(prodQty.value);
            if (cantidad <= 0 || cantidad > currentSelectedProduct.stock_actual) {
                Swal.fire({
                    title: 'Cantidad inválida',
                    text: 'La cantidad ingresada supera el stock disponible o es inválida.',
                    icon: 'error',
                    confirmButtonColor: '#cd5219'
                });
                return;
            }

            let priceTotal = (parseFloat(currentSelectedProduct.precio_venta) * cantidad).toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });

            Swal.fire({
                title: '¿Procesar Venta Rápida?',
                html: `Se registrará la venta de <b>${cantidad} ${currentSelectedProduct.unidad_medida}(s)</b> de <b>${currentSelectedProduct.nombre}</b> por <b>${priceTotal}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#cd5219',
                cancelButtonColor: '#737373',
                confirmButtonText: 'Sí, procesar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: '¡Venta Registrada!',
                        text: 'La venta ha sido procesada con éxito y el stock se actualizará.',
                        icon: 'success',
                        confirmButtonColor: '#cd5219'
                    }).then(() => {
                        // Limpiar formulario y variables
                        currentSelectedProduct = null;
                        inputBusqueda.value = '';
                        selectedProductCard.style.display = 'none';
                        priceQtyRow.style.display = 'none';
                    });
                }
            });
        });
    }
});
