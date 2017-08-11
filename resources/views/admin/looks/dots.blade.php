<style>
    .moving-wrapper {
        margin-top: 20px;
        position: relative;
    }
    .moving-dot {
        position: absolute;
        width: 38px;
        line-height: 38px;
        background-color: #ffffff;
        border-radius: 20px;
        text-align: center;
        cursor: pointer;
        font-size: 20px;
        font-weight: 700;
        user-select: none;
        z-index: 3;
        color: grey;
    }
    .moving-dot:before {
        content: "";
        position: absolute;
        top: -9px;
        left: -9px;
        display: block;
        width: 56px;
        height: 56px;
        border: 1px solid #fff;
        border-radius: 30px;
    }
    .moving-dot.active {
        color: #000000;
    }
    .dot-related {
        display: none;
    }
    .dot-related.active {
        display: block;
    }
</style>

<script>
    window.onload = addListeners();

    var mv,
        dotInd,
        activeDot;

    function addListeners(){
        mv = document.getElementById('moving-wrapper');
        window.addEventListener('mouseup', mouseUp, false);
        document.getElementById('add-moving-dot').addEventListener('click', addDot, true);
        document.getElementById('remove-moving-dot').addEventListener('click', removeDot, true);
        document.getElementById('image').addEventListener('change', addImage, false);

        // remove drop field
        setTimeout(function(){
            var drop = document.getElementsByClassName('ace-file-container');
            if(drop.length > 1) {
                drop[0].innerHTML = drop[1].innerHTML;
                drop[0].className = 'ace-file-container hide-placeholder selected';
                drop[0].title = '';
                drop[1].parentNode.removeChild(drop[1]);
            }
        }, 500)

        var dots = document.getElementsByClassName('moving-dot');
        dotInd = dots.length + 1;
        [].forEach.call(dots, function(el) {
            el.addEventListener('mousedown', mouseDown, false);
        });

    }

    function addDot(e)
    {
        e.preventDefault();

        if(dotInd <= 10) {
            var dot = document.createElement('div');
            dot.className = 'moving-dot';
            dot.id = 'moving-dot_' + dotInd;
            dot.appendChild(document.createTextNode(dotInd.toString()));

            // Hidden inputs
            var inputX = document.createElement('input');
            inputX.type = 'hidden';
            inputX.name = 'dots['+ dotInd +'][left]';
            inputX.className = 'left';
            dot.appendChild(inputX);

            var inputY = document.createElement('input');
            inputY.type = 'hidden';
            inputY.name = 'dots['+ dotInd +'][top]';
            inputY.className = 'top';
            dot.appendChild(inputY);

            mv.prepend(dot);
            dot.addEventListener('mousedown', mouseDown, false);

            document.getElementById('form-field-7'+dotInd).disabled = false;
            var related = document.getElementById('form-field-7'+dotInd);
            related.disabled = false;
            var event = new Event('chosen:updated');
            related.dispatchEvent(event);
            document.getElementById('dot-related_' + dotInd).className = 'form-group dot-related active';

            dotInd++;
        }
    }

    function removeDot(e)
    {
        e.preventDefault();
//        if(activeDot) {
//            activeDot.parentNode.removeChild(activeDot);
//            activeDot = null;
//        }
        if(dotInd > 1) {
            dotInd--;
            document.getElementById('dot-related_'+dotInd).className = "form-group dot-related";
            var related = document.getElementById('form-field-7'+dotInd);
            related.disabled = true;
            related.value = '';
            var event = new Event('chosen:updated');
            related.dispatchEvent(event);
            var dot = document.getElementById('moving-dot_' + dotInd);
            dot.parentNode.removeChild(dot);
        }
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
        var rect = mv.getBoundingClientRect();
        var top = e.clientY - rect.top - (activeDot.offsetHeight/2),
            left = e.clientX - rect.left - (activeDot.offsetWidth/2);
        if( top >= 0 && top <= (mv.offsetHeight - activeDot.offsetHeight)  && left >= 0 && left <= (mv.offsetWidth - activeDot.offsetWidth) ) {
            activeDot.style.top = (top) + 'px';
            activeDot.style.left = (left) + 'px';

            activeDot.getElementsByClassName('top')[0].value = top;
            activeDot.getElementsByClassName('left')[0].value = left;
        }

    }

    function addImage() {
        setTimeout(function(){
            var img = document.getElementsByClassName("middle")[0];
            if(typeof img === "undefined") {
                addImage();
            }
            else {
//                mv.style.backgroundImage = img.style.backgroundImage;
                mv.style.width = img.style.width;
                mv.style.height = img.style.height;
            }
        }, 500);

    }

</script>