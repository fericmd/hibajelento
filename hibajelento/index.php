<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Hibabejelentő</title>

<style>
body { font-family: Arial; background:#f5f5f5; padding:20px }
.box { background:#fff; padding:15px; max-width:500px; margin-bottom:20px; border-radius:8px }
.hidden { display:none }
input, textarea, button, select { width:100%; margin-top:5px; padding:5px }
table { width:100%; background:#fff; border-collapse:collapse }
th, td { border:1px solid #aaa; padding:6px }
</style>
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
</div>

<div class="box hidden" id="adminBox">
<table id="tabla">
<tr><th>Tanár</th><th>Gép</th><th>Hiba</th><th>Státusz</th></tr>
</table>
</div>

<script>
let role = "";

function login(r){
    role=r;
    valasztas.classList.add("hidden");
    loginBox.classList.remove("hidden");
}

function belep(){
    const p = pass.value;
    if(role=="tanar" && p=="Tanar01"){
        loginBox.classList.add("hidden");
        tanarBox.classList.remove("hidden");
    }
    if(role=="admin" && p=="Admin01"){
        loginBox.classList.add("hidden");
        adminBox.classList.remove("hidden");
        frissit();
    }
}

function ment(){
    fetch("mentes.php",{
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:`tanar=${tanar.value}&gep=${gep.value}&hiba=${hiba.value}`
    }).then(r=>r.text()).then(t=>{
        alert("Mentve adatbázisba");
    });
}

function frissit(){
    fetch("lekerdez.php")
    .then(r=>r.json())
    .then(data=>{
        const t = tabla;
        while(t.rows.length>1)t.deleteRow(1);

        data.forEach(h=>{
            const r = t.insertRow();
            r.insertCell().innerText=h.tanar;
            r.insertCell().innerText=h.gep;
            r.insertCell().innerText=h.hiba;

            const s = document.createElement("select");
            ["Új","Folyamatban","Megoldva"].forEach(v=>{
                const o=document.createElement("option");
                o.value=v;o.text=v;
                if(v==h.status)o.selected=true;
                s.appendChild(o);
            });

            s.onchange=()=>{
                fetch("status.php",{
                    method:"POST",
                    headers:{"Content-Type":"application/x-www-form-urlencoded"},
                    body:`id=${h.id}&status=${s.value}`
                });
            };

            r.insertCell().appendChild(s);
        });
    });
}
</script>

</body>
</html>
