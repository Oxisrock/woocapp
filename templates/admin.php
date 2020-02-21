<div class="wrap">
	<h1>Woocapp New plugin</h1>
	<?php settings_errors(); ?>

	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-1">Configurar</a></li>
		<li><a href="#tab-2">Actualizaciones</a></li>
		<li><a href="#tab-3">¿Quienes somos?</a></li>
	</ul>

	<div class="tab-content">
		<div id="tab-1" class="tab-pane active">

			<form method="post" action="options.php">
				<?php 
					settings_fields( 'woocapp_options_group' );
					do_settings_sections( 'woocapp' );
					submit_button();
				?>
			</form>
			
		</div>

		<div id="tab-2" class="tab-pane">
			<h3>Actualizaciones</h3>
		</div>

		<div id="tab-3" class="tab-pane">
			<h3>¿Quienes somos?</h3>
		</div>
	</div>
</div>