// Подстановка параметров в строку
// Например:
// window.injectParams('/url/#id/#name', { id: 'foo', name: 'bar' })
// => /url/foo/bar
window.injectParams = function(str, params) {
    for(var paramName in params)
        str = str.split('#' + paramName).join(params[paramName]);
    return str;
};