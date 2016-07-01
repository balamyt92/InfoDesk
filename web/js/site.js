var id = 0;

function importStatus() {
    $.ajax({
        method: "GET",
        url: "index.php?r=import%2Fimport-status",
        data: {last_id: id}
    })
        .done(function (data) {
            alert("Data Saved: " + data);
            id++;
        });
}

function searchFirm() {
    var str = document.getElementById('search-line').value;
    console.log(str);
    $.ajax({
        method: "GET",
        url: "index.php?r=site%2Fsearch",
        data: {str: str}
    })
        .done(function (data) {
            console.log(data);
            var render = document.getElementById('search-firm-result');
            render.innerHTML(data.serialize());

            // com
        });
}