<div id="order" class="modal-box" style="display: inline-block;">
    <form class="js-form-ajax" action="{{ route('ajax.letter') }}" method="POST">
        <div class="form-modal">
            {{ csrf_field() }}
            <input type="hidden" name="action" value="letterSuccess">
            <input type="hidden" name="text" value="Клиент желает сотрудничать">
            <div class="form-modal_title">ЗАЯВКА</div>
            <div class="form-modal_line">
                <label>Ваше имя: <span class="mod-col-or">*</span></label>
                <input class="js-required-fields input input_text" type="text" name="name"/>
            </div>
            <div class="form-modal_line">
                <label>Ваш email: <span class="mod-col-or">*</span></label>
                <input class="js-required-fields input input_text" type="text" name="email"/>
            </div>
            <div class="form-modal_line">
                <button class="btn btn_green">ОТПРАВИТЬ</button>
            </div>
        </div>
    </form>
    <button data-fancybox-close  class="modal-close">&#10006;</button>
</div>