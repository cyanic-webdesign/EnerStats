<?php

//	get the last 24h
$logs = array_reverse($energyMapper->getLogs(6*24));
$lastLog = $logs[count($logs)-1];

//	get the current
$currentLogs = $energyMapper->getCurrentStats();
$currentLogs = $currentLogs[0];

//	get the records
$recordUsage = $energyMapper->getRecord('current_usage');
$recordRestitution = $energyMapper->getRecord('current_restitution');

//     get the raw daily logs
$raw =  $energyMapper->getDailyRawLogs(new DateTime('yesterday'));
foreach($energyMapper->getDifferences($raw) as $rawLog) {
    $energyMapper->saveDailyLog($rawLog);
}

$daily = $energyMapper->getDailyLogs(new DateTime('- 7 days'), new DateTime('today'));
$monthly = $energyMapper->getMonthlyLogs(new DateTime('last year'), new DateTime('next month'));
$yearly = $energyMapper->getYearlyLogs();

$energyUser = $authenticate->getEnergyUser();


?>
        <title>Electra - EnerStats - Statisfy your enery usage</title>    
    </head>
    <body>
        <!-- start navigation -->           
        <nav id="navigation" class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/dashboard"><img src="images/logo-enerstats-inverted.png" width="300"></a>
                </div>
				<ul class="nav navbar-nav">
					<li class="text-center"><a href="/dashboard"><i class="fa fa-dashboard"></i><br /><small class="hidden-xs">dashboard</small></a></li>
					<li class="active text-center"><a href="/electra"><i class="fa fa-bolt"></i><br /><small class="hidden-xs">electra</small></a></li>
					<li class="text-center"><a href="/gas"><i class="fa fa-tint"></i><br /><small class="hidden-xs">gas</small></a></li>
					<li class="text-center"><a href="/instellingen"><i class="fa fa-wrench"></i><br /><small class="hidden-xs">instellingen</small></a></li>
					<li class="text-center"><a href="/uitloggen"><i class="fa fa-sign-out"></i><br /><small class="hidden-xs">uitloggen</small></a></li>						
				</ul>
            </div>
        </nav>
        
        <div id="content" class="container">
            <section>
                <header>
                    <div class="row clearfix">
                        <div class="col-md-6">
                            <h1 class="pink">Electra</h1>
                        </div>
                        <div class="col-md-6 text-right">
                            <br>
                            <h4><?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?> <a href="http://maps.google.com/?q=<?php echo $energyUser->getAddress(); ?>, <?php echo $energyUser->getZipcode(); ?> <?php echo $energyUser->getCity(); ?>" target="_blank" class="pink"><i class="fa fa-map-marker"></i></a></h4>
                        </div>
                    </div>                    
                </header>
                <hr>
				<h3>Verbruik</h3>
                <ul class="list-unstyled clearfix">
					<li class="col-md-3"><div class="well text-center">Huidig<br><h4><?php echo number_format($lastLog->getCurrentUsage(), 2); ?> kWh</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Vandaag<br><h4><?php echo number_format($currentLogs->getCurrentUsage(), 2); ?> kWh</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Dagrecord (<?php echo $recordUsage[0]->getDateCreated()->format('d-m-Y'); ?>)<br><h4><?php echo number_format($recordUsage[0]->getCurrentUsage(), 2); ?> kWh</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Totaal<br><h4><?php echo number_format($lastLog->getT1Usage() + $lastLog->getT2Usage(), 2); ?> kWh</h4></div></li>
				</ul>
				<hr>
				<h3>Teruglevering</h3>
                <ul class="list-unstyled clearfix">
					<li class="col-md-3"><div class="well text-center">Huidig<br><h4><?php echo number_format($lastLog->getCurrentRestitution(), 2); ?> kWh</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Vandaag<br><h4><?php echo number_format($currentLogs->getCurrentRestitution(), 2); ?> kWh</h4></div></li>
					<li class="col-md-3"><div class="well text-center">Dagrecord (<?php echo $recordRestitution[0]->getDateCreated()->format('d-m-Y'); ?>)<br><h4><?php echo number_format($recordRestitution[0]->getCurrentRestitution(), 2); ?> kWh</h4></div></li>					                    
                    <li class="col-md-3"><div class="well text-center">Totaal<br><h4><?php echo number_format($lastLog->getT1Restitution() + $lastLog->getT2Restitution(), 2); ?> kWh</h4></div></li>
                </ul>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 24 uur</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="electra-chart-hours" class="chart"></div>
                    </div>					
                </div>
				<hr>						
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 7 dagen</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="electra-chart-days" class="chart"></div>
                    </div>					
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen 12 maanden</h3>
                        </header>
                    </article>
                    <div class="col-md-12">
                        <div id="electra-chart-months" class="chart"></div>
                    </div>					
                </div>
                <hr>
                <div class="row clearfix">
                    <article class="container">
                        <header>
                            <h3>Afgelopen jaren</h3>
                        </header>
                    </article>
                    <div class="col-md-4">
                        <div id="electra-chart-years" class="chart"></div>
                    </div>
                    <div class="col-md-4">
                        <div id="electra-chart-pie" class="chart"></div>
                    </div>
                    <div class="col-md-4">
                        <div id="electra-chart-pie" class="chart"></div>
                    </div>					
                </div>				
            </section>
            <hr>
        </div>
        
        <footer id="footer">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-md-8 credits">
                        <p><small>EnerStats is initiated and created by Cyanic Webdesign &copy; 2014<br>
						Build with <a href="http://getbootstrap.com/" class="light-blue" target="_blank">Twitter Bootstrap</a>,
						<a href="http://fontawesome.io/" class="light-blue" target="_blank">Font Awesome</a> and the lovelyness of
						<a href="http://sass-lang.com/" class="light-blue" target="_blank">SASS</a>. Code licensed under
						<a href="https://github.com/twbs/bootstrap/blob/master/LICENSE" class="light-blue" target="_blank">MIT</a></small>
						</p>
                    </div>					
                    <div class="col-md-4 credits-logo">
                        <img width="150" src="images/logo-enerstats-inverted-small.png" alt="EnerStats"><h3 class="tagline">Statisfy your energy usage</h3>
                    </div>
                </div>
            </div>
        </footer>
        
		<!-- copyright -->
		<div class="cwcopyright">
			<a href="http://www.cyanicwebdesign.nl" title="Cyanic Webdesign - Staphorst" target="_blank"><img src="images/copyright-cyaniwebdesign.png" /></a>
		</div>
		<!-- end copyright -->	        
        
        <!-- scripts -->
		<script type="text/javascript">
		    $(function () {			
				EnerStats.electraHours = {
					container: 'electra-chart-hours',
					options: {
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,
							formatter: function() { 
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B om %H:%M:%S', this.points[0].point.x) + '</h5>';
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' kWh</strong></td></tr>';
								});	
								html += '</table>';
								return html;
							}
						},						
						series: [{
							name: 'Energieverbruik',
	                        data: <?php echo $energyMapper->toJson($logs, 'currentUsage');?>,
	                    },{
	                        name: 'Teruglevering',
	                        data: <?php echo $energyMapper->toJson($logs, 'currentRestitution', true); ?>,
	                    }]    
				   }
				};
				EnerStats.electraHours = jQuery.extend(true, {}, EnerStats.defaultAreaChart, EnerStats.electraHours);
				EnerStats.electraHours.init(EnerStats.electraHours.options);
				EnerStats.electraHours.create();
				
				EnerStats.electraDays = {
					container: 'electra-chart-days',					
					options: {
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,   
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%A %e %B %Y', this.points[0].point.x) + '</h5>';
								total = 0;
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' kWh</strong></td></tr>';
									total += entry.point.y;
								});
								if(total >= 0) {
									html += '<tr><td><br />Totaal energieverbruik</td><td class="text-right"><br /><strong>' + total.toFixed(2) + ' kWh</strong><td></tr>';
								} else {
									html += '<tr><td><br />Totale teruglevering</td><td class="text-right"><br /><strong>' + Math.abs(total).toFixed(2) + ' kWh</strong><td></tr>';
								} 
								html += '</table>';
								return html;
							}						
						},						
                        series: [{
							name: 'Energieverbruik',
							data: <?php echo($energyMapper->toJson($daily, 'currentUsage')); ?>,
						}, {
							name: 'Teruglevering',
							data: <?php echo($energyMapper->toJson($daily, 'currentRestitution', true)); ?>,
						}]
					}
				};
				EnerStats.electraDays = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.electraDays);
				EnerStats.electraDays.init(EnerStats.electraDays.options);
				EnerStats.electraDays.create();
				
				EnerStats.electraMonths = {
					container: 'electra-chart-months',				
					options: {
						xAxis: { labels: { formatter: function() { return Highcharts.dateFormat('%b %Y', this.value); }}},
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%B %Y', this.points[0].point.x) + '</h5>';
								total = 0;
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' kWh</strong></td></tr>';
									total += entry.point.y;
								});
								if(total >= 0) {
									html += '<tr><td><br />Totaal energieverbruik</td><td class="text-right"><br /><strong>' + total.toFixed(2) + ' kWh</strong><td></tr>';
								} else {
									html += '<tr><td><br />Totale teruglevering</td><td class="text-right"><br /><strong>' + Math.abs(total).toFixed(2) + ' kWh</strong><td></tr>';
								} 
								html += '</table>';
								return html;
							}						
						},							
                        series: [{
							name: 'Energieverbruik',
							data: <?php echo($energyMapper->toJson($monthly, 'currentUsage')); ?>,
						}, {
							name: 'Teruglevering',
							data: <?php echo($energyMapper->toJson($monthly, 'currentRestitution', true)); ?>,
						}]
					}
				};
				EnerStats.electraMonths = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.electraMonths);
				EnerStats.electraMonths.init(EnerStats.electraMonths.options);
				EnerStats.electraMonths.create();
				
				EnerStats.electraYears = {
					container: 'electra-chart-years',					
					options: {
						xAxis: { labels: { formatter: function() { return Highcharts.dateFormat('%Y', this.value); }}},
						tooltip: {
							useHTML: true,
							shared: true,
							borderRadius: 0,
							borderColor: '#d7dadb',
							borderWidth: 1,    
							formatter: function() {
								html = '<table width="250" class="chart-tooltip"><tr"><td class="pink" colspan="2"><h5>' + Highcharts.dateFormat('%Y', this.points[0].point.x) + '</h5>';
								total = 0;
								this.points.forEach(function(entry) {
									html += '<tr><td width="75%"><i class="fa fa-square" style="color:' + entry.series.color + ';"></i> ' + entry.series.name + '</td><td class="text-right"><strong>' + Math.abs(entry.point.y) + ' kWh</strong></td></tr>';
									total += entry.point.y;
								});
								if(total >= 0) {
									html += '<tr><td><br />Totaal energieverbruik</td><td class="text-right"><br /><strong>' + total.toFixed(2) + ' kWh</strong><td></tr>';
								} else {
									html += '<tr><td><br />Totale teruglevering</td><td class="text-right"><br /><strong>' + Math.abs(total).toFixed(2) + ' kWh</strong><td></tr>';
								} 
								html += '</table>';
								return html;
							}						
						},						
                        series: [{
							name: 'Energieverbruik',
							data: <?php echo($energyMapper->toJson($yearly, 'currentUsage')); ?>,
						}, {
							name: 'Teruglevering',
							data: <?php echo($energyMapper->toJson($yearly, 'currentRestitution', true)); ?>,
						}]
					}
				};
				EnerStats.electraYears = jQuery.extend(true, {}, EnerStats.defaultColumnChart, EnerStats.electraYears);
				EnerStats.electraYears.init(EnerStats.electraYears.options);
				EnerStats.electraYears.create();
		    });
		</script>