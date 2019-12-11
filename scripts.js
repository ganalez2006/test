var app = new Vue({
	el: '#app'
	, data: {
		titulo: 'Hola mundo con Vue desde Git'
		, productos: [
			{nombre: 'manzana', cantidad: 10}
			, {nombre: 'pera', cantidad: 0}
			, {nombre: 'platano', cantidad: 10}
		]
		, nuevaProducto: ''
		, totalProductos: 0
	}
	, methods: {
		agregarProducto() {

			if (this.nuevaProducto !== '') {

				this.productos.push({nombre: this.nuevaProducto, cantidad:0});
				this.nuevaProducto = '';
			}
		}
	}
	, computed: {
		sumarProductos() {
			this.totalProductos = 0;
			for (fruta of this.productos) {
				this.totalProductos += fruta.cantidad;
			}
			return this.totalProductos;
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