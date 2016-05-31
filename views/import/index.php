<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\jui\ProgressBar;

// Запуск проверки статауса импорта при переходе на страницу
$script = <<< JS
$(document).ready(function() {
	$.ajax({
	  method: "GET",
	  url: "index.php?r=import%2Fimport-status",
	  data: { last_id: id}
	})
	  .done(function( data ) {
	    alert( "Data Saved: " + data );
	    id++;
	  });
});
JS;
$this->registerJs($script);
?>
<h1>Импорт даных из старой базы</h1>
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Здесь отображен статус папки импорта <code>/import</code>.</h3>
        </div>
        <div class="panel-body">
	    	<table class="table">
	    		<thead>
	    			<tr>
	    				<th>Файл</th>
	    				<th>Статус</th>
	    			</tr>
	    		</thead>
	    		<tbody>
	    		<?php 
				    $files = ['CarBodyModelGroupsEN.txt', 'CarMarksEN.txt', 
							  'CarBodyModelsEN.txt', 'CarModelGroupsEN.txt',
							  'CarENDetailNames.txt', 'CarModelsEN.txt',
							  'CarEngineAndBodyCorrespondencesEN.txt', 'CarPresenceEN.txt',
							  'CarEngineAndModelCorrespondencesEN.txt', 'CatalogNumbersEN.txt',
							  'CarEngineModelGroupsEN.txt', 'Firms.txt', 'CarEngineModelsEN.txt',
							  'CarENLinkedDetailNames.txt', 'ServicePresence.txt',
							  'CarMarkGroupsEN.txt', 'Services.txt',];
					while ($files) {
						$file = array_shift($files);
						$path = __DIR__ . '/../../import/';
					    ?>
	    			<tr>
	    				<td>
	    					<?php echo '<span class="lable">' . $file . '</span>'; ?>
	    				</td>
	    				<td>
							<?php
							if(is_readable($path . $file)) {
								echo '<span class="label label-success">Доступен</span>';
							} else {
								echo '<span class="label label-danger">Не доступен</span>';
							} 
							?>
	    				</td>
	    			</tr>
	    			<?php 
	    			}
	    			?>
			    </tbody>
	    	</table>
        </div>
  	</div>
	</div>		    	
<div class="col-md-6">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Элементы управления</h3>
        </div>
        <div class="panel-body">
          <?php
			echo ProgressBar::widget([
			    'clientOptions' => [
			        'value' => 0,
			    ],
			]);          
          ?>
			<br>
			<button type="button" class="btn btn-primary" onclick="importStatus()">Запуск</button>
			<span #id="import-status" class="label label-warning">Не запущенн</span>
          	<br>
	      	<div class="form-group col-md-12">
			  <label for="comment">Лог процесса:</label>
			  <textarea class="form-control" rows="20" id="comment" readonly>
			  	
			  </textarea>
			</div>
        </div>
  	</div>
</div>
