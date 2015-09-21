<?php
error_reporting(E_ALL & ~E_NOTICE);
include_once "maps.php";

$maps = getMaps();

$playersList = array(
	'Mania', 'Kimimaru', 'Khaolan', 'Xadrim', 'Heracross', 'Clicking', 'Tataur', 'Djay' ,'Llelouch'
);
$currencies = array(
	array('name' => 'Orb of Transmutation', 'img' => 'currencies/Orb_of_Transmutation.png', 'amount' => 0),
	array('name' => 'Orb of Augmentation', 'img' => 'currencies/Orb_of_Augmentation.png', 'amount' => 0),
	array('name' => 'Orb of Alteration', 'img' => 'currencies/Orb_of_Alteration.png', 'amount' => 0),
	array('name' => 'Cartographer\'s Chisel', 'img' => 'currencies/Cartographer\'s_Chisel.png', 'amount' => 0),
	array('name' => 'Orb of Alchemy', 'img' => 'currencies/Orb_of_Alchemy.png', 'amount' => 0),
	array('name' => 'Chaos Orb', 'img' => 'currencies/Chaos_Orb.png', 'amount' => 0),
	array('name' => 'Vaal Orb', 'img' => 'currencies/Vaal_Orb.png', 'amount' => 0),
);
$playersCosts = array();
$playersSpent = array();
$playersBalance = array();
$totalCosts = array();


shuffle($maps);
foreach($maps as $idMap => $map) {
	$maps[$idMap]['mapLvl'] = $map['lvl'];
	$maps[$idMap]['mapName'] = $map['name'];

//	$players = $playersList;
//	shuffle($players);
	$playersAmount = rand(1,6);
	
	//new
	$playersRand = (array) array_rand($playersList, $playersAmount);
	$players = array();
	foreach($playersRand as $key => $value) {
		$players[$value] = $playersList[$value];
	}
	
//	$players = array_slice($players, 0, $playersAmount);
	$owner = array_rand($players);
	$maps[$idMap]['players'] = implode(', ', $players);
	$maps[$idMap]['owner'] = $players[$owner];

	// COSTS
	foreach($currencies as $idCurrency => $currency) {
		$maps[$idMap][$idCurrency] = mt_rand(0,15);

		#Each Players (players required contribution)
		foreach($players as $idPlayer => $player) {
			$playersCosts[$idPlayer][$idCurrency] += $maps[$idMap][$idCurrency] / $playersAmount;
		}
		
		#Map Owner (owner depense)
		$playersSpent[$owner][$idCurrency] += $maps[$idMap][$idCurrency];
		
		#Total (costs sums)
		$totalCosts[$idCurrency] += $maps[$idMap][$idCurrency];
	}
	
	$date = new Datetime();
	$maps[$idMap]['date'] = $date->format('Y-m-d h:m:s');
}

# Players Balance
foreach($currencies as $idCurrency => $currency) {
	foreach($playersList as $idPlayer => $player) {
		$playersBalance[$idPlayer][$idCurrency] = $playersSpent[$idPlayer][$idCurrency] - $playersCosts[$idPlayer][$idCurrency];
	}
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Mapping Cost</title>

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <link href="dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script> 
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>	
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<script src="main.js"></script>
	
	<!-- select2 --> 
        <link href="https://vitalets.github.io/x-editable/assets/select2/select2.css" rel="stylesheet">
        <script src="https://vitalets.github.io/x-editable/assets/select2/select2.js"></script>
	<!-- select2 bootstrap -->
        <link href="https://vitalets.github.io/x-editable/assets/select2/select2-bootstrap.css" rel="stylesheet">
	
	<style>
	.small_currencies {
		width:24px;
		padding:0px;
		margin-right:2px;
	}
	
	.select2-container-multi .select2-choices .select2-search-field input {
		min-width:120px;
	}
	</style>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Path of Exile - Mapping Cost</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
		<div class="main">
          <h1 class="page-header">Dashboard</h1>
		  <div class="row">
			<div class="col-md-4 col-md-offset-4 placeholder text-center">
              <h4>Total Costs</h4>
				<span class="text-muted">
				<?php
				foreach($currencies as $idCurrency => $currency) {
					echo $totalCosts[$idCurrency] .'<img src="'. $currencies[$idCurrency]['img'] .'" class="small_currencies" /></td>';
				}
				?>
				</span>
            </div>
		  </div>
          <div class="row placeholders">
            <div class="col-xs-6 col-sm-4 placeholder">
              <h4>Expenses</h4>
			  <table class="table text-muted">
				<?php
				foreach($playersList as $idPlayer => $player) {
					echo '<tr><td>'.$player .'</td><td>';
					foreach($currencies as $idCurrency => $currency) {
						$currencyDisplay = $playersSpent[$idPlayer][$idCurrency];
						$currencyDisplay = round($currencyDisplay, 0);
						echo $currencyDisplay .'<img src="'. $currencies[$idCurrency]['img'] .'" class="small_currencies" />';
					}
					echo '</td></tr>';
				}
				?>
				</table>
            </div>
            <div class="col-xs-6 col-sm-4 placeholder">
              <h4>Requiered contribution</h4>
              <table class="table text-muted">
				<?php
				foreach($playersList as $idPlayer => $player) {
					echo '<tr><td>'.$player .'</td><td>';
					foreach($currencies as $idCurrency => $currency) {
						$currencyDisplay = $playersCosts[$idPlayer][$idCurrency];
						$currencyDisplay = round($currencyDisplay, 0);
						echo $currencyDisplay .'<img src="'. $currencies[$idCurrency]['img'] .'" class="small_currencies" />';
					}
					echo '</td></tr>';
				}
				?>
				</table>
            </div>
            <div class="col-xs-6 col-sm-4 placeholder">
              <h4>Balance</h4>
			  <table class="table text-muted">
				<?php
				foreach($playersList as $idPlayer => $player) {
					$displayCurrencies = '';
					$balance = 0;
					foreach($currencies as $idCurrency => $currency) {
						$currencyDisplay = $playersBalance[$idPlayer][$idCurrency];
						$currencyDisplay = round($currencyDisplay, 0);
						$balance += $currencyDisplay;
						$displayCurrencies .= $currencyDisplay .'<img src="'. $currencies[$idCurrency]['img'] .'" class="small_currencies" />';
					}
					$color = $balance > 0 ? 'success' : 'danger';
					echo <<<CURRENCIES
					<tr>
						<td class="$color">$player</td>
						<td>$displayCurrencies</td>
					</tr>
CURRENCIES;
				}
				?>
				</table>
            </div>
          </div>
          <h2 class="sub-header">Mapping History</h2>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Map</th>
                  <th>Costs</th>
                  <th>Players</th>
                  <th>Owner</th>
				  <th>Date</th>
                </tr>
              </thead>
              <tbody>
<script type="text/javascript">
$(document).ready(function() {
	$.fn.editable.defaults.mode = 'popup';
	$('#map').editable({
        type: 'select',
        placement: 'right',
        source: [
			<?php
			foreach($maps as $idMap => $map) {
				$mapName = addslashes($map['name']);
				$mapLvl = addslashes($map['lvl']);
				echo "{value: $idMap, text: '$mapName ($mapLvl)'},\n";
			}
			?>
        ]
    });
	$('#players').editable({
        placement: 'right',
        source: [
			<?php
			foreach($playersList as $idPlayer => $player) {
				echo "{id: $idPlayer, text: '$player'},\n";
			}
			?>
        ],
        select2: {
           multiple: true
        }
    });
	$('#host').editable({
		type: 'select',
        placement: 'left',
        source: [
			<?php
			foreach($playersList as $idPlayer => $player) {
				echo "{value: $idPlayer, text: '$player'},\n";
			}
			?>
        ],
    });
	//automatically show next editable
	/*
	$('.editable').on('save.newuser', function(){
		var that = this;
		setTimeout(function() {
			$(that).closest('tr').next().find('.editable').editable('show');
		}, 200);
	});
	*/
});
</script>

				<tr>
					<td>#</td>
					<td>
						<a class="editable editable-click" href="#" id="map" data-type="select" data-pk="1" data-value="" data-title="Select the Map">Select the Map</a>
					</td>
					<td><a data-toggle="modal" href="#currenciesModal" class="editable editable-click">Open Currencies</a></td>
					<td><a class="editable editable-click" href="#" id="players" data-type="select2" data-pk="2" data-title="Select the Players">Select the Players</a></td>
					<td><a class="editable editable-click" href="#" id="host" data-type="select" data-pk="3" data-value="" data-title="Select the Host">Select the Host</a></td>
					<td>#</td>
				</tr>
<?php
foreach($maps as $idMap => $map) {
				echo <<<EOL
				<tr>
                  <td>$idMap</td>
                  <td>
					<img src="maps/{$map['image']}" style="width:36px" />
					<a style="color: gray; display: inline;" class="editable editable-click" href="#">{$map['mapName']} ({$map['mapLvl']})</a>
				  </td>
                  <td>
					<table>
						<tr>
EOL;
				foreach($currencies as $idCurrency => $currency) {
					if($map[$idCurrency] > 0) {
						echo '<td><img src="'. $currencies[$idCurrency]['img'] .'" class="small_currencies" /></td>';
					}
				}
				echo <<<EOL
						</tr>
						<tr>
EOL;
				foreach($currencies as $idCurrency => $currency) {
					if($map[$idCurrency] > 0) {
						echo '<td  class="text-center">'. $map[$idCurrency] .'</td>';
					}
				}

				echo <<<EOL
						</tr>
					</table>
				  </td>
                  <td>{$map['players']}</td>
                  <td>{$map['owner']}</td>
				  <td>{$map['date']}</td>
                </tr>
EOL;
			}
			?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
	
	<div class="modal fade" id="currenciesModal" role="dialog">
	<div class="modal-dialog">
	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 style="color:red;"><span class="glyphicon glyphicon-lock"></span> Login</h4>
		</div>
		<div class="modal-body">
		  <form role="form">
			<div class="form-group">
			  <label for="usrname"><span class="glyphicon glyphicon-user"></span> Username</label>
			  <input type="text" class="form-control" id="usrname" placeholder="Enter email">
			</div>
			<div class="form-group">
			  <label for="psw"><span class="glyphicon glyphicon-eye-open"></span> Password</label>
			  <input type="text" class="form-control" id="psw" placeholder="Enter password">
			</div>
			<div class="checkbox">
			  <label><input type="checkbox" value="" checked>Remember me</label>
			</div>
			<button type="submit" class="btn btn-default btn-success btn-block"><span class="glyphicon glyphicon-off"></span> Login</button>
		  </form>
		</div>
		<div class="modal-footer">
		  <button type="submit" class="btn btn-default btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
		  <p>Not a member? <a href="#">Sign Up</a></p>
		  <p>Forgot <a href="#">Password?</a></p>
		</div>
	  </div>
	</div>
	</div>
  </body>
</html>