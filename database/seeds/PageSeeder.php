<?php

use Illuminate\Database\Seeder;

use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            [
                'name' => 'Гарантия',
                'sysname' => 'warranty',
                'content' => <<<EOT
                    <p>Гарантийный срок на продукцию фирмы Gardena составляет один год с момента продажи изделия, а
                        также дополнительно продлена гарантия до 3-х лет на электрические части электрических моторов.
                        При обнаружении в течении гарантийного срока в данном изделии недостатков, кроме возникших после
                        передачи товара потребителю вследствие нарушения последним правил транспортировки, хранения,
                        либо использования, а также действий третьих лиц или обстоятельств непреодолимой силы,
                        уполномоченные сервисные центры произведут, в соответствии с законом, ремонт или замену изделия,
                        бесплатно для потребителя при сл</p>

                    <ul>
                        <li>Неисправность не является следствием неправильной эксплуатации, небрежности или неправильной
                            регулировки со стороны пользователя.
                        </li>
                        <li>Неисправность не является следствием естественного износа или выработки ресурса детали или
                            изделия.
                        </li>
                        <li>Гарантийные обязательства распространяются только на изделия, использующиеся в личных целях,
                            не связанных с коммерческим использованием. В противном случае гарантийный срок составляет
                            три месяца.
                        </li>
                        <li>Данная гарантия действительна только на территории России на сертифицированные, официально
                            поставленные изделия, приобретённые на территории России.
                        </li>
                    </ul>


                    <p>Претензии по качеству изделия принимаются только при условии, что недостатки обнаружены и
                        рекламация заявлена в течении гарантийного срока, установленного на изделие.</p>


                    <h2>Гарантийные обязательства не распространяются</h2>


                    <ul>
                        <li>На замену изношенного или повреждённого режущего оборудования.</li>
                        <li>На неисправности, возникшие в результате несообщения о первоначальной неисправности.</li>
                        <li>На неисправности изделия, возникшие в результате нарушения инструкций и рекомендаций,
                            содержащихся в инструкции по эксплуатации.
                        </li>
                        <li>На неисправности, вызванные небрежным отношением обращением или плохим уходом, неправильным
                            использованием (включая перегрузку), подключением к неправильному напряжению питания.
                        </li>
                        <li>На детали, вышедшие из строя вследствие естественного износа, на пример цепи, шины, свечи,
                            звёздочки, триммерные головки, ножи, фильтры, приводные ремни и.т., а также детали, срок
                            службы
                            которых зависит от регулярного технического обслуживания.
                        </li>
                        <li>На недостатки, которые вызваны независящим от производителя причинами, такие как стихийные и
                            иные бедствия, попадания внутрь изделия посторонних предметов (жидкостей), перепады
                            напряжения и
                            другими подобными причинами.
                        </li>
                        <li>На такие виды работ, как регулировка, чистка, замена расходных материалов, а также
                            периодическое обслуживание и прочий уход за изделием, оговорённый в руководстве оператора.
                        </li>
                        <li>На предпродажную подготовку.</li>
                    </ul>

                    <p>В случае возврата принимаются только полностью укомплектованные изделия (упаковка является частью
                        комплекта).
                    </p>

                    <h2>Условия возврата и обмена товара</h2>

                    <p>При приобретении товара через интернет-магазин Вы вправе отказаться от товара в любое время до
                        его передачи, а после передачи в течении семи дней в соответствии со ст. 26.1 Закона РФ " О
                        защите прав потребителей"
                    </p>
                    <p>Возврат товара надлежащего качества возможен в случае, если сохранены его товарный вид,
                        потребительские свойства, а также документ подтверждающий факт покупки (кассовый/товарный чек,
                        товарная накладная)
                    </p>
                    <p>При отказе от переданного Вам товара надлежащего качества в течении семи дней продавец имеет
                        право не возвращать стоимость доставки товара.
                    </p>
                    <p>При возврате товара ненадлежащего качества, на которые установлен гарантийный срок необходимо
                        иметь заключение авторизованного сервисного центра.
                    </p>
                    <p><strong>Адреса ближайших авторизованных сервисных центров можно узнать по телефону горячей линии</strong>
                        <span class="mod-fs-140 mod-col-or">8-800-200-1962</span>.
                    </p>
EOT
            ], [
                'name' => 'О компании',
                'sysname' => 'about',
                'content' => <<<EOT
                    <p>Наша компания вот уже более 15 лет на Российском рынке занимается розничной продажей товаров под
                        торговой маркой "GARDENA". Товары выпускаемые данным брендом имеют широкую географию
                        производства: Германия, Италия, Чехия, Китай, Великобритания. В 2013 году нами был создан
                        интернет-магазин www.market-sad.ru, который с первых дней своего существования показал, как не
                        хватает честных и добросовестных онлайн продавцов, которые способны отвечать "за свои слова".
                        Опыт накопленный нами за время работы через розничный канал дал нам понимание того чего не
                        хватает каждому покупателю при покупках в интернет-магазинах и мы готовы это Вам
                        предоставить...</p>
                    <img src="img/img-about.jpg" class="mod-full-width"/>

                    <div class="h1">Что мы предлагаем:</div>

                    <div class="col-wrap">
                        <div class="col-2">
                            <div class="about-block mod-icon1">
                                <div class="h3">Широкий ассортимент:</div>
                                <p>Огромный выбор всего поставляемого в Россию
                                    товара и собственный склад позволяет нам оперативно удовлетворять любые потребности,
                                    даже самого требовательного розничного и оптового покупателя.
                                    На сайте присутствует полная информация о товаре, позволяющая сделать правильный
                                    выбор.
                                    Вся продукция отвечает требованиям сертификации, а производитель регулярно обновляет
                                    и
                                    дополняет каталог продукции.</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="about-block mod-icon2">
                                <div class="h3">Профессиональная консультация:</div>
                                <p>Наш интернет-магазин работает по иному принципу чем множество магазинов подобного
                                    рода.
                                    При обращении к нам Вы получите всестороннюю консультацию именно технических
                                    специалистов*, работающих "на полях", а не просто девочек-консультантов, которые
                                    что-то,
                                    где-то почитали.</p>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="col-2">
                            <div class="about-block mod-icon3">
                                <div class="h3">Высокое качество и гарантии:</div>
                                <p>Вся продукция, выпускаемая под торговой маркой GARDENA отвечает самым высоким
                                    стандартам
                                    качества, проходит испытания в "жёстких" условиях. На всю продукцию предоставляется
                                    гарантия.</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="about-block mod-icon4">
                                <div class="h3">Приемлемые цены:</div>
                                <p>Мы являемся крупнейшими дилерами в России по продаже садового оборудования марки
                                    GARDENA
                                    и это даёт нам возможность предлагать товары с максимально низкими ценами для всех
                                    покупателей. Также мы всегда рады предложить индивидуальные условия для оптовых и
                                    постоянных покупателей.</p>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="col-2">
                            <div class="about-block mod-icon5">
                                <div class="h3">Быстрая доставка:</div>
                                <p>Наличие огромного склада товара и собственная курьерская служба позволяет нам
                                    оперативно
                                    решать вопросы связанные с доставкой заказов. Для нас важен каждый покупатель и мы
                                    ценим
                                    Ваше время поэтому стараемся в максимально короткие сроки обработать и доставить Ваш
                                    заказ. Обычно это занимает 1-2 дня.</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="about-block">
                                <img src="img/img-yandex.jpg"/>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="col-1">
                            <div class="about-block">
                                <div class="mod-col-or"><strong>Мы готовы к плодотворному сотрудничеству и новым достижениям! А
                                    фотографии нашего магазина ниже надеемся подтолкнут Вас к нужному выбору!!!</strong></div>
                            </div>
                        </div>

                    </div>


                    <div class="h1">Фото нашего магазина</div>
                    <div class="col-wrap">
                        <div class="col-2">
                            <a data-fancybox="group" href="img/photo.jpg"><img src="img/photo.jpg"/></a>
                        </div>
                        <div class="col-2">
                            <a data-fancybox="group" href="img/photo1.jpg"><img src="img/photo1.jpg"/></a>
                        </div>
                        <div class="clear"></div>
                        <div class="col-2">
                            <a data-fancybox="group" href="img/phono2.jpg"><img src="img/phono2.jpg"/></a>
                        </div>
                        <div class="col-2">
                            <a data-fancybox="group" href="img/photo3.jpg"><img src="img/photo3.jpg"/></a>
                        </div>
                        <div class="clear"></div>

                        <div class="text-center">
                            <button class="btn-blue-border">
                                Показать ещё
                            </button>

                        </div>

                    </div>
EOT
            ], [
                'name' => 'Доставка и оплата',
                'sysname' => 'delivery',
                'content' => <<<EOT
                    <div class="title-line"><h2>Оплата</h2><span>При заказе товара в интернет магазине www.market-sad.ru. Вы можете выбрать удобный для Вас способ оплаты:</span>
                    </div>

                    <div class="orange-box">
                        <div class="orange-box_title mod-icon-wallet">Оплата наличными</div>
                        <p>При оформлении заказа необходимо выбрать способ оплаты "Наличные". Заказ доставляется клиенту
                            и оплачивается на месте при получении товара курьеру. Вместе с товаром покупателю передаётся
                            кассовый/товарный чек, гарантийный талон (на аккумуляторную/электрическую технику).</p>
                    </div>
                    <div class="orange-box">
                        <div class="orange-box_title mod-icon-ar-left-bottom">Оплата по безналичному расчёту</div>

                        <p>При оформлении заказа необходимо выбрать способ оплаты "Безналичный расчёт". В появившееся
                            поле загрузить карточку организации и отправить вместе с заказом. Мы Вам выставляем счёт,
                            тем самым подтверждая наличие товара на складе, на основании которого вы производите
                            оплату.</p>
                        <p class="mod-col-or">Внимание!!! В целях борьбы с мошенниками отправка товара по платёжным
                            поручениям а также выдача товара "на доверии" не осуществляется.</p>
                        <p>Доставка товара осуществляется после фактического поступления денежных средств на наш
                            расчётный счёт. При получении товара необходима доверенность установленной формы или печать
                            организации-плательщика. Вместе с товаром передаётся оригинал счёта, товарная накладная,
                            (счёт-фактуру не выдаём, т.к. работаем без НДС) гарантийный талон (на
                            аккумуляторную/электрическую технику).</p>
                    </div>
                    <div class="orange-box">
                        <div class="orange-box_title mod-icon-card">Оплата банковской картой</div>
                        <p>Обратите внимание! Данная услуга доступна только при самовывозе товара из магазина-партнёра.
                        </p>
                        <p>Также доступна возможность перевода с карты на карту клиентам Сбербанка. Номер карты и данные
                            получателя высыляются после оформления заказа и указания контактов плательщика.
                        </p>
                    </div>

                    <h2 class="mod-col-or">Доставка</h2>
                    <p>При оформлении заказа, помимо способа оплаты, необходимо указать адрес, способ доставки и
                        контактные данные получателя.</p>
                    <p>Доставка по Москве и МО осуществляется нашими курьерами. Доставка в другие регионы РФ
                        осуществляется транспортными компаниями по условиям и тарифам выбранной Вами ТК.</p>

                    <h3>Доставка по Москве в пределах МКАД:</h3>

                    <p><span class="mod-col-or">Доставка осуществляется с 11-00 до 22-00 ч.</span>,, как в рабочие, так
                        и в выходные дни в зависимости от загруженности. Возможна доставка в день заказа или к
                        определённому часу. Расчёт стоимости приведён ниже в таблице:</p>

                    <table class="mod-two-half">
                        <tr>
                            <th>Сумма заказа</th>
                            <th>Стоимость доставки</th>
                        </tr>
                        <tr>
                            <td>До 3 000 руб.</td>
                            <td>550 руб.</td>
                        </tr>
                        <tr>
                            <td>От 3 000 - 15 000 руб.</td>
                            <td>350 руб.</td>
                        </tr>
                        <tr>
                            <td>Свыше 15 000 руб.</td>
                            <td>Беспл.</td>
                        </tr>
                        <tr>
                            <td>Доставка к определённому часу (+/- 30 мин.)</td>
                            <td>Дополнительно 500 руб. к чеку.</td>
                        </tr>
                    </table>

                    <h3>Доставка по МО и по Новой Москве за пределами МКАД:</h3>

                    <p>Доставка по Московской области и Москве за пределами МКАД рассчитывается методом сложения
                        доставки, указанной в таблице выше, + 30 руб. за каждый км. от МКАД. Точную стоимость доставки
                        можно узнать по телефону у менеджера. Пример: Вы заказали товар на сумму 12 500 руб.
                        Территориально Вы находитесь в 15-ти км. от МКАД, значит общая стоимость доставки будет равна
                        350 руб. + 450руб.(30 руб.х15 км.). Итого: 800 руб.</p>

                    <h3>Доставка в регионы РФ через транспортные компании:</h3>
                    <p>По вашему желанию мы отправляем товар через транспортные компании во все точки страны, где есть
                        терминалы, выбранной Вами ТК. Доставка до терминала в Москве осуществляется в течении 2-3х дней
                        с момента зачисления средств на наш р/с (обычно деньги от юридических лиц поступают в течении
                        1-3х рабочих дней, но если Вы платите частным образом, то средства иногда могут зачислятся в
                        течении 10-ти рабочих дней, просьба это учитывать при планировании заказов). Товар отгружается
                        исключительно по 100% предоплате!!!</p>


                    <div class="warning">
                        Уважаемые покупатели будьте внимательны!!! Стоимость доставки до терминалов Транспортных компаний в Москве бесплатная, а стоимость и сроки доставки из терминалов Москвы до терминала Вашего города необходимо уточнять у грузоперевозчика!!! Для товаров, которые могут пострадать в процессе перевозки обязательно заказывается жёсткая упаковка.
                    </div>
EOT
            ], [
                'name' => 'Контакты',
                'sysname' => 'contacts',
                'content' => <<<EOT
                    <div class="contact-line mod-icon-phone-or-big">
                        <div><span>8 (495)</span> 642-27-84</div>
                        <span>Телефон в Москве</span>
                    </div>


                    <div class="contact-line mod-icon-letter-or-big">
                        <div>zakaz@garden-clab.ru</div>
                        <span>Электронная опчта</span>
                    </div>

                    <div class="contact-line">
                        <p>ИП “Сеу Дойна Васильевна”<br/>
                            ОГРНИП: 315503100002082</p>
                    </div>

                    <div class="contact-line mod-icon-geo-or-big">
                        <div>АДРЕС</div>
                        <p>Юридический адрес: Москва, 92 км МКАД,<br/>
                            внешнее кольцо, дом павильон В 25. Г10 ОГРНИП: 315503100002082</p>
                    </div>
EOT
            ], [
                'name' => 'Самовывоз',
                'sysname' => 'pickup',
                'content' => '<p>Самовывоз</p>',
            ], [
                'name' => 'Сертификаты',
                'sysname' => 'sertificates',
                'content' => '<p>Сертификаты</p>',
            ]
        ];

        foreach($pages as $page) Page::firstOrCreate($page);
    }
}
