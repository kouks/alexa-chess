<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Chess</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
	<div class="chessboard">
		<div>
			<div class="rank desc">8</div>
			<div class="rank desc">7</div>
			<div class="rank desc">6</div>
			<div class="rank desc">5</div>
			<div class="rank desc">4</div>
			<div class="rank desc">3</div>
			<div class="rank desc">2</div>
			<div class="rank desc">1</div>
		</div>
		
		<div>
			<div class="file desc">a</div>
			<div class="file desc">b</div>
			<div class="file desc">c</div>
			<div class="file desc">d</div>
			<div class="file desc">e</div>
			<div class="file desc">f</div>
			<div class="file desc">g</div>
			<div class="file desc">h</div>
		</div>

		<?php for ($i = 0; $i < 8; $i++) : ?>
			<?php for ($j = 0; $j < 8; $j++) : ?>
				<div 
					id="<?php echo $i . '-' . $j; ?>" 
					class="tile <?php echo ($j + $i) % 2 == 0 ? 'white' : 'black'; ?>"
				>
				</div>
			<?php endfor; ?>
		<?php endfor; ?>
	</div>

	<script type="text/javascript" src="main.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</body>
</html>
