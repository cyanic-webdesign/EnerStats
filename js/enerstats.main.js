//     init namespace
var EnerStats = EnerStats || {};

//     default area chart
EnerStats.defaultAreaChart = {
    container: null,
    chart: null,
    defaults: {
        chart: { type: 'area' },
        title: { text: false },
        yAxis: { title: { text: 'kWh'} },
        xAxis: { type: 'datetime' },
        credits: { enabled: false },
        colors: ['#2c3e50', '#6dbcdb'],
        legend: false,
        plotOptions: { area: { marker: { enabled: false }, }, },	             
    },
    init: function(options) {
        this.chart = jQuery.extend({}, this.defaults, options);
        this.chart.chart.renderTo = this.container;
    },
    create: function() { new Highcharts.Chart(this.chart); }
}

//default column chart
EnerStats.defaultColumnChart = {
    container: null,
    chart: null,
    defaults: {
        chart: { type: 'column' },
        title: { text: false },
        yAxis: { title: { text: 'kWh' }, },
        xAxis: { type: 'datetime' },
        colors: ['#2c3e50', '#6dbcdb'],
        credits: { enabled: false },
        legend: false, 
        plotOptions: { column: { stacking: 'normal', dataLabels: { enabled: true, color: '#ffffff' }}}
    },
    init: function(options) {
        this.chart = jQuery.extend({}, this.defaults, options);
        this.chart.chart.renderTo = this.container;
    },
    create: function() { new Highcharts.Chart(this.chart); }
}


Highcharts.setOptions({
    lang: {
       months: ['januari', 'Feburari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'Decemeber'],
       weekdays: ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'],
       shortMonths: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec']
   },
   global: { useUTC: false, timezoneOffset: 1 }
});	

