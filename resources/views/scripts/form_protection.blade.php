<script type="text/javascript">
    var protection_forms = document.forms;
    var form;
    for(var i = 0; i < protection_forms.length; i++) {
        form = protection_forms[i];
        if(typeof form['type'] != 'undefined') {
            form.onsubmit = function(e) {
                if(e.target['type'].value == '') {
                    e.target['type'].value = 444 + 222;
                }
            };
        }
    }
</script>
