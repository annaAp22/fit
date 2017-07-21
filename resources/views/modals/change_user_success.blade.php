<form id="change-user-success" name="change-user-success" data-submit-on-close="true" action="{{route('room')}}" method="get">
    <div class="modal-box">
        <div class="form-success form-success_modal"><i class="sprite_main sprite_main-form-succsess-smile-gray"></i>
            <div class="form-success__title">Спасибо, {{ $user->name }}!
            </div>
            <div class="form-success__text">Ваши данные успешно изменены!
            </div>
        </div>
        <button data-fancybox-close class="modal-close">&#10006;</button>
    </div>
</form>

