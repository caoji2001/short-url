function show_url() {
    const ajax_node = document.getElementById("ajax_div");

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            ajax_node.innerHTML = xmlhttp.responseText;
        }
    }

    xmlhttp.open("POST", "/api/worker.php", true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("input_url=" + document.getElementById("input_url").value);
}