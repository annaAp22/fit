<footer>
  <div class="container">
    <div class="footer">
      <div class="navigation-footer">
        @widget('CatalogWidget', ['type' => 'footerMenu'])
        @widget('FooterList', ['page_title' => 'Для клиентов', 'type' => 'clients'])
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
            </ul>
          </div>
          <div class="navigation-footer__social-buttons">
            <div class="navigation-footer__title navigation-footer__title_mt">Мы в соц. сетях
            </div>
            <div class="navigation-footer__social"><a href="#"><i class="sprite_main sprite_main-social__footer_vk"></i></a><a href="#"><i class="sprite_main sprite_main-social__footer_facebook"></i></a><a href="#"><i class="sprite_main sprite_main-social__footer_instagram"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="footer__info">
        <div class="footer__requisites">Интернет-магазин «Fit2U». Одежда для фитнеса и танцев "Profit" . ООО "Твой Фитнес имидж". ОГРН: 1127747190350, Юр.адрес: 115682, Москва г, Шипиловская ул, дом 64, корпус 1, офис 147.
        </div>
        <div class="footer__address">{!! $global_settings['address']->var !!} Телефон: {!! $global_settings['phone_number']->value['free'] !!} 2011–@php echo date('Y')@endphp.
        </div>
        <div class="footer__copyright">©Копирование материалов сайта разрешено только при наличии письменного согласия администрации fit2u.ru.
        </div>
      </div>
    </div>
  </div>
  <div class="line line_footer">
  </div>
</footer>
<div class="map" id="map">
</div>