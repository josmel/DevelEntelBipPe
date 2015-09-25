$(document).ready(function(e) {
	var homeDest = $('#homeDestacado');
	homeDest.carouFredSel({
		auto: {
			play: true,
			duration: 1500	
		},
		items: {
			visible: 1,
			width: 100,												
			height: '42%'
		},
		scroll : {
			items: 1
		},
		prev: '#hdFlechaIzq',
		next: '#hdFlechaDer',											
		pagination: ".hdPagination",		
		responsive: true,
		onCreate: function () {
			
			var int=self.setInterval(function(){tamanoImagenCarrousel(homeDest)},1000);
			$(window).on('resize', function () {													
				tamanoImagenCarrousel(homeDest);																								  												}).trigger('resize');
			
			homeDest.find('img').touchwipe({
				 wipeLeft: function() { homeDest.trigger("next", 1); },
				 wipeRight: function() { homeDest.trigger("prev", 1); }													 
				
			});
			
		}											
	});


});

function tamanoImagenCarrousel(homeDest){
	var LDHeight=homeDest.find('li img').height();
	$('#hdFlechaIzq img').height(LDHeight);
	$('#hdFlechaDer img').height(LDHeight);
}