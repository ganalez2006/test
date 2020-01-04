var app = new Vue({
	el: '#app'
	, data() {
		return {
			titulo: 'Lista de compras'
			, productos: []
			, nuevoProducto: ''
			, totalProductos: 0
		}
	}
	, methods: {
		agregarProducto() {

			if (this.nuevoProducto !== '') {

				this.productos.push({nombre: this.nuevoProducto, cantidad:0});
				this.nuevoProducto = '';
				this.guardarProductos();
			}
		}
		, eliminarProducto(i) {
			this.productos.splice(i, 1);
			this.guardarProductos();
		}
		, sumarProducto(i) {
			this.productos[i].cantidad++;
			this.guardarProductos();
		}
		, restarProducto(i) {

			if (this.productos[i].cantidad > 0)
				this.productos[i].cantidad--;
			
			this.guardarProductos();
		}
		, validarProducto(i) {
			this.productos[i].cantidad = (this.productos[i].cantidad === '') 
										? 0 
										: (this.productos[i].cantidad < 0) 
										? 0 
										: this.productos[i].cantidad;
			this.guardarProductos();
		}
		, guardarProductos() {
			let parsed = JSON.stringify(this.productos);
			localStorage.setItem('productos', parsed);
		}
	}
	, computed: {
		sumarProductos() {

			this.totalProductos = 0;

			for (var producto of this.productos) {
				
				this.totalProductos += producto.cantidad;
			}

			return this.totalProductos;
		}
	}
	, mounted() {
		if(localStorage.getItem('productos')) {
			try {
				this.productos = JSON.parse(localStorage.getItem('productos'));
			} catch(e) {
				localStorage.removeItem('productos');
			}
		}
		document.getElementById('app').classList.remove("hide");
	}
	, watch: {
		productos() {
			this.guardarProductos();
		}
	}
});


async function getPosts() {

	// read our JSON
	var misCabeceras = new Headers();

	var miInit = { 
		method: 'GET'
		, headers: misCabeceras
		, mode: 'cors'
		, cache: 'default'
	};
	
	let response = await fetch('//demo.wp-api.org/wp-json/wp/v2/posts', miInit);
	let user = await response.json();

	console.debug(user);
}

getPosts();