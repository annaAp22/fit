@extends('layouts.main')

@section('breadcrumbs')
	{!!  Breadcrumbs::render('page', $page) !!}
@endsection

@section('content')
	<main class="container">
	<div class="page-single">
		<h1 class="page-single_title">Контакты</h1>
		<div class="wrap-typography">
			<p class="mod-mar-bottom-30">Внимание!!! Звонки принимаются строго с 8-00 до 19.00 ч. В остальное время можно воспользоваться
				он-лайн заказом через интернет-сайт.</p>

			<div class="contact-form">
				<div class="contact-form_title">ОБРАТНАЯ СВЯЗЬ</div>
				<form id="contacts-form" action="{{ route('ajax.letter') }}" method="POST" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="form-line">
						<input type="text" name="name" placeholder="Ваше имя" required />
					</div>
					<div class="form-line">
						<input type="email" name="email" placeholder="Ваш @mail" required />
					</div>
					<div class="form-line">
						<textarea rows="10" name="text" placeholder="Текст вопроса" required ></textarea>
					</div>
					<div class="form-line">
						<input type="submit" class="btn-blue" value="ОТПРАВИТЬ">
					</div>
				</form>
			</div>

			{!! $page->content !!}

		</div>
	</div>

        <section class="content-full-width">
            @widget('SubscribeWidget')
        </section>
	</main>
@endsection