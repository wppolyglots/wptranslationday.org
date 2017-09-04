jQuery(document).ready(function() {
	var panControlEnabled = false;
	if(window.matchMedia("(max-width:800px)").matches) {
		panControlEnabled = true;
	}
	var map = AmCharts.makeChart( 'gwtd_map', {
		'type': 'map',
		'projection': 'mercator',
		'addClassNames': true,
  		'dataProvider': {
			'map': 'worldHigh',
  			'images': markers,
  			'zoomLevel': 1.4,
  			'zoomLatitude': 20,
  			'zoomLongitude': 0
  		},
  		'balloon': {
	  		'color': '#FFFFFF',
	  		'fillColor': '#206480',
	  		'borderAlpha': 0,
	  		'borderColor': '#206480',
	  		'borderThickness': 0,
	  		'fontSize': 16, 
  		},
		'imagesSettings': {
			'autoZoom': true,
			'rollOverScale': 1.5,
			'selectedScale': 1.5
		},
		'zoomControl': {
			//'homeButtonEnabled': false,
			'panControlEnabled': panControlEnabled,
			'top': '50'
		},
		'areasSettings': {
			'balloonText': '',
			'unlistedAreasColor': '#90c3d3',
			'unlistedAreasOutlineColor': '#d3dde0'
		},
		'legend': {
			'divId': 'legenddiv',
			'marginRight': 27,
			'marginLeft': 27,
			'equalWidths': false,
			'backgroundAlpha': 1,
			'backgroundColor': "#FFFFFF",
			'borderColor': "#ffffff",
			'borderAlpha': 1,
			'position': 'absolute',
			'bottom': 0,
			'right': 0,
			'fontSize': 16,
			'horizontalGap': 10,
			//'data': mapLegendDatas
		}
	});

	map.addListener('clickMapObject', function(event) {
		if (event.mapObject.eventURL.length > 0) {
			window.open( event.mapObject.eventURL );
		}
	});
});