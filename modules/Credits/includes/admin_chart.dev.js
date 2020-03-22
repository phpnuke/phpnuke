var chart = AmCharts.makeChart("chartdiv",
{
	"type": "serial",
	"categoryField": "date",
	"fontFamily": "Tahoma",
	"marginRight": 40,
	"marginLeft": 40,
	"autoMarginOffset": 20,
	"mouseWheelZoomEnabled":true,
	"colors": [
		"#176b0a",
		"#ed9cb3",
		"#0a136b",
	],
	"categoryAxis": {
		"gridPosition": "start",
		"autoGridCount": false
	},
	"chartCursor": {
		"enabled": true,
		"cursorPosition": "mouse",
		"showNextAvailable":true,
		"cursorColor": "#888888"
	},
	"export": {
		"enabled": true
	},
	"chartScrollbar": {
		"enabled": true
	},
	"trendLines": [],
	"graphs": [
		{
			"bullet": "round",
			"hideBulletsCount": 60,
			"id": "AmGraph-1",
			"title": chart_data.deposits,
			"valueField": "column-1",
			"balloonText": "<span style='font-size:15px;'>[[value]]</span>"
		},
		{
			"bullet": "square",
			"hideBulletsCount": 60,
			"id": "AmGraph-2",
			"title": chart_data.withdraw,
			"valueField": "column-2",
			"balloonText": "<span style='font-size:15px;'>[[value]]</span>"
		},
		{
			"bullet": "round",
			"hideBulletsCount": 60,
			"id": "AmGraph-3",
			"title": chart_data.total_transactions,
			"valueField": "column-3",
			"balloonText": "<span style='font-size:15px;'>[[value]]</span>"
		}
	],
	"balloon": {
		"borderThickness": 1,
		"shadowAlpha": 0
	},
	"guides": [],
	"valueAxes": [
		{
			"id": "ValueAxis-1",
			"title": chart_data.amount
		}
	],
	"allLabels": [],
	"legend": {
		"enabled": true,
		"useGraphSettings": true
	},
	"titles": [
		{
			"id": "Title-1",
			"size": 15,
			"face": 'tahoma',
			"text": chart_data.statistics
		}
	],
	"responsive": {
		"enabled": true
	},
	"dataProvider": chart_data.dataProvider_contents
}
);

chart.addListener("rendered", zoomChart);

zoomChart();

function zoomChart() {
	chart.zoomToIndexes(chart.dataProvider.length - 26, chart.dataProvider.length - 1);
}

// note, each data item has "bullet" field.

var columnChartData = chart_data.user_dataproviders;
AmCharts.ready(function () {
	// SERIAL CHART
	chart = new AmCharts.AmSerialChart();
	chart.dataProvider = columnChartData;
	chart.categoryField = "name";
	chart.startDuration = 1;
	chart.addTitle(chart_data.chart_users_title, 12);


	// AXES
	// category
	var categoryAxis = chart.categoryAxis;
	categoryAxis.labelRotation = 0;
	categoryAxis.gridPosition = "start";

	// value
	// in case you don't want to change default settings of value axis,
	// you don't need to create it, as one value axis is created automatically.

	// GRAPH


	var graph = new AmCharts.AmGraph();
	graph.valueField = "points";
	graph.customBulletField = "avatar"; // field of the bullet in data provider
	graph.bulletOffset = 16; // distance from the top of the column to the bullet
	graph.bulletSize = 34; // bullet image should be rectangle (width = height)
	graph.type = "column";
	graph.fillAlphas = 0.8;
	graph.lineAlpha = 0;
	graph.balloonText = "<div style='font-size:13px;direction:rtl;'>"+chart_data.chart_users+"</div>";
	chart.addGraph(graph);


	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorAlpha = 0;
	chartCursor.zoomable = false;
	chartCursor.categoryBalloonEnabled = false;
	chart.addChartCursor(chartCursor);

	chart.creditsPosition = "top-right";

	chart.write("chartdiv2");
});