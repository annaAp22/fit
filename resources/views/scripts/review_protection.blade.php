<script type="text/javascript">
    var reg_form = document.forms['review-form'];
    reg_form.onsubmit = function(e) {
        if(!reg_form['type'].value) {
            reg_form['type'].value = 444 + 222;
        }
    };
</script>
