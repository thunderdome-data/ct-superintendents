<!--START MAP EMBED -->
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css" />
<link rel="stylesheet" href="<?php echo home_url(); ?>/data/lib/css/smoothness/jquery-ui-1.8.24.custom.css" />

<!--[if lte IE 8]>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.ie.css" />
<![endif]-->

<link rel="stylesheet" href="<?php echo home_url(); ?>/data/lib/css/mapTable.css" />



	<div id="containter">
	<h4>This map and table show Digital First Media publications. Select a map pin or a row in the table to learn more.</h4>
		<div id="map" class="floatLeft map"></div>
			<div id="gridContainer" class="floatLeft gridContainer">
				<div id="searchField" style="float:left;"></div>
				<div id="filterCategory"></div>
				<div id="dfm" class="tableContainer clearBoth"></div>
			</div>
	</div>

<script src="http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/jquery-1.8.2.min.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/jquery-ui-1.8.24.custom.min.js"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/handlebars-1.0.rc.1.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/underscore-min.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/backbone-min.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/thunderdome.js"></script>
<script src="<?php echo home_url(); ?>/data/lib/js/oms.min.js"></script>

<script type="text/javascript">
//google visualization libs we need
google.load('visualization', '1.1', {packages: ['controls','table']});
</script>

<script>
var mapConfig = new Thunderdome.mapConfig( {
	mapTiles:		'http://tile.stamen.com/toner/{z}/{x}/{y}.png', //CloudMade map tiles to use
	attribution:	'Map tiles by <a href="http://stamen.com">Stamen Design</a>,'
					+ 'under <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a>.'
					+ 'Data by <a href="http://openstreetmap.org">OpenStreetMap</a>,'
					+ 'under <a href="http://creativecommons.org/licenses/by-sa/3.0">CC BY SA</a>.', //Text for map attribution
	//Start lat, lon and zoom level for map init
	startLat:		39.6, 
	startLon:		-96.75,
	startZoom:		4,
	mapDiv:			'map', //id of div where map will go
	
	//fields from JSON data we want to show in popups and modal window (for detail)
	//should correspond to placeholders in handlebars templates
	popupItems: 	['Site','City','State','URL','Brand Twitter handle','Brand Facebook URL','Newseum front page link'],
	popupTemplate:	'popupText', //id of handlebars template script

	modalDiv:		'modal', //id of div used by jquery dialog ui
	modalWidth:		300, //width of modal window
	modalHeight:	'auto', //height of modal window
	modalTitle:		'property',
	modalItems: 	['Site','City','State','URL','Brand Twitter handle','Brand Facebook URL','Newseum front page link'],   

	modalTemplate:	'modalText', //id of handlebars template script
	//field from JSON data to use for marker key
	//should be unique; used to retrieve markers for callbacks
	markerKey:		'Site' ,
	showTable:		true
} );
//config for the datagrid
var tableConfig = new Thunderdome.tableConfig({
	//IDs of divs where pieces will go
	mainDiv: 		'gridContainer',
	showSearch:		true,
	showCategory:	true,
	searchDiv: 		'searchField',
	tableDiv: 		'dfm',
	//table header config
	//'label' is what shows in that column head
	//'type' is the data type (used for sorting, etc.)
	//TODO: try to abstract this to make config simpler
	tableHeader:	[
						{'label': 'Property', 'type': 'string'},
						{'label': 'State', 'type': 'string'},
						{'label': 'Division', 'type': 'string'}
						
					],
	//fields from JSON data we want to show in table
	tableCols:		['Site','State', 'MNG/JRC'],

	//table column we want search field to filter
	//should be name of the label in tableHeader above
	fieldToSearch: 	'Property',
	categoryToFilter: 	'Division',

	searchLabel:	'Search properties', //label text added to search field
	categoryLabel:	'Filter by division', //label text added to category dropdown
	searchCSSClass:	'searchFieldText', //css applied to search field text
	categoryCSSClass:	'categoryFilterText', //css applied to category dropdown text
	headerCSSClass:	'', //css applied to header
	rowCSSClass:	'' //css applied to rows
});


//callback function that draws map and grid
var drawMapAndGrid = function() {
	var collection;
	Thunderdome.csv("http://insidethunderdome.com/data/DFMPROPERTYMAP.csv", function(data) {
	collection = new Backbone.Collection(data);
	

	Thunderdome.data = collection;
	Thunderdome.mapConfig = mapConfig;
	Thunderdome.tableConfig = tableConfig;
	var theMap = Thunderdome.makeMap();
	var theTable = Thunderdome.makeDataTable(theMap.getMarkers,theMap.getMap);

	});
}
//we need to wait till all the google libraries are ready, then fire the callback
google.setOnLoadCallback(drawMapAndGrid);
</script>

<!--handlebars template to build map popups-->

<script id="popupText" type="text/x-handlebars-template">
  <b><a href="{{URL}}">{{Site}}</a></b><br />
  {{City}}, {{State}}<br />
  {{#if [Brand Facebook URL]}}
	Facebook: <a href="{{[Brand Facebook URL]}}" target="_blank">Our page</a> <br />
  {{/if}}
  {{#if [Brand Twitter handle]}}
	Twitter: {{[Brand Twitter handle]}} <br />	
  {{/if}}
  {{#if [Newseum front page link]}}
	<a href="{{[Newseum front page link]}}" target="_blank">Today's front page</a>
  {{/if}}
</script>
<!-- END MAP EMBED -->