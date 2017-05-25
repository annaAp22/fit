@extends('layouts.main')

@section('breadcrumbs')
	{!!  Breadcrumbs::render('page', $page) !!}
@endsection

@section('content')
	<main class="container">
		<div class="container">
			<aside class="sidebar">
				@widget('TagsWidget')
				@widget('BannerLeftWidget')
			</aside>
			<section class="content">
				<div class="container-in">

					<!-- Header -->
					<div class="header-listing">
						<h1>Контакты</h1>
						<!-- Back to shopping link-->
						<a class="btn btn_back-link" href="#" onclick="location.href = document.referrer;">
                            <span class="icon-fade">
                                <i class="sprite_main sprite_main-icon-arrow-small-left-gray normal"></i>
                                <i class="sprite_main sprite_main-icon-arrow-small-left-green_active active"></i>
                                <span>Назад к покупкам</span>
                            </span>
						</a>
					</div>

					<div class="page-text">




					</div>

				</div>
			</section>

			<section class="content-full-width">
				@widget('SubscribeWidget')
			</section>
		</div>
	</main>
@endsection