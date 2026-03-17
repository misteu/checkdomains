<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Domain Checker</title>

<style>
body { font-family: Arial; padding: 20px; }

h3 { margin-top: 25px; }

.section {
    margin-top: 10px;
    border: 1px solid #eee;
    padding: 10px;

    /* 🔥 multi-column layout */
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 6px;
}

.row {
    padding: 4px 6px;
    font-family: monospace;
    border-radius: 4px;
    background: #fafafa;
}

.available { color: green; font-weight: bold; }
.taken { color: red; }
.unknown { color: orange; }

#status {
    margin-top: 10px;
    font-size: 14px;
    color: #666;
}
</style>


</head>
<body>

<input id="domain" placeholder="domain">
<button onclick="check()">Check</button>
<button onclick="stop()">Stop</button>

<div id="status">Idle</div>

<h3>✅ Available</h3>
<div id="available" class="section"></div>

<h3>⚠ Unknown</h3>
<div id="unknown" class="section"></div>

<h3>❌ Taken</h3>
<div id="taken" class="section"></div>

<script>
let source;

function check() {
    let domain = document.getElementById("domain").value.trim();
    if (!domain) return alert("Enter domain");

    // clear previous results
    document.getElementById("available").innerHTML = "";
    document.getElementById("unknown").innerHTML = "";
    document.getElementById("taken").innerHTML = "";

    document.getElementById("status").textContent = "Connecting...";

    if (source) source.close();

    source = new EventSource("check.php?domain=" + encodeURIComponent(domain));

    source.onopen = function() {
        document.getElementById("status").textContent = "Streaming...";
    };

    source.onmessage = function(event) {
        let data;

        try {
            data = JSON.parse(event.data);
        } catch (e) {
            return;
        }

        if (data.status === "started") return;

        let div = document.createElement("div");
        div.className = "row";
        div.textContent = data.domain;

        if (data.available === true) {
            div.classList.add("available");
            document.getElementById("available").appendChild(div);
        } 
        else if (data.available === false) {
            div.classList.add("taken");
            document.getElementById("taken").appendChild(div);
        } 
        else {
            div.classList.add("unknown");
            document.getElementById("unknown").appendChild(div);
        }
    };

    source.onerror = function() {
        document.getElementById("status").textContent = "Connection issue…";
    };
}

function stop() {
    if (source) {
        source.close();
        document.getElementById("status").textContent = "Stopped";
    }
}
</script>

</body>
</html>
