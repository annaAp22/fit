<button id="add-moving-dot">Добавить точку</button>
<button id="remove-moving-dot">Удалить точку</button>
<div id="moving-wrapper" class="moving-wrapper">
    <div id="moving-dot_0" class="moving-dot active">+</div>
</div>

<style>
    .moving-wrapper {
        height:400px;
        width:400px;
        background-color:green;
        position: relative;
    }
    .moving-dot {
        position: relative;
        background-color: #fff;
        width: 30px;
        line-height: 30px;
        border-radius: 15px;
        text-align: center;
        cursor: pointer;
        font-size: 30px;
        font-weight: 700;
        user-select: none;
    }
    .moving-dot.active {
        background-color: yellow;
    }
</style>

<script>
    window.onload = addListeners();

    var mv,
            mvCords,
            minY,
            minX,
            maxY,
            maxX,
            dotInd = 1,
            activeDot;

    function addListeners(){
        movingSettings();
        activeDot.addEventListener('mousedown', mouseDown, false);
        window.addEventListener('mouseup', mouseUp, false);
        document.getElementById('add-moving-dot').addEventListener('click', addDot, true);
        document.getElementById('remove-moving-dot').addEventListener('click', removeDot, true);
    }

    function movingSettings()
    {
        mv = document.getElementById('moving-wrapper'),
                mvCords = mv.getBoundingClientRect();
        minY = mvCords.top,
                minX = mvCords.left,
                maxY = minY + mv.offsetHeight,
                maxX = minX + mv.offsetWidth,
                dotInd = 1,
                activeDot = document.getElementById('moving-dot_0');
    }

    function addDot()
    {
        var dot = document.createElement('div');
        dot.className = 'moving-dot';
        dot.id = 'moving-dot_' + dotInd;
        dotInd++;
        dot.appendChild(document.createTextNode("+"));
        mv.appendChild(dot);
        dot.addEventListener('mousedown', mouseDown, false);
    }

    function removeDot()
    {
        activeDot.parentNode.removeChild(activeDot);
    }

    function mouseUp()
    {
        window.removeEventListener('mousemove', divMove, true);
    }

    function mouseDown(e){
        activeDot = this;
        var dots = document.getElementsByClassName('moving-dot');
        [].forEach.call(dots, function(el) {
            el.className = el.className.replace(/\bactive\b/, "");
        });
        activeDot.className = "moving-dot active";
        window.addEventListener('mousemove', divMove, true);
    }

    function divMove(e){
        var div = activeDot;
        div.style.position = 'absolute';

        if(e.clientY >= minY && e.clientY <= maxY) {
            div.style.top = (e.clientY - minY - (div.offsetHeight/2)) + 'px';
        }
        if(e.clientX >= minX && e.clientX <= maxX) {
            div.style.left = (e.clientX - minX - (div.offsetWidth/2)) + 'px';
        }
    }

</script>