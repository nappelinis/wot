<?php

require_once('core.php');

echo '<!DOCTYPE html><html><head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';

?>
<script>
	function editStratFunc() {
		var object = jQuery.parseJSON(document.getElementById('editStrat').value);
		//alert(JSON.stringify(object));
		
		//Setting of upper form values
		$('#strat_map option:contains(' + object.map + ')').prop({selected: true});
		$('#strat_name').val(object.name);
		$('#strat_description').val(object.description);
		$('#strat_creator option:contains(' + object.creator + ')').prop({selected: true});
		$('#strat_link').val(object.link);
		$('#strat_stratsketchID').val(object.stratsketchID);
		$('#strat_type').val(object.type);
		$('#strat_format').val(object.format);
		$('#strat_tier option:contains(' + object.tier +')').prop({selected: true});
		$('#strat_cardinal').val(object.cardinal);
		
		//ID set
		$('#ID').val(object.ID);
		
		//Button change
		$('#add_strat').val('Update Strategy');	
		
		//Show clear
		$('#clear').show();
	}
	
	function clearStratFunc() {
		
		//Clearing set values
		$('#strat_name').val('');
		$('#strat_description').val('');
		$('#strat_link').val('');
		$('#strat_stratsketchID').val('');
		$('#strat_type').val(0);
		$('#strat_format').val('');
		$('#strat_tier').val(10);
		$('#strat_cardinal').val(0);
		
		$('#add_strat').val('Add Strategy');
		
		//ID set, should allow for Add
		$('#ID').val('');
	}
	

</script>
<?php


$rq = (object)$_POST;
//pre($rq);

$maps = WOT::getMaps();
sort($maps);

$clanMembers = WOT::getClanMembers();

foreach($maps as $map) {
	$strat = new strat();
	$strats = $strat->get_maps_by_name($map['name_i18n']);
	
	
	//pre($strats);
	
	$count_tier10 = 0;
	$count_tier8 = 0;
	$count_tier6 = 0;
	
	foreach($strats as $strat) {
		if($strat->ID == NULL) continue;
		else {
			if($strat->tier == 10) $count_tier10++;
			elseif($strat->tier == 8) $count_tier8++;
			elseif($strat->tier == 6) $count_tier6++;
		}
	}

	$clean_maps_array[$map['name_i18n']] = str_replace("'", "", $map['name_i18n']);
	//$maps_array[$map['arena_id']] = $map['name_i18n'].($map_count > 0 ? ' ('.$map_count.')' : '');
	
	//new will break preg_match below
	$maps_array[$map['name_i18n']] = $map['name_i18n'].($count_tier10+$count_tier8+$count_tier6 > 0 ? ' -- '.$count_tier10.'-'.$count_tier8.'-'.$count_tier6 : '');
}

echo '<div class="container">';
echo '<br />';
echo WOT::select('map', $maps_array, NULL, (isset($rq->map) ? $rq->map : NULL));
echo '<input type="submit" name="submit" id="submit" value="Select" />';
echo '</div>';

$table_header = array('Map', 'Name', 'Description', 'Creator', 'Link', 'StratSkecth ID', 'Type', 'Format', 'Tier', 'Cardinal', 'Actions');

echo '<div class="container" style="width: 100%;"><table>';

echo '<tr>';
foreach($table_header as $header_item) {
	echo '<th>'.$header_item.'</th>';
}
echo '</tr>';

echo '<tr>';
echo '<td>'.WOT::select('strat_map', $clean_maps_array, (isset($rq->map) ? $rq->map : NULL), NULL).'</td>';
echo '<td><input type="text" size="20" name="strat_name" id="strat_name" value="" /></td>';
echo '<td><input type="text" size="20" name="strat_description" id="strat_description" value="" /></td>';
echo '<td>'.WOT::select('strat_creator', $clanMembers, NULL, NULL, true).'</td>';
echo '<td><input type="text" size="20" name="strat_link" id="strat_link" value="" /></td>';
echo '<td><input type="text" size="20" name="strat_stratsketchID" id="strat_stratsketchID" value="" /></td>';
echo '<td>'.WOT::select('strat_type', WOT::getGameModes()).'</td>';
echo '<td><input type="text" size="5" name="strat_format" id="strat_format" value="" placeholder="ex.: 7v7" /></td>';
echo '<td>'.WOT::select('strat_tier', WOT::getTiers()).'</td>';
echo '<td>'.WOT::select('strat_cardinal', WOT::getCardinal()).'</td>';
echo '<td><input type="hidden" id="ID" name="ID" value="" /><input type="submit" name="add_strat" id="add_strat" class="btn btn-primary btn-xs" value="Add Strategy" />&nbsp;&nbsp;
	<input type="button" class="clear" id="clear" onclick="clearStratFunc()" value="Clear" style="display: none; "/></td>';
echo '</tr>';
echo '</table></div>';



//Display strategies for a set map that has been selected
if(isset($rq->submit)) {

	if(isset($rq->map)) {

		$strat = new strat();
		$map_name = preg_replace('#\s--\s\(\d*\-\d*\-\d*\)#', '', $rq->map);
		$current_map_strat = $strat->get_maps_by_name($map_name);
	
		echo '<div class="container" style="width: 100%;"><div class="row"><div class="col-md-10"><table class="table table-striped">';
		echo '<tr>';
		foreach($table_header as $header_item) {
			echo '<th>'.$header_item.'</th>';
		}
		echo '</tr>';
		if(count($current_map_strat) > 0 && $current_map_strat[0]->ID != NULL) {
			foreach($current_map_strat as $ID => $ms) {	
				echo '<tr>'
				. '<td>'.$ms->map.'</td>'
				. '<td>'.$ms->name.'</td>'
				. '<td>'.$ms->description.'</td>'
				. '<td>'.$ms->creator.'</td>'
				. '<td>'.(!empty($ms->link) ? '<a target="_blank" href="'.$ms->link.'">Strat Link</a>' : '').'</td>'
				. '<td>'.$ms->stratsketchID.'</td>'
				. '<td>'.WOT::getGameModes($ms->type).'</td>'
				. '<td>'.$ms->format.'</td>'
				. '<td>'.WOT::getTiers($ms->tier).'</td>'
				. '<td>'.WOT::getCardinal($ms->cardinal).'</td>'
				. '<td>
					<input type="button" class="btn btn-primary btn-xs" onclick="editStratFunc()" value="Edit" />
					<input type="hidden" class="editStrat" id="editStrat" value="'.htmlentities(json_encode($ms)).'" />
					</td>'		
				.'</tr>';
			}
		}
		else {
			echo '<tr><td colspan="'.count($table_header).'">No strats found for '.$map_name.'</td></tr>';
		}
		echo '</table></div></div></div>';
	}
}

//Adding a new strategy
if(isset($rq->add_strat)) {
	
	$add = true;
	$strat = new strat();
	
	if(isset($rq->ID) && !empty($rq->ID)) {
		$add = false;
		$strat = $strat->get($rq->ID);
	}

	$strat->name = $rq->strat_name;
	$strat->description = $rq->strat_description;
	$strat->creator = $rq->strat_creator;
	$strat->map = $rq->strat_map;
	$strat->link = $rq->strat_link;
	$strat->stratsketchID = $rq->strat_stratsketchID;
	$strat->type = $rq->strat_type;
	$strat->format = $rq->strat_format;
	$strat->tier = $rq->strat_tier;
	$strat->cardinal = $rq->strat_cardinal;
	
	
	if($add) {
		$strat->add();
	}
	else {
		$strat->update();
	}
}

echo '</form>';
echo '</body>';

?>

