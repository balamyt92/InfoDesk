var id = 0;

function importStatus(argument) {
	$.ajax({
	  method: "GET",
	  url: "index.php?r=import%2Fimport-status",
	  data: { last_id: id}
	})
	  .done(function( data ) {
	    alert( "Data Saved: " + data );
	    id++;
	  });
}

function importRun(argument) {
	// body...
}