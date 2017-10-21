if (typeof String.prototype.endsWith != "function") { String.prototype.endsWith = function(a) {
        return this.indexOf(a, this.length - a.length) !== -1 } }
var table = [];
var targetIndex = 0;
var camera, scene, renderer;
var controls;
var radius = 100,
    theta = 0;
var rotate = true;
var objects = [];
var targets = { table: [], sphere: [], helix: [], grid: [] };
var initEffect = "table";
if (signEffect.sphere) { 
    initEffect = "sphere" }
if (signEffect.helix) { 
    initEffect = "helix" }
if (signEffect.grid) { 
    initEffect = "grid" }
getImgs();
init();
animate();


function init() { camera = new THREE.PerspectiveCamera(35, window.innerWidth / window.innerHeight, 1, 10000);
    camera.position.z = 3000;
    scene = new THREE.Scene();
    for (var g = 0; g < table.length; g++) {
        var h = document.createElement("div");
        h.className = "element";
        var m = document.createElement("a");
        m.href = table[g][3];
        m.setAttribute("data-lightbox", "roadtrip");
        m.setAttribute("data-showImageNumberLabel", "false");
        m.setAttribute("title", table[g][4]);
        var d = new Image();
        d.src = table[g][0];
        m.appendChild(d);
        h.appendChild(m);
        var f = new THREE.CSS3DObject(h);
        f.position.x = Math.random() * 4000 - 2000;
        f.position.y = Math.random() * 4000 - 2000;
        f.position.z = Math.random() * 4000 - 2000;
        scene.add(f);
        objects.push(f);
        var f = new THREE.Object3D();
        f.position.x = (table[g][1] * 140) - 1330;
        f.position.y = -(table[g][2] * 180) + 990;
        targets.table.push(f);
        d.addEventListener("load", function(a) { resizeImg(this, 120, 120) }, false) }
    if (targets.sphere) {
        var c = new THREE.Vector3();
        for (var g = 0, e = objects.length; g < e; g++) {
            var k = Math.acos(-1 + (2 * g) / e);
            var b = Math.sqrt(e * Math.PI) * k;
            var f = new THREE.Object3D();
            f.position.x = 800 * Math.cos(b) * Math.sin(k);
            f.position.y = 800 * Math.sin(b) * Math.sin(k);
            f.position.z = 800 * Math.cos(k);
            c.copy(f.position).multiplyScalar(2);
            f.lookAt(c);
            targets.sphere.push(f) } }
    if (targets.helix) {
        var c = new THREE.Vector3();
        for (var g = 0, e = objects.length; g < e; g++) {
            var k = g * 0.175 + Math.PI;
            var f = new THREE.Object3D();
            f.position.x = 900 * Math.sin(k);
            f.position.y = -(g * 8) + 450;
            f.position.z = 900 * Math.cos(k);
            c.x = f.position.x * 2;
            c.y = f.position.y;
            c.z = f.position.z * 2;
            f.lookAt(c);
            targets.helix.push(f) } }
    if (targets.grid) {
        for (var g = 0; g < objects.length; g++) {
            var f = new THREE.Object3D();
            f.position.x = ((g % 5) * 400) - 800;
            f.position.y = (-(Math.floor(g / 5) % 5) * 400) + 800;
            f.position.z = (Math.floor(g / 25)) * 1000 - 2000;
            targets.grid.push(f) } }
    renderer = new THREE.CSS3DRenderer();
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.domElement.style.position = "absolute";
    document.getElementById("container").appendChild(renderer.domElement);
    var j = document.getElementById("table");
    j.addEventListener("click", function(a) { transform(targets.table, 2000) }, false);
    var j = document.getElementById("sphere");
    j.addEventListener("click", function(a) { transform(targets.sphere, 2000) }, false);
    var j = document.getElementById("helix");
    j.addEventListener("click", function(a) { transform(targets.helix, 2000) }, false);
    var j = document.getElementById("grid");
    j.addEventListener("click", function(a) { transform(targets.grid, 2000) }, false);
    transform(targets[initEffect], 2500);
    window.addEventListener("resize", onWindowResize, false) }

function transform(a, e) { TWEEN.removeAll();
    for (var c = 0; c < objects.length; c++) {
        var b = objects[c];
        var d = a[c];
        new TWEEN.Tween(b.position).to({ x: d.position.x, y: d.position.y, z: d.position.z }, Math.random() * e + e).easing(TWEEN.Easing.Exponential.InOut).start();
        new TWEEN.Tween(b.rotation).to({ x: d.rotation.x, y: d.rotation.y, z: d.rotation.z }, Math.random() * e + e).easing(TWEEN.Easing.Exponential.InOut).start() }
    new TWEEN.Tween(this).to({}, e * 2).onUpdate(render).start() }

function onWindowResize() { camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight) }

function animate() { requestAnimationFrame(animate);
    TWEEN.update();
    render() }

function render() {
    var a = Date.now() * 0.0002;
    if (rotate) { camera.position.x = Math.cos(a) * 1500;
        camera.position.z = Math.sin(a) * 3000 }
    camera.lookAt(scene.position);
    renderer.render(scene, camera) }

function resizeImg(f, d, e) { srcWidth = f.width;
    srcHeight = f.height;
    var c = 1;
    if (srcWidth > 0 && srcHeight > 0) {
        if (srcWidth / srcHeight >= d / e) {
            if (srcWidth > d) { c = d / srcWidth } } else {
            if (srcHeight > e) { c = e / srcHeight } } }
    var b = srcWidth * c;
    var a = srcHeight * c;
    f.style.width = b.toString() + "px";
    f.style.height = a.toString() + "px";
    if (b < d) { f.style.paddingLeft = ((d - b) / 2).toString() + "px" }
    if (a < e) { f.style.paddingTop = ((e - a) / 2).toString() + "px" } }

function getImgs() {
    var e = 1;
    var a = 1;
    for (var d = 0; d < arrfiles.length; d++) {
        var c = [];
        c[0] = arrfiles[d]["head"];
        c[1] = a++;
        c[2] = e;
        var b = c[0];
        if (c[0].endsWith("/132")) { b = c[0].substring(0, c[0].lastIndexOf("/")) + "/0" }
        c[3] = b;
        c[4] = arrfiles[d]["nickname"];
        table[d] = c;
        if (a > 18) { a = 1;
            e++ } } };
