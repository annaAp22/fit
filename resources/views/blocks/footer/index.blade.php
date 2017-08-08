<footer>
  <div class="container">
    <div class="footer">
      <div class="navigation-footer">
        @widget('CatalogWidget', ['type' => 'footerMenu'])
        {{--@widget('FooterList', ['page_title' => 'Для клиентов', 'type' => 'clients'])--}}
        @widget('FooterList', ['page_title' => 'Инфо'])
        <div class="navigation-footer__column">
          <div class="navigation-footer__contacts">
            <div class="navigation-footer__title">Контакты
            </div>
            <ul>
              <li class="navigation-footer__phone"><i class="sprite_main sprite_main-footer__phone"></i>
                <div class="item">{!! $global_settings['phone_number']->value['free'] !!}<br/><span>Бесплатно по России</span>
                </div>
              </li>
              <li class="navigation-footer__phone"><i class="sprite_main sprite_main-footer__phone"></i>
                <div class="item">{!! $global_settings['phone_number']->value['msk'] !!}<br/><span>с {{ $global_settings['schedule']->value['start_workday'] }} до {{ $global_settings['schedule']->value['end_workday'] }} без выходных</span>
                </div>
              </li>
              <li class="navigation-footer__phone">
                <i class="sprite_main sprite_main-form-input-letter-green"></i>
                <div class="item">&nbsp;&nbsp;<a href="mailto:{{$global_settings['email_support']->value}}">{!! $global_settings['email_support']->value !!}</a></div>
              </li>

            </ul>
          </div>
          <div class="navigation-footer__social-buttons">
            <div class="navigation-footer__title navigation-footer__title_mt">Мы в соц. сетях
            </div>
            <div class="navigation-footer__social">
              <a href="{!! $global_settings['social_vk']->value !!}" target="_blank"><i class="sprite_main sprite_main-social__footer_vk"></i></a>
              <a href="{!! $global_settings['social_fb']->value !!}" target="_blank"><i class="sprite_main sprite_main-social__footer_facebook"></i></a>
              <a href="{!! $global_settings['social_instagram']->value !!}" target="_blank"><i class="sprite_main sprite_main-social__footer_instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="footer__info">
        <div class="footer__requisites">{!! $global_settings['site_info']->value !!}
        </div>
        <div class="footer__address">{!! $global_settings['address']->value !!} Телефон: {!! $global_settings['phone_number']->value['free'] !!}.
        </div>
        <div class="footer__copyright">2011–{{ date('Y') }}. ©Копирование материалов сайта разрешено только при наличии письменного согласия администрации fit2u.ru.
        </div>
      </div>
    </div>
  </div>
  <div class="line line_footer">
  </div>
</footer>
<div class="map" id="map">
</div>
<div id="tooltip">Этого размера нет в наличии, но Вы можете оформить предзаказ</div>
@include('blocks.counters')