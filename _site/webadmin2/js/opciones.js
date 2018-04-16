jQuery(function(){
	
	
var opciones = new Vue({
      
	  el: '#opciones',
	  
	  data: {
		  
		  opcionesTotales: null,
		  opcionesVivienda: {}
		  
	  },
	  
	  computed: {
		

		  
	  },
	   
	  methods: {
		  
		  hasOption: function(option){
			
				return (this.opcionesVivienda.indexOf(option) != -1);
			  
		  },
		  
		  addOption(opcion){

			this.opcionesVivienda.push(opcion);
			  
		  },
		  
		  removeOption(opcion){
			  
			let index = this.opcionesVivienda.indexOf(opcion);
			this.opcionesVivienda.splice(index, 1);
			  
		  },
		  
		  getViviendasTotales() {
			  
			  const vm = this;
			  
			  axios.get('get_opciones.php?id=' + $('#formId').val())
			  .then(function (response) {
					vm.opcionesTotales = response.data;
				})
		  }

		  
	  },
	  
	  mounted: function() {
		 
			$savedOpciones = $('#opciones_json').val();
			this.opcionesVivienda = ($savedOpciones) ? JSON.parse($savedOpciones) : [];
			
			this.getViviendasTotales();

			
			
	  },
	  
	  watch: {
		  
		  opcionesVivienda: function(newVal, oldVal){
			  
			  $('#opciones_json').val(JSON.stringify(newVal));

			  
		  }
		  
	  }
	  
});

	
});