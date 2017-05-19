{{--
[16:48:31] Higoglhf1: для кнопок есть отдельный файл стилей
[16:49:03] Higoglhf1: можешь взять .btn .btn_green + добавить свои стили
[16:49:39] Higoglhf1: Я думаю слово обхват спрячешь на xs и всё войдёт как есть
[16:50:03] Higoglhf1: добавь стили для этого модальника в файл _modals.sass
[16:50:26] Higoglhf1: чтобы спрятать используй примесь +hidden-xs()
[16:51:40] Higoglhf1: вместо медиа квери +media-lg() +media-md() и тд, например для всего, что меньше md надо так +media-sm(down)
[16:52:06] Higoglhf1: ну и можешь использовать flex, тут весь сайт на нём
[16:53:22] Programmer Free: а что тут исползуется - media-lg, это самописная часть или что-то типа bootstrap?
[16:53:24] Higoglhf1: для открытия модальника передай из контроллера в ответе 'modal' => view( ... )->render()
[16:53:45] Higoglhf1: сейчас скину на документацию ссылку
[16:54:16] Higoglhf1: https://gmdjs.github.io/
--}}
<div class="modal-sizes">
    <div class="caption">Таблица размеров</div>
    <div class="subcaption"></div>
    <table>
        <tr>
            <th>Размер</th>
            <th>Обхват груди</th>
            <th>Обхват талии</th>
            <th>Обхват бедер</th>
        <tr>
            <td>40</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>42</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>44</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>46</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>48</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </table>
    <div class="banner">
        <div class="left"></div>
        <img class="middle" src="" alt="">
        <div class="right"></div>
    </div>
</div>