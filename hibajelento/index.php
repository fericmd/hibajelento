<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Hibabejelentő</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Iskolai Hibabejelentő</h1>

<div class="box" id="valasztas">
    <button onclick="login('tanar')">Tanár</button>
    <button onclick="login('admin')">Admin</button>
</div>

<div class="box hidden" id="loginBox">
    <input type="password" id="pass" placeholder="Jelszó">
    <button onclick="belep()">Belépés</button>
</div>

<div class="box hidden" id="tanarBox">
    <input id="tanar" placeholder="Tanár neve">
    <input id="gep" placeholder="Gép neve">
    <textarea id="hiba" placeholder="Hiba leírása"></textarea>
    <button onclick="ment()">Beküld</button>
    <button onclick="vissza()">Vissza</button>
</div>

<div class="box hidden" id="adminBox">
    <table id="tabla">
        <tr>
            <th>Tanár</th>
            <th>Gép</th>
            <th>Hiba</th>
            <th>Dátum</th>
            <th>Státusz</th>
            <th>Törlés</th>
        </tr>
    </table>
    <button onclick="vissza()">Vissza</button>
</div>

<script>
let role = "";

function login(r){
    role = r;
    valasztas.classList.add("hidden");
    loginBox.classList.remove("hidden");
}

function belep(){
    if(role === "tanar" && pass.value === "Tanar01"){
        loginBox.classList.add("hidden");
        tanarBox.classList.remove("hidden");
    }
    if(role === "admin" && pass.value === "Admin01"){
        loginBox.classList.add("hidden");
        adminBox.classList.remove("hidden");
        frissit();
    }
}

function vissza(){
    tanarBox.classList.add("hidden");
    adminBox.classList.add("hidden");
    loginBox.classList.add("hidden");
    valasztas.classList.remove("hidden");
    pass.value = "";
}

function ment(){
    if(!tanar.value || !gep.value || !hiba.value){
        alert("Minden mezőt ki kell tölteni!");
        return;
    }

    fetch("mentes.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`tanar=${tanar.value}&gep=${gep.value}&hiba=${hiba.value}`
    }).then(() => {
        alert("Hiba mentve!");
        tanar.value = "";
        gep.value = "";
        hiba.value = "";
    });
}

function frissit(){
    fetch("lekerdez.php")
    .then(r => r.json())
    .then(data => {
        while(tabla.rows.length > 1) tabla.deleteRow(1);

        data.forEach(h => {
            const r = tabla.insertRow();
            r.insertCell().innerText = h.tanar;
            r.insertCell().innerText = h.gep;
            r.insertCell().innerText = h.hiba;
            r.insertCell().innerText = h.datum;

            const s = document.createElement("select");
            ["Új","Folyamatban","Megoldva"].forEach(v => {
                const o = document.createElement("option");
                o.value = v;
                o.text = v;
                if(v === h.status) o.selected = true;
                s.appendChild(o);
            });

            s.className = "status-" + h.status.replace(" ", "");
            s.onchange = () => {
                fetch("status.php",{
                    method:"POST",
                    headers:{"Content-Type":"application/x-www-form-urlencoded"},
                    body:`id=${h.id}&status=${s.value}`
                });
                s.className = "status-" + s.value.replace(" ", "");
            };

            r.insertCell().appendChild(s);

            const t = document.createElement("button");
            t.innerText = "❌";
            t.onclick = () => {
                if(confirm("Biztos törlöd?")){
                    fetch("torles.php",{
                        method:"POST",
                        headers:{"Content-Type":"application/x-www-form-urlencoded"},
                        body:`id=${h.id}`
                    }).then(() => frissit());
                }
            };

            r.insertCell().appendChild(t);
        });
    });
}
</script>

</body>
</html>
